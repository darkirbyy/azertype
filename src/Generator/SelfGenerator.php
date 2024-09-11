<?php

namespace Azertype\Generator;

use Azertype\Helper\DbHandler;

/**
 * Generate random words from local database
 */

class SelfGenerator extends AbstractGenerator
{
    private DbHandler $wordsDb;
    private bool $orderBySize;

    /**
     * Initialize the generator
     *
     * @param DbHandler $wordDb the database handling the listing of all words
     *
     * @param bool $orderBySize should the generated words be sorted by size
     * 
     */
    function initialize(DbHandler $wordDb, bool $orderBySize)
    {
        $this->wordsDb = $wordDb;
        $this->orderBySize = $orderBySize;
    }


    /**
     * Generate a list of words
     * 
     * @param int $size How many words to generate
     * 
     * @return string
     */
    function generate(int $size): string
    {
        if ($size < 1)
            return "";

        $wordsArray = [];
        $wordsString = "";

        if ($this->orderBySize) {
            $query = "SELECT word FROM french WHERE length BETWEEN :min AND :max ORDER BY RANDOM() LIMIT :count";
            for ($i = 0; $i < $size; $i++) {
                $queryResult = $this->wordsDb->readQuery($query, [$i * 2 + 1, $i * 2 + 2, 1]);
                if ($queryResult !== null)
                    $wordsArray = [...$wordsArray, $queryResult[0]];
            }
        } else {
            $query = "SELECT word FROM french ORDER BY RANDOM() LIMIT :count";
            $queryResult = $this->wordsDb->readQuery($query, [$size]);
            if ($queryResult !== null)
                $wordsArray = $queryResult;
        }

        foreach ($wordsArray as $wordArray) {
            $wordsString .= $wordArray['word'] . ",";
        }

        return substr($wordsString, 0, strlen($wordsString) - 1);
    }
}
