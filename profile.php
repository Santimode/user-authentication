<?php
// profile.php
include 'config.php';
include 'User.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

$message = "";

// Get current user data
$user_data = $user->getUserWithProfile($_SESSION['user_id']);

// Update profile
if ($_POST && isset($_POST['update_profile'])) {
    $user->first_name = trim($_POST['first_name']);
    $user->last_name = trim($_POST['last_name']);
    $user->middle_name = trim($_POST['middle_name']);
    $user->date_of_birth = trim($_POST['date_of_birth']);

    $result = $user->updateProfile($_SESSION['user_id']);
    
    if ($result === "success") {
        $message = '<div class="alert alert-success">Profile updated successfully!</div>';
        // Refresh user data
        $user_data = $user->getUserWithProfile($_SESSION['user_id']);
    } else {
        $message = '<div class="alert alert-danger">Failed to update profile. Please try again.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Modern System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .sidebar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            min-height: 100vh;
        }
        .sidebar .nav-link {
            color: white;
            padding: 12px 20px;
            margin: 5px 0;
            border-radius: 8px;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: rgba(255,255,255,0.1);
        }
        .main-content {
            background-color: #f8f9fa;
        }
        .profile-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar p-0">
                <div class="p-4">
                    <h4><i class="bi bi-shield-lock"></i> Dashboard</h4>
                </div>
                <nav class="nav flex-column p-3">
                    <a class="nav-link" href="dashboard.php">
                        <i class="bi bi-speedometer2 me-2"></i> Overview
                    </a>
                    <a class="nav-link active" href="profile.php">
                        <i class="bi bi-person me-2"></i> Profile
                    </a>
                    <a class="nav-link" href="#">
                        <i class="bi bi-gear me-2"></i> Settings
                    </a>
                    <a class="nav-link" href="logout.php">
                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                    </a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <nav class="navbar navbar-light bg-white border-bottom">
                    <div class="container-fluid">
                        <span class="navbar-brand">Profile Management</span>
                        <div class="d-flex">
                            <span class="navbar-text me-3">
                                Welcome, <?php echo htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['username']); ?>!
                            </span>
                        </div>
                    </div>
                </nav>

                <div class="container-fluid mt-4">
                    <div class="row">
                        <div class="col-12">
                            <?php echo $message; ?>
                            
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0"><i class="bi bi-person-gear me-2"></i>Edit Profile</h5>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="first_name" class="form-label">First Name *</label>
                                                    <input type="text" class="form-control" id="first_name" name="first_name" 
                                                           value="<?php echo htmlspecialchars($user_data['first_name'] ?? ''); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="middle_name" class="form-label">Middle Name</label>
                                                    <input type="text" class="form-control" id="middle_name" name="middle_name" 
                                                           value="<?php echo htmlspecialchars($user_data['middle_name'] ?? ''); ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="last_name" class="form-label">Last Name *</label>
                                                    <input type="text" class="form-control" id="last_name" name="last_name" 
                                                           value="<?php echo htmlspecialchars($user_data['last_name'] ?? ''); ?>" required>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="username" class="form-label">Username</label>
                                                    <input type="text" class="form-control" id="username" 
                                                           value="<?php echo htmlspecialchars($user_data['username'] ?? ''); ?>" disabled>
                                                    <div class="form-text">Username cannot be changed.</div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="date_of_birth" class="form-label">Date of Birth</label>
                                                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" 
                                                           value="<?php echo htmlspecialchars($user_data['date_of_birth'] ?? ''); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" 
                                                   value="<?php echo htmlspecialchars($user_data['email'] ?? ''); ?>" disabled>
                                            <div class="form-text">Email cannot be changed.</div>
                                        </div>
                                        
                                        <button type="submit" name="update_profile" class="btn btn-primary">
                                            <i class="bi bi-check-circle me-2"></i>Update Profile
                                        </button>
                                        <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>