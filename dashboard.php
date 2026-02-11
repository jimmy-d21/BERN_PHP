<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "bern_db");
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

$userCount = $conn->query("SELECT COUNT(*) as total FROM users");
$totalUsers = $userCount->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard | Bern System</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>

<div class="dashboard-container">

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <h2>Bern System</h2>
        </div>

        <ul class="menu">
            <li class="active"><a href="#">🏠 Dashboard</a></li>
            <li><a href="#">📂 Categories</a></li>
            <li><a href="#">📦 Products</a></li>
            <li><a href="logout.php" class="logout">🚪 Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">

        <div class="topbar">
            <div class="welcome">
                Welcome 
                <strong>
                    <?php echo ($_SESSION['role'] === "admin") ? "Admin" : "Encoder"; ?>
                </strong>,
                <?php echo htmlspecialchars($_SESSION['username']); ?>
            </div>
        </div>

        <div class="cards">

            <div class="card">
                <h3>Total Users</h3>
                <p><?php echo $totalUsers; ?></p>
            </div>

            <div class="card">
                <h3>Your Role</h3>
                <p><?php echo $_SESSION['role']; ?></p>
            </div>

        </div>

    </div>
</div>

</body>
</html>
