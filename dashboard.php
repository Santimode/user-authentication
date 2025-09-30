<?php
// dashboard.php
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Modern System</title>
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
                    <a class="nav-link active" href="#">
                        <i class="bi bi-speedometer2 me-2"></i> Overview
                    </a>
                    <a class="nav-link" href="#">
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
                        <span class="navbar-brand">Welcome, <?php echo htmlspecialchars($username); ?>!</span>
                        <div class="d-flex">
                            <span class="navbar-text me-3">
                                <i class="bi bi-calendar me-1"></i> <?php echo date('F j, Y'); ?>
                            </span>
                        </div>
                    </div>
                </nav>

                <div class="container-fluid mt-4">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Welcome to Your Dashboard</h5>
                                    <p class="card-text">You have successfully logged into the system.</p>
<div class="alert alert-success">
    <i class="bi bi-check-circle-fill me-2"></i>
    Login successful! Welcome back, 
    <?php 
    if (isset($_SESSION['full_name'])) {
        echo htmlspecialchars($_SESSION['full_name']);
    } else {
        echo htmlspecialchars($_SESSION['username']);
    }
    ?>!
</div>
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