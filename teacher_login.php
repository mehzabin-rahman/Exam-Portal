<?php
session_start();
$host = "localhost";
$user = "root";
$pass = "";
$db = "exam_portal";
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $password = $_POST['password'];

    // fetch teacher by ID
    $sql = "SELECT * FROM teachers WHERE teacherId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id); // teacherId is INT
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // verify hashed password
        if (password_verify($password, $row['password'])) {
            $_SESSION['teacherId'] = $row['teacherId'];
            $_SESSION['teacherName'] = $row['name'];
            header("Location: teacher_dashboard.php");
            exit();
        } else {
            $error = "Invalid ID or password.";
        }
    } else {
        $error = "Invalid ID or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Teacher Login</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
body{background:linear-gradient(135deg,#6a11cb,#2575fc);min-height:100vh;display:flex;justify-content:center;align-items:center;font-family:'Segoe UI',sans-serif;}
.container{background:#fff;padding:30px;border-radius:15px;box-shadow:0 10px 25px rgba(0,0,0,0.2);width:350px;text-align:center;}
.container h2{margin-bottom:20px;color:#4a6fc0;}
.input-group{margin-bottom:15px;text-align:left;}
.input-group label{display:block;margin-bottom:5px;color:#333;}
.input-group input{width:100%;padding:10px;border:1px solid #ccc;border-radius:6px;}
.btn{width:100%;padding:12px;background:#4a6fc0;color:white;border:none;border-radius:6px;font-size:16px;cursor:pointer;margin-top:10px;}
.btn:hover{background:#3b5aa6;}
.error{color:red;margin-top:10px;}
</style>
</head>
<body>
<div class="container">
    <h2><i class="fas fa-chalkboard-teacher"></i> Teacher Login</h2>
    <form method="POST">
        <div class="input-group">
            <label>ID</label>
            <input type="text" name="id" required>
        </div>
        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit" class="btn">Login</button>
    </form>
    <?php if($error) echo "<p class='error'>$error</p>"; ?>
</div>
</body>
</html>
