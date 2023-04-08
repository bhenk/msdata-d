<?php

namespace connector;

use bhenk\logger\unit\ConsoleLoggerTrait;
use bhenk\logger\unit\LogAttribute;
use bhenk\msdata\connector\MysqlConnector;
use mysqli;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertNotSame;
use function PHPUnit\Framework\assertSame;

#[LogAttribute(false)]
class MysqlConnectorTest extends TestCase {
    use ConsoleLoggerTrait;

    public function tearDown(): void {
        MysqlConnector::closeConnection();
        parent::tearDown();
    }

    #[LogAttribute(true)]
    public function testConnection() {
        $mysql = MysqlConnector::get();
        assertInstanceOf(MysqlConnector::class, $mysql);

        $mysqli01 = $mysql->getConnector();
        assertInstanceOf(mysqli::class, $mysqli01);

        MysqlConnector::closeConnection();
        $mysqli02 = $mysql->getConnector();
        assertInstanceOf(mysqli::class, $mysqli02);
        assertNotSame($mysqli01, $mysqli02);
        assertSame($mysql, MysqlConnector::get());
        MysqlConnector::closeConnection();
    }

//    #[LogAttribute(true)]
//    public function testWrongPassword() {
//        $configuration = [
//            "hostname" => "127.0.0.1",
//            "username" => "user",
//            "password" => "foo",
//            "database" => "test",
//        ];
//        $mysql = MysqlConnector::get();
//        $mysql->setConfiguration($configuration);
//        $this->expectException(Exception::class);
//        $mysql->getConnector();
//    }
//
//    #[LogAttribute(true)]
//    public function testWrongPassword2() {
//        $configuration = [
//            "hostname" => "127.0.0.1",
//            "username" => "user",
//            "password" => "foo",
//            "database" => "test",
//        ];
//        $mysql = MysqlConnector::get();
//        $mysql->setConfiguration($configuration);
//        $ex_thrown = false;
//        try {
//            $mysql->getConnector();
//        } catch (Exception $e) {
//            $ex_thrown = true;
//        }
//        assertTrue($ex_thrown);
//    }

    // long timeout
//    #[LogAttribute(false)]
//    public function testWrongHost() {
//        $configuration = [
//            "hostname" => "128.0.0.1",
//            "username" => "user",
//            "password" => "user",
//            "database" => "test",
//        ];
//        $mysql = MysqlConnector::get();
//        $mysql->setConfiguration($configuration);
//        $ex_thrown = false;
//        try {
//            $mysql->getConnector();
//        } catch (Exception $e) {
//            $ex_thrown = true;
//        }
//        assertTrue($ex_thrown);
//    }

}
