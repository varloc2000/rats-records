<?php

namespace Varloc\Framework\Database;

class Connection
{
    private $pdo;
    private $error;
    
    /**
     * Set PDO instance
     */
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Apply select query and return result
     * @param string $query
     * @return boolean | null | array
     */
    function select($query)
    {
        $statement = $this->pdo->query($query);
        
        if (false === $statement) {
            $this->setError(
                $this->pdo->errorInfo(),
                $this->pdo->errorCode()
            );

            return false;
        }

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Apply select query and return single result
     * @param string $query
     * @return boolean | null | array
     */
    function selectOne($query)
    {
        $statement = $this->pdo->query($query);
        
        if (false === $statement) {
            $this->setError(
                $this->pdo->errorInfo(),
                $this->pdo->errorCode()
            );

            return false;
        }

        return $statement->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Apply any query and return success or not
     * @param string $query
     * @return boolean
     */
    function unselect($query)
    {
        $statement = $this->pdo->prepare($query);
        
        return $statement-execute();
    }

    /**
     * Set error
     */
    public function setError($errorCode, $errorMessage)
    {
        $this->error = sprintf(
            'Connection last error is: "%s" with code: "%s"',
            $errorMessage,
            $errorCode
        );
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
