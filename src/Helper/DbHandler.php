<?php

namespace Azertype\Helper;
use PDO;

/**
 * Open the connection to the database and manage all queries
 */ 

class DbHandler{

    public PDO $pdo;

    /**
     * Open a PDO connection with sqlite
     */
    function __construct(){
        $dirPath = $_ENV['REL_ROOT'].$_ENV['DATABASE_DIR'];
        $filePath = $dirPath.$_ENV['DATABASE_NAME'].'.sqlite';
        if (!is_dir($dirPath)) {
            mkdir($dirPath);       
        } 
        $this->pdo = new PDO("sqlite:".$filePath);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Execute the read query with the given param, 
     * return an array response is succeed, null otherwise
     * 
     * @param string $query The query to make
     * @param array $param Array containing the value of each parameter 
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
     * @param array $param Array containing the value of each parameter 
     * 
     * @return ?int
     */
    function writeQuery(string $query, array $param = []) : ?int {
        $stmt = $this->pdo->prepare($query);
        $valid = $stmt->execute($param);
        return ($valid) ? $stmt->rowCount() : null;
    }
  
}