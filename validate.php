<!-- validate.php -->
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

class Validator {
    private $db;
    private $user;
    private $errors = array();


    public function __construct($db, $user) {
        $this->db = $db;
        $this->user = $user;
    }

    public function getErrors() {
        return $this->errors;
    }

    public function addError($error) {
        $this->errors[] = $error;
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

        if (!$this->db->getUser()->emailExists($email)) {
            return true;
        } else {
            $this->addError("Email already registered.");
            return false;
        }
        // ... (Your existing validation code)
    
        // Validate the profile picture
        if (empty($profilePic) || $_FILES['profile_pic']['error'] !== UPLOAD_ERR_OK) {
            return false;
        }
    
        // return true;
    
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