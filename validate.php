<!-- validate.php -->
<?php

class Validator {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function validateLogin($email, $password) {
        if (empty($email) || empty($password)) {
            return false;
        }

        // Perform additional validation if needed

        return true;
    }

    public function validateSignup($username, $email, $password, $profilePic, $createdAt) {
        if (empty($username) || empty($email) || empty($password) || empty($profilePic) || empty($createdAt)) {
            return false;
        }

        // Perform additional validation if needed

        return true;
    }
}

?>