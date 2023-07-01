<?php

namespace bhenk\msdata\connector;

use bhenk\logger\log\Log;
use Exception;
use mysqli;
use Throwable;
use function dirname;
use function get_class;
use function is_dir;
use function is_file;
use function mysqli_get_client_info;

/**
 * Static wrapper around a mysqli instance
 *
 * Convenience class for a {@link mysqli} connection and configuration.
 * Configuration options can be expressed in a configuration file. The configuration file
 * can be set with the method {@link MysqlConnector::setConfigFile()} or you can rely on auto-finding
 * of the file.
 *
 * If relying on auto-finding of configuration, this class will look for a configuration file
 * with the name *msd_config.php* in a directory with the name *config*. The *config* directory should be
 * a child of an ancestor directory of this code base:
 * ```
 * {ancestor directory}/config/msd_config.php
 * ```
 * The configuration file should return configuration options as an array:
 * ```
 * <?php
 *
 * return [
 *     "hostname" => {string},     // required
 *     "username" => {string},     // required
 *     "password" => {string},     // required
 *     "database" => {string},     // required
 *     "port" => {int},            // optional, default 3306
 *     "persistent" => {bool},     // optional, default true
 *     "use_parameterized_queries" => {bool} // optional, default true
 * ];
 * ```
 *
 * A connection via *libmysqlclient* will not allow binding parameters in execute and produce the
 * following error:
 * ```
 * ArgumentCountError: Binding parameters in execute is not supported with libmysqlclient
 * ```
 * In that case set *use_parameterized_queries* to *false*.
 *
 * A third method of setting the configuration is by programmatically calling
 * {@link MysqlConnector::setConfiguration()} with the appropriate array, like shown above.
 */
class MysqlConnector {

    /**
     * Name of the directory where a configuration file is expected
     */
    const CONFIG_DIR = "config";
    /**
     * Name of the expected configuration file
     */
    const CONFIG_FILE = "msd_config.php";

    private static ?MysqlConnector $instance = null;
    private mysqli $mysqli;
    private bool|string $config_file = false;
    private array $configuration = [];

    /**
     * Get the singleton instance of this class
     *
     * @return MysqlConnector
     */
    public static function get(): MysqlConnector {
        if (self::$instance == null)
            self::$instance = new MysqlConnector();
        return self::$instance;
    }

    /**
     * Close the connection (if any)
     * @return void
     */
    public static function closeConnection(): void {
        Log::debug("Trying to close connection to mysql database");
        if (!self::$instance == null) {
            if (isset(self::$instance->mysqli)) {
                self::$instance->mysqli->close();
                unset(self::$instance->mysqli);
                Log::info("Closed connection to mysql database");
            } else {
                Log::debug("No connection was open or unreachable");
            }
        } else {
            Log::debug("No instance of class " . static::class);
        }
    }

    /**
     * Get the (absolute path to the) configuration file
     *
     * @return bool|string absolute path to configuration file or *false* if not set
     * @see MysqlConnector::getConfiguration()
     *
     */
    public function getConfigFile(): bool|string {
        return $this->config_file;
    }

    /**
     * Set the configuration file
     *
     * When not using auto-find of configuration, this method must be called before a call to
     * {@link MysqlConnector::getConnector()}.
     * @param bool|string $config_file absolute path to a configuration file, or *false* when returning to
     *    auto-find configuration
     */
    public function setConfigFile(bool|string $config_file): void {
        $this->config_file = $config_file;
    }

    /**
     * Returns status info
     *
     * Something like
     * ```
     * Uptime: 80984
     * Threads: 2
     * Questions: 1327
     * Slow queries: 0
     * Opens: 432
     * Flush tables: 3
     * Open tables: 274
     * Queries per second avg: 0.016"
     * ```
     * @return bool|string a string describing the server status, *false* if an error occurred
     * @throws Exception
     */
    public function statusInfo(): bool|string {
        $conn = $this->getConnector();
        $stat = $conn->stat();
        Log::info("Status info: ", [$stat]);
        return $stat;
    }

    /**
     * Get the connector
     *
     * @return mysqli connector to database
     * @throws Exception if connection could not be established, code 100
     */
    public function getConnector(): mysqli {
        if (!isset($this->mysqli) or empty($this->configuration)) {
            $config = $this->getConfiguration();
            $persistent = $config["persistent"] ?? true;
            $hostname = $persistent ? "p:" . $config["hostname"] : $config["hostname"];
            $port = $config["port"] ?? 3306;
            try {
                $this->mysqli = new mysqli($hostname, $config["username"],
                    $config["password"], $config["database"], $port);
                Log::info("Created connection to mysql database '" . $config["database"] . "'");
            } catch (Exception $e) {
                $msg = "Could not create connection to mysql database '"
                    . $config["database"] . "'";
                Log::alert($msg, [$e]);
                throw new Exception($msg, 100, $e);
            }
        }
        return $this->mysqli;
    }

    /**
     * The value of the configuration option use_parameterized_queries
     * @return bool default *true*
     * @throws Exception
     */
    public function useParameterizedQueries(): bool {
        return $this->getConfiguration()["use_parameterized_queries"] ?? true;
    }

    /**
     * Get the configuration
     *
     * If configuration not set, the array will be read from the configuration file, either from the configuration
     * file as given with {@link MysqlConnector::setConfigFile()} or from the auto-find configuration file at
     * ```
     * {ancestor directory}/config/msd_config.php
     * ```
     *
     * @return array configuration array
     * @throws Exception if configuration could not be read
     */
    public function getConfiguration(): array {
        if (empty($this->configuration)) {
            try {
                return $this->readConfiguration();
            } catch (Throwable $e) {
                $msg = "Could not read configuration:";
                Log::alert($msg, [$e]);
                throw new Exception($msg, 101, $e);
            }
        } else {
            return $this->configuration;
        }
    }

    /**
     * Set configuration as an array
     *
     * @param array $configuration configuration as described in comment on this class
     * @throws Exception if given configuration is not valid
     * @see MysqlConnector
     *
     */
    public function setConfiguration(array $configuration): void {
        $this->validateConfiguration($configuration);
        $this->configuration = $configuration;
    }

    /**
     * @return array
     * @throws Exception
     */
    private function readConfiguration(): array {
        if ($this->config_file) {
            Log::debug("Reading configuration from " . $this->config_file);
            $this->configuration = require $this->config_file;
            $this->validateConfiguration($this->configuration);
            return $this->configuration;
        }
        $dir = dirname(__DIR__);
        for ($i = 1; $i < 20; $i++) {
            $dir = dirname(__DIR__, $i);
            $config_dir = $dir . DIRECTORY_SEPARATOR . self::CONFIG_DIR;
            if (is_dir($config_dir)) {
                $config_file = $config_dir . DIRECTORY_SEPARATOR . self::CONFIG_FILE;
                if (is_file($config_file)) {
                    $this->config_file = $config_file;
                    Log::debug("Reading configuration from config: " . $this->config_file);
                    $this->configuration = require $this->config_file;
                    $this->validateConfiguration($this->configuration);
                    return $this->configuration;
                } else {
                    $msg = "Configuration file " . $config_file . " not found";
                    Log::alert($msg);
                    throw new Exception($msg);
                }
            }
        }
        $msg = "Configuration directory '" . self::CONFIG_DIR . "' not found";
        Log::alert($msg);
        throw new Exception($msg);
    }

    /**
     * @throws Exception
     */
    private function validateConfiguration(array $config): void {
        $args = ["hostname", "username", "password", "database"];
        foreach ($args as $arg) {
            if (!isset($config[$arg])) {
                $msg = "'$arg' not found in configuration for " . get_class($this)
                    . " in configuration file " . $this->config_file;
                Log::alert($msg);
                throw new Exception($msg);
            }
        }
    }

    /**
     * Returns client and server info
     *
     * Something like
     * ```
     * client: mysqlnd 8.2.1
     * server: 8.0.32
     * host: 127.0.0.1 via TCP/IP
     * protocol version: 10
     * character set: utf8mb4
     * ```
     *
     * @return string client and server info
     * @throws Exception
     */
    public function connectionInfo(): string {
        $conn = $this->getConnector();
        $info = "client: " . mysqli_get_client_info()
            . " - server: " . $conn->get_server_info()
            . " - host: " . $conn->host_info
            . " - protocol version: " . $conn->protocol_version
            . " - character set: " . $conn->character_set_name();
        Log::info("Connection info: ", [$info]);
        return $info;
    }

}