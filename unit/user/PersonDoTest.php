<?php

namespace user;

use bhenk\msdata\user\PersonDo;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

class PersonDoTest extends TestCase {

    public function testToArray() {
        $pdo = new PersonDo(42, "Karel", "van der",
            "Laan", "kvdl@test.com", 101,);
        $arr = $pdo->toArray();
        assertEquals(6, count($arr));
        $pdo2 = PersonDo::fromArray($arr);
        assertTrue($pdo->equals($pdo2));
        assertEquals([
            'ID' => 42,
            'first_name' => 'Karel',
            'prefixes' => 'van der',
            'last_name' => 'Laan',
            'email' => 'kvdl@test.com',
            'knows' => 101
        ], $arr);
    }

    public function testToString() {
        $expected = "bhenk\msdata\user\PersonDo" . PHP_EOL
            . "\t" . "ID (int) -> 42" . PHP_EOL
            . "\t" . "first_name (string) -> 'Karel'" . PHP_EOL
            . "\t" . "prefixes (string) -> 'van der'" . PHP_EOL
            . "\t" . "last_name (string) -> 'Laan'" . PHP_EOL
            . "\t" . "email (string) -> 'kvdl@test.com'" . PHP_EOL
            . "\t" . "knows (int) -> 101" . PHP_EOL;
        $pdo = new PersonDo(42, "Karel", "van der",
            "Laan", "kvdl@test.com", 101,);
        assertEquals($expected, $pdo->__toString());
    }

}
