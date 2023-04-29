<?php

class Validator {

    private $db;

    /**
     * Instantiates a new validator
     */
    public function __construct($db) {
        $this->db = $db;
    }

    public function validateUsername($username) {
        $error_username = '';
        if (empty($username)) {
            $error_username = 'Enter a username.';
        } elseif (strlen($username) > 20) {
            $error_username = 'Can have at most 20 characters.';
        } elseif ($this->db->isValidUser($username)) {
            $error_username = 'The username exists already.';
        }
        return $error_username;
    }

    public function validatePassword($password) {
        $error_password = '';
        if (empty($password)) {
            $error_password = 'Enter a password.';
        } elseif (strlen($password) < 8) {
            $error_password = 'Must consist of at least 8 characters';
        } elseif (!preg_match('/[[:digit:]]/', $password) ||
                !preg_match('/[[:lower:]]/', $password) ||
                !preg_match('/[[:upper:]]/', $password)) {
            $error_password = 'Must contain a number, an uppercase and a lowercase letter';
        }
        return $error_password;
    }

    public function validateValue($value) {
        $error_value = '';
        if (empty($value)) {
            $error_value = 'Enter a value.';
        } elseif (strlen($value) > 50) {
            $error_value = 'Cannot exceed 50 characters.';
        }
        return $error_value;
    }

    public function validateAddress($value) {
        $error_value = '';
        if (empty($value)) {
            $error_value = 'Enter a value.';
        } elseif (strlen($value) > 100) {
            $error_value = 'Cannot exceed 100 characters.';
        }
        return $error_value;
    }

    public function validatePostal($value) {
        $error_value = '';
        if (empty($value)) {
            $error_value = 'Enter a value.';
        } elseif (strlen($value) > 20) {
            $error_value = 'Cannot exceed 20 characters.';
        }
        return $error_value;
    }

    public function validateEmail($email) {
        $pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';

        $error_value = '';
        if (empty($email)) {
            $error_value = 'Enter a value.';
        } elseif (strlen($email) > 50) {
            $error_value = 'Cannot exceed 50 characters.';
        } elseif (!preg_match($pattern, $email)) {
            $error_value = 'Invalid email.';
        }

        return $error_value;
    }

}

?>