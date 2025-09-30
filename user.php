<?php
// User.php
class User {
    private $conn;
    private $table_name = "users";
    private $profile_table = "user_profiles";

    public $id;
    public $username;
    public $email;
    public $password;
    public $last_login;
    public $is_active;
    public $created_at;
    public $updated_at;

    // Profile fields
    public $first_name;
    public $last_name;
    public $middle_name;
    public $date_of_birth;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Check if user exists by email or username
    public function userExists() {
        $query = "SELECT id, username, email, password, is_active 
                  FROM " . $this->table_name . " 
                  WHERE username = :username OR email = :email 
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":email", $this->email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            return $row;
        }
        return false;
    }

    // Login user
    public function login() {
        $user = $this->userExists();
        
        if ($user && password_verify($this->password, $user['password'])) {
            if (!$user['is_active']) {
                return "account_inactive";
            }

            // Update last login
            $this->updateLastLogin($user['id']);
            
            // Get user profile data
            $profile = $this->getUserProfile($user['id']);
            
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['logged_in'] = true;
            
            // Set profile data in session if exists
            if ($profile) {
                $_SESSION['first_name'] = $profile['first_name'];
                $_SESSION['last_name'] = $profile['last_name'];
                $_SESSION['full_name'] = $profile['first_name'] . ' ' . $profile['last_name'];
            }

            return "success";
        }
        return "invalid_credentials";
    }

    // Get user profile data
    public function getUserProfile($user_id) {
        $query = "SELECT first_name, last_name, middle_name, date_of_birth 
                  FROM " . $this->profile_table . " 
                  WHERE user_id = :user_id 
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetch();
        }
        return false;
    }

    // Update last login timestamp
    private function updateLastLogin($user_id) {
        $query = "UPDATE " . $this->table_name . " 
                  SET last_login = CURRENT_TIMESTAMP 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $user_id);
        $stmt->execute();
    }

    // Register new user
    public function register() {
        // Check if user already exists
        if ($this->userExists()) {
            return "user_exists";
        }

        // Start transaction
        $this->conn->beginTransaction();

        try {
            // Insert into users table
            $query = "INSERT INTO " . $this->table_name . " 
                      SET username=:username, email=:email, password=:password";

            $stmt = $this->conn->prepare($query);

            // Hash password
            $hashed_password = password_hash($this->password, PASSWORD_DEFAULT);

            $stmt->bindParam(":username", $this->username);
            $stmt->bindParam(":email", $this->email);
            $stmt->bindParam(":password", $hashed_password);
            $stmt->execute();

            // Get the last inserted user ID
            $user_id = $this->conn->lastInsertId();

            // Insert into user_profiles table
            $query = "INSERT INTO " . $this->profile_table . " 
                      SET user_id=:user_id, first_name=:first_name, last_name=:last_name";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":user_id", $user_id);
            $stmt->bindParam(":first_name", $this->first_name);
            $stmt->bindParam(":last_name", $this->last_name);
            $stmt->execute();

            // Commit transaction
            $this->conn->commit();
            return "success";

        } catch (Exception $e) {
            // Rollback transaction on error
            $this->conn->rollBack();
            error_log("Registration error: " . $e->getMessage());
            return "error";
        }
    }

    // Update user profile
    public function updateProfile($user_id) {
        $query = "INSERT INTO " . $this->profile_table . " 
                  (user_id, first_name, last_name, middle_name, date_of_birth) 
                  VALUES (:user_id, :first_name, :last_name, :middle_name, :date_of_birth)
                  ON DUPLICATE KEY UPDATE 
                  first_name = :first_name, 
                  last_name = :last_name, 
                  middle_name = :middle_name, 
                  date_of_birth = :date_of_birth";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":user_id", $user_id);
        $stmt->bindParam(":first_name", $this->first_name);
        $stmt->bindParam(":last_name", $this->last_name);
        $stmt->bindParam(":middle_name", $this->middle_name);
        $stmt->bindParam(":date_of_birth", $this->date_of_birth);

        if ($stmt->execute()) {
            // Update session data
            $_SESSION['first_name'] = $this->first_name;
            $_SESSION['last_name'] = $this->last_name;
            $_SESSION['full_name'] = $this->first_name . ' ' . $this->last_name;
            
            return "success";
        }
        return "error";
    }

    // Get complete user data with profile
    public function getUserWithProfile($user_id) {
        $query = "SELECT u.*, up.first_name, up.last_name, up.middle_name, up.date_of_birth
                  FROM " . $this->table_name . " u
                  LEFT JOIN " . $this->profile_table . " up ON u.id = up.user_id
                  WHERE u.id = :user_id 
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetch();
        }
        return false;
    }
}
?>