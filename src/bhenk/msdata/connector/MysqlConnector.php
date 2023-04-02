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

class MysqlConnector {

    const CONFIG_DIR = "config";
    const CONFIG_FILE = "msd_config.php";

    private static ?MysqlConnector $instance = null;

    private mysqli $mysqli;
    private bool|string $config_file = false;
    private array $configuration = [];

    public static function get(): MysqlConnector {
        if (self::$instance == null)
            self::$instance = new MysqlConnector();
        return self::$instance;
    }

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
     * @return bool|string
     */
    public function getConfigFile(): bool|string {
        return $this->config_file;
    }

    /**
     * @param bool|string $config_file
     */
    public function setConfigFile(bool|string $config_file): void {
        $this->config_file = $config_file;
    }

    /**
     * @return array
     * @throws Exception
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
     * @param array $configuration
     * @throws Exception
     */
    public function setConfiguration(array $configuration): void {
        $this->validateConfig($configuration);
        $this->configuration = $configuration;
    }

    /**
     * @throws Exception
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
     * @throws Exception
     */
    private function validateConfig(array $config): void {
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
     * @return array
     * @throws Exception
     */
    private function readConfiguration(): array {
        if ($this->config_file) {
            Log::debug("Reading configuration from " . $this->config_file);
            $this->configuration = require $this->config_file;
            $this->validateConfig($this->configuration);
            return $this->configuration;
        }
        for ($i = 1; $i < 8; $i++) {
            $dir = dirname(__DIR__, $i);
            $config_dir = $dir . DIRECTORY_SEPARATOR . self::CONFIG_DIR;
            if (is_dir($config_dir)) {
                $config_file = $config_dir . DIRECTORY_SEPARATOR . self::CONFIG_FILE;
                if (is_file($config_file)) {
                    $this->config_file = $config_file;
                    Log::debug("Reading configuration from config: " . $this->config_file);
                    $this->configuration = require $this->config_file;
                    $this->validateConfig($this->configuration);
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

}