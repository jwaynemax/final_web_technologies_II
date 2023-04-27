<?php
class Database {
    private $db;
    private $error_message;
    
    /**
     * Instantiates a new database object that connects
     * to the database
     */
    public function __construct() {
        $dsn = 'mysql:host=localhost;dbname=muscle_mayhem_justimaxwell';
        $username = 'popeye';
        $password = 'spinach';
        $this->error_message = '';
        try {
            $this->db = new PDO($dsn, $username, $password);
        } catch (PDOException $e) {
            $this->error_message = $e->getMessage();
        }
    }
    
    /**
     * Checks the connection to the database
     *
     * @return boolean - true if a connection to the database has been established
     */
    public function isConnected() {
        return ($this->db != Null);
    }
    
    /**
     * Returns the error message
     * 
     * @return string - the error message
     */
    public function getErrorMessage() {
        return $this->error_message;
    }
    
    /**
     * Checks if the specified username is in this database
     * 
     * @param string $username
     * @return boolean - true if username is in this database
     */
//    public function isValidUser($username) {
//        $query = 'SELECT * FROM users
//              WHERE username = :username';
//        $statement = $this->db->prepare($query);
//        $statement->bindValue(':username', $username);
//        $statement->execute();
//        $row = $statement->fetch();
//        $statement->closeCursor();
//        return !($row === false);
//    }
}
?>