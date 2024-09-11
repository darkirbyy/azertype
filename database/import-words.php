<?php

// Script configuration
$dbFile = 'words.db';
$tableName = 'french';
$filename = 'french.txt'; // must be encoded in UTF-8 NFC
$numberOfWords = 0;

// Create a new SQLite database or open the existing one
$pdo = new PDO("sqlite:$dbFile");

// Set error mode to exceptions
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Optimize SQLite for bulk inserts
$pdo->exec("PRAGMA synchronous = OFF;");
$pdo->exec("PRAGMA journal_mode = MEMORY;");

// Create the table if it does not exist
$createTableSQL = "
CREATE TABLE IF NOT EXISTS $tableName (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    word TEXT NOT NULL,
    length INTEGER NOT NULL
)";
$pdo->exec($createTableSQL);

// Truncate the table to avoid duplicate
$truncateTableSQL = "DELETE FROM $tableName";
$pdo->exec($truncateTableSQL);

// Function to insert words into the database
function insertWordsIntoDB($pdo, $tableName, $words) {
    global $numberOfWords;

    // Begin transaction
    $pdo->beginTransaction();

    // Prepare insert statement
    $insertSQL = "INSERT INTO $tableName (word, length) VALUES (:word, :length)";
    $stmt = $pdo->prepare($insertSQL);

    foreach ($words as $word) {
        // Trim any whitespace and ensure word is lowercase
        $word = strtolower(trim($word));
        if (!empty($word) && preg_match('/^[a-zéèçàù-]+$/u', $word)){
            $length = mb_strlen($word); // Get the length of the word
            $stmt->execute([':word' => $word, ':length' => $length]);
            $numberOfWords++;
        }
    }

    // Commit transaction
    $pdo->commit();
}

// Process the file
if (file_exists($filename)) {
    // Read words from the file
    $words = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    // Insert words into the database
    insertWordsIntoDB($pdo, $tableName, $words);
    echo "Inserted words from $filename\n";
}
else {
    echo "File $filename not found, skipping.\n";
}

echo "Word import complete: $numberOfWords words added.\n";

?>
