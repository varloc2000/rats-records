<?php

namespace Varloc\DatabaseWorker;

class Connector
{
    private $DBName      = 'database_name';
    private $DBUsername  = 'root';
    private $DBPassword  = 'password';
    private $DBHostName  = 'localhost';

    private $connection;
    private $error;
    
    /**
     * Configure class to work with database
     * @param string $name
     * @param string $username
     * @param string $password
     */
    public function configure($name, $username, $password = '')
    {
        $this->DBName       = $name;
        $this->DBUsername   = $username;
        $this->DBPassword   = $password;
    }

    /**
     * Set connection with database
     * @return boolean
     */
    public function connect()
    {
        if (!$this->connection = mysql_connect($this->DBHostName, $this->DBUsername, $this->DBPassword)) {
            $this->error = mysql_error();
            return false;
        } else if (!mysql_select_db($this->DBName, $this->connection)) {
            $this->error = mysql_error();
            return false;
        } if (!mysql_set_charset('utf8', $this->connection)) {
            $this->error = mysql_error();
            return false;
        }

        return true;
    }

    /**
     * Apply select query and return result
     * @param string $query
     * @return boolean | null | array
     */
    function select($query)
    {
        if (!$res = mysql_query($query, $this->connection)) {
            $this->error = mysql_error();
            return false;
        }
        if (mysql_num_rows($res) == 0) {
            return null;
        }
        while ($row = mysql_fetch_assoc($res)) {
            $respounce[] = $row;
        }
        
        return $respounce;
    }

    /**
     * Apply any query and return success or not
     * @param string $query
     * @return boolean
     */
    function unselect($query)
    {
        if (!$res = mysql_query($query, $this->connection)) {
            $this->error = mysql_error();
            return false;
        }
        return true;
    }
    
    /**
     * Get error
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }
}