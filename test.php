<?php
class Select {
    private $pdo;

    // Constructor to initialize the PDO connection
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Method to select records with a join between two tables
    public function selectWithJoin($table1, $table2, $joinCondition, $columns = "*", $whereConditions = []) {
        try {
            $sql = "SELECT $columns FROM $table1 INNER JOIN $table2 ON $joinCondition";
            
            // Adding WHERE clause if conditions are provided
            if (!empty($whereConditions)) {
                $sql .= " WHERE " . $this->buildWhereClause($whereConditions);
            }

            $stmt = $this->pdo->prepare($sql);

            // Binding parameters for WHERE clause if conditions are provided
            if (!empty($whereConditions)) {
                foreach ($whereConditions as $column => $value) {
                    $stmt->bindValue(":$column", $value);
                }
            }

            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    // Helper method to build the WHERE clause for the select query
    private function buildWhereClause($conditions) {
        $whereClauses = [];
        foreach ($conditions as $column => $value) {
            $whereClauses[] = "$column = :$column";
        }
        return implode(' AND ', $whereClauses);
    }
}

// Example usage:

// Database configuration
$host = '127.0.0.1';
$db = 'your_database_name';
$user = 'your_username';
$pass = 'your_password';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}

// Instantiate the Select class
$select = new Select($pdo);

// Select records with a join between two tables
$table1 = 'table1';
$table2 = 'table2';
$joinCondition = 'table1.pk = table2.fk';
$columns = 'table1.column1, table2.column2';
$whereConditions = [
    'table1.column1' => 'value1',
    'table2.column2' => 'value2'
];

$results = $select.selectWithJoin($table1, $table2, $joinCondition, $columns, $whereConditions);

// Output results
foreach ($results as $row) {
    print_r($row);
}

?>