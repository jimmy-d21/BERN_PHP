<?php
$conn = new mysqli("localhost", "root", "", "bern_db");
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

if (isset($_POST['register'])) {

    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];
    $birthdate = $_POST['birthdate'];
    $role = $_POST['role'];

    if (empty($username) || empty($password) || empty($confirm) || empty($birthdate) || empty($role)) {
        $error = "All fields are required!";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match!";
    } else {

        $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $check->bind_param("s", $username);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "Username already exists!";
        } else {

            $hashed = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users (username, password, role, created_at) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $hashed, $role, $birthdate);

            if ($stmt->execute()) {
                header("Location: index.php");
                exit();
            } else {
                $error = "Registration failed!";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register | Bern System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="card">
    <h1>Create Account</h1>

    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Username">
        <input type="password" name="password" placeholder="Password">
        <input type="password" name="confirm_password" placeholder="Confirm Password">
        <input type="date" name="birthdate">

        <select name="role">
            <option value="encoder">Encoder</option>
            <option value="admin">Admin</option>
        </select>

        <button type="submit" name="register">Register</button>
    </form>

    <p class="link">Already have account? <a href="index.php">Login</a></p>
</div>

</body>
</html>
