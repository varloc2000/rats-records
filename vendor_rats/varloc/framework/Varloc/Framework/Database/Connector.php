<?php

namespace Varloc\Framework\Database;

class Connector
{
    static private $PDODsn      = 'mysql:host=%s;dbname=%s;unix_socket=/var/lib/stickshift/52b1a5d04382ec1bda0001ed/app-root/data/lib/mysql/socket/mysql.sock';
    static private $DBName      = 'database_name';
    static private $DBUsername  = 'root';
    static private $DBPassword  = 'password';
    static private $DBHostName  = 'localhost';

    static private $connection;
    
    /**
     * Configure class to work with database
     * @param string $name
     * @param string $username
     * @param string $password
     */
    static public function configure($name, $username, $password = '')
    {
        self::$DBName       = $name;
        self::$DBUsername   = $username;
        self::$DBPassword   = $password;

        self::$PDODsn = sprintf(
            self::$PDODsn,
            self::$DBHostName,
            self::$DBName
        );
    }

    /**
     * Set connection with database
     * @return boolean
     */
    public static function connect()
    {
        $pdo = new \PDO(
            self::$PDODsn,
            self::$DBUsername,
            self::$DBPassword
        );

        self::$connection = new Connection($pdo);
        
        return self::$connection;
    }

    /**
     * Get connector Connection instance
     * @return Connection|null
     */
    public static function getActiveConnection()
    {
        if (!self::$connection instanceof Connection) {
            return self::connect();
        }
        
        return self::$connection;
    }
}