<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace bhenk\msdata\abc;

use ValueError;

/**
 * Convertor for PHP-types to database-types
 */
enum DataTypes: string {

    /**
     * PHP string converts to database type VARCHAR(255)
     */
    case string = "VARCHAR(255)";
    /**
     * PHP int converts to database type INT
     */
    case int = "INT";
    /**
     * PHP bool converts to database type BOOLEAN
     */
    case bool = "BOOLEAN";
    /**
     * PHP float converts to database type FLOAT
     */
    case float = "FLOAT";

    /**
     * Get the database-type for the given PHP-type
     * @param string $name PHP-type name
     * @return string database-type name
     */
    public static function fromName(string $name): string {
        foreach (self::cases() as $type) {
            if ($name === $type->name) {
                return $type->value;
            }
        }
        throw new ValueError("$name is not a valid backing value for enum " . self::class);
    }
}
