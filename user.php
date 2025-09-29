<?php
// User.php
class User {
    private $conn;
    private $table_name = "users";

    public $id;
    public $username;
    public $email;
    public $password;
    public $last_login;
    public $is_active;
    public $created_at;
    public $updated_at;

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
            
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['logged_in'] = true;

            return "success";
        }
        return "invalid_credentials";
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

        $query = "INSERT INTO " . $this->table_name . " 
                  SET username=:username, email=:email, password=:password";

        $stmt = $this->conn->prepare($query);

        // Hash password
        $hashed_password = password_hash($this->password, PASSWORD_DEFAULT);

        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $hashed_password);

        if ($stmt->execute()) {
            return "success";
        }
        return "error";
    }
}
?>