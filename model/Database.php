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
    public function isValidUser($username) {
        $query = 'SELECT * FROM Customers
              WHERE Username = :username';
        $statement = $this->db->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->execute();
        $row = $statement->fetch();
        $statement->closeCursor();
        return !($row === false);
    }

    /**
     * Add customer
     * @param type $username
     * @param type $password
     * @param type $first_name
     * @param type $last_name
     * @param type $address
     * @param type $city
     * @param type $state
     * @param type $postal
     * @param type $phone
     * @param type $email
     * @return type
     */
    public function addCustomer($username, $password, $first_name, $last_name, $address, $city, $state, $postal, $phone, $email) {
        $query = 'INSERT INTO Customers (Username, Password, First_Name, Last_Name, Address, City, State, Postal, Phone, Email)
                    VALUES (:username, :password, :first_name, :last_name, :address, :city, :state, :postal, :phone, :email)';
        $statement = $this->db->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->bindValue(':password', $password);
        $statement->bindValue(':first_name', $first_name);
        $statement->bindValue(':last_name', $last_name);
        $statement->bindValue(':address', $address);
        $statement->bindValue(':city', $city);
        $statement->bindValue(':state', $state);
        $statement->bindValue(':postal', $postal);
        $statement->bindValue(':phone', $phone);
        $statement->bindValue(':email', $email);
        $statement->execute();
        $row = $statement->fetch();
        $statement->closeCursor();
        return !($row === false);
    }

    public function getClasses() {
        $query = 'SELECT * FROM Classes';
        $statement = $this->db->prepare($query);
        $statement->execute();
        $classes = $statement->fetchAll();
        $statement->closeCursor();
        return $classes;
    }

    public function registerClass($Customer_id, $Class_id) {
        $query = 'INSERT INTO Registered_Classes (Customer_id, Class_id)
                    VALUES (:Customer_id, :Class_id)';
        $statement = $this->db->prepare($query);
        $statement->bindValue(':Customer_id', $Customer_id);
        $statement->bindValue(':Class_id', $Class_id);
        $statement->execute();
        $row = $statement->fetch();
        $statement->closeCursor();
        return !($row === false);
    }

    public function getCustomerIdByUsername($username) {
        $query = 'SELECT Customer_id FROM Customers WHERE Username = :username';
        $statement = $this->db->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->execute();
        $row = $statement->fetch();
        $Customer_id = $row['Customer_id'];
        $statement->closeCursor();
        return $Customer_id;
    }

    public function getClassesDetailsByCustomer($Customer_id) {
        $query = ' SELECT * FROM Classes
                    JOIN Registered_Classes ON Classes.Class_id = Registered_Classes.Class_id
                    JOIN Customers ON Customers.Customer_id = Registered_Classes.Customer_id
                    WHERE Customers.Customer_id = :Customer_id';
        $statement = $this->db->prepare($query);
        $statement->bindValue(':Customer_id', $Customer_id);
        $statement->execute();
        $classes = $statement->fetchAll();
        $statement->closeCursor();
        return $classes;
    }

}

?>