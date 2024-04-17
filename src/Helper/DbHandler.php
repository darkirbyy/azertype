<?php

namespace Azertype\Helper;
use PDO;

/**
 * Open the connection to the database and manage all queries
 */ 

class DbHandler{

    private PDO $pdo;

    /**
     * Open a PDO connection with sqlite
     * 
     * @param string $path The full path do the database file
     */
    function __construct(?string $path = null){
        $path ??= $_ENV['APP_ROOT'].'database/'.$_ENV['DATABASE_FILENAME'];
        $this->pdo = new PDO("sqlite:".$path);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Execute the read query with the given param, 
     * return an array response is succeed, null otherwise
     * 
     * @param string $query The query to make
     * @param string $param Array containing the value of each parameter 
     * 
     * @return ?array
     */
    function readQuery(string $query, array $param = []) : ?array{
        $stmt = $this->pdo->prepare($query);
        $valid = $stmt->execute($param);
        return ($valid) ? $stmt->fetchAll() : null;
    }

    /**
     * Execute the write query with the given param, 
     * return the number of affected rows if succeed, null otherwise
     * 
     * @param string $query The query to make
     * @param string $param Array containing the value of each parameter 
     * 
     * @return ?int
     */
    function writeQuery(string $query, array $param = []) : ?int {
        $stmt = $this->pdo->prepare($query);
        $valid = $stmt->execute($param);
        return ($valid) ? $stmt->rowCount() : null;
    }

    /**
     * Read and execute a whole sql file 
     * 
     * @param string $filePath The full path to the .sql file
     * 
     * @return int|false
     */
    function exec(string $filePath) : int|false {
        return $this->pdo->exec(file_get_contents($filePath));
    }
   
}