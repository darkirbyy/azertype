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

        $query = "SELECT word FROM french ORDER BY RANDOM() LIMIT :count";
        $queryResult = $this->wordsDb->readQuery($query, [$size]);
        if ($queryResult !== null)
            $wordsArray = $queryResult;

        if ($this->orderBySize) {
            usort($wordsArray, fn($a, $b) => mb_strlen($a['word']) <=> mb_strlen($b['word']));
        }

        foreach ($wordsArray as $wordArray) {
            $wordsString .= $wordArray['word'] . ",";
        }

        return substr($wordsString, 0, mb_strlen($wordsString) - 1);
    }
}
