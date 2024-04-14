<?php

namespace Azertype\Helper;
use Azertype\Config;
use PDO;

class DbHandler{

    private PDO $pdo;

    /*
    Open a PDO connection to sql
    */ 
    function connect(){
        $dbFilePath = Config::ROOT.Config::DB_DIRECTORY.Config::DB_FILENAME;
        $this->pdo = new PDO("sqlite:".$dbFilePath);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /*
    Execute the read query with the given param, 
    return an array response is succeed, null otherwise
    */ 
    function readQuery(string $query, array $param = []) : ?array{
        $stmt = $this->pdo->prepare($query);
        $valid = $stmt->execute($param);
        return ($valid) ? $stmt->fetchAll() : null;
    }

    /*
    Execute the write query with the given param, 
    return the number of affected rows if succeed, null otherwise
    */ 
    function writeQuery(string $query, array $param = []) : ?int {
        $stmt = $this->pdo->prepare($query);
        $valid = $stmt->execute($param);
        return ($valid) ? $stmt->rowCount() : null;
    }
   
}