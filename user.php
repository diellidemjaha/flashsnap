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
                return $user['id'];
            }
        }

        return false;
    }

    public function signup($username, $email, $password, $profilePic, $createdAt) {
        $connection = $this->db->getConnection();

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $destination = $_FILES['profile_pic']['name'];
        move_uploaded_file($profilePic, $destination);

        $query = "INSERT INTO users (username, email, password, profile_pic, created_at) VALUES ('$username', '$email', '$hashedPassword', '$destination', '$createdAt')";
        mysqli_query($connection, $query);

        return mysqli_insert_id($connection);
    }
}

?>