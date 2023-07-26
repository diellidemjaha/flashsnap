<?php

class User {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function login($email, $password) {
        $connection = $this->db->getConnection();

        $query = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($connection, $query);

        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            if (password_verify($password, $user['password'])) {
                return $user;
            }
        }

        return false;
    }

    public function signup($username, $email, $password, $profilePic, $createdAt) {
        $connection = $this->db->getConnection();
    
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
        $query = "INSERT INTO users (username, email, password, profile_pic, created_at) VALUES ('$username', '$email', '$hashedPassword', '$profilePic', '$createdAt')";
        $result = mysqli_query($connection, $query);
    
        if (!$result) {
            throw new Exception("Error creating user: " . mysqli_error($connection));
        } else {
            return mysqli_insert_id($connection); // Return the new user ID
        }
    }

    // public function signup($username, $email, $password, $profilePic, $createdAt) {
    //     $connection = $this->db->getConnection();
    
    //     $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    //     $query = "INSERT INTO users (username, email, password, profile_pic, created_at) VALUES (?, ?, ?, ?, ?)";
    
    //     $statement = $connection->prepare($query);
    //     $statement->bind_param("sssss", $username, $email, $hashedPassword, $profilePic, $createdAt);
        
    //     if ($statement->execute()) {
    //         $userID = $statement->insert_id;
    //         $statement->close();
    //         return $userID;
    //     } else {
    //         $statement->close();
    //         return false;
    //     }
    // }
    
    // Updating Profile functions
    public function updateUsername($userID, $newUsername) {
        $connection = $this->db->getConnection();

        $query = "UPDATE users SET username = '$newUsername' WHERE id = '$userID'";
        mysqli_query($connection, $query);
    }

    public function updatePassword($userID, $newPassword) {
        $connection = $this->db->getConnection();

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $query = "UPDATE users SET password = '$hashedPassword' WHERE id = '$userID'";
        mysqli_query($connection, $query);
    }

    public function updateProfilePic($userID, $profilePicFilename) {
        $connection = $this->db->getConnection();

        $extension = pathinfo($newProfilePic, PATHINFO_EXTENSION);
        $newFilename = $userID . '_' . time() . '.' . $extension;
        $destination = 'gallery/' . $newFilename;
        move_uploaded_file($_FILES['new_profile_pic']['tmp_name'], $destination);
        
        $query = "UPDATE users SET profile_pic = '$profilePicFilename' WHERE id = '$userID'";
        mysqli_query($connection, $query);
    }

    public function getProfilePicFilename($userID) {
        $connection = $this->db->getConnection();

        $query = "SELECT profile_pic FROM users WHERE id = '$userID'";
        $result = mysqli_query($connection, $query);

        if (!$result) {
            die("Query failed: " . mysqli_error($connection));
        }

        $userData = mysqli_fetch_assoc($result);
        return $userData['profile_pic'];
    }

    public function emailExists($email) {
        $connection = $this->db->getConnection();

        $query = "SELECT id FROM users WHERE email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

}

?>