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
        if (empty($username) || empty($email) || empty($password) || empty($createdAt)) {
            return false;
        }
    
        // Validate the profile picture
        if (empty($profilePic) || $_FILES['profile_pic']['error'] !== UPLOAD_ERR_OK) {
            return false;
        }
    
        // Perform additional validation if needed
    
        return true;
    }

    public function validateProfileUpdate($newUsername, $newPassword, $newProfilePic) {
        // Add your validation logic here
        // Make sure to return true or false based on the validation result
        // You can validate the new username, password, and profile picture as per your requirements

        // Example validation code
        if (empty($newUsername) || empty($newPassword) || empty($newProfilePic)) {
            return false;
        }

        return true;
    }
}

?>