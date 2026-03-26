<?php
session_start();
$host = "localhost";
$user = "root";
$pass = "";
$db = "exam_portal";
$conn = new mysqli($host,$user,$pass,$db);
if($conn->connect_error) die("Connection failed: ".$conn->connect_error);

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $studentId = $_POST['studentId'];
    $name = $_POST['name'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // NEW

    $stmt = $conn->prepare("INSERT INTO students (studentId, name, password) VALUES (?,?,?)"); // NEW
    $stmt->bind_param("sss", $studentId, $name, $password); // NEW
    if($stmt->execute()){
        echo "<h3 style='color:green;text-align:center;'>Registration successful!</h3>";
        echo "<div style='text-align:center;'><a href='student_login.php' class='btn'>Login</a></div>";
        exit();
    } else {
        $error = $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Registration</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
* {margin:0;padding:0;box-sizing:border-box;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;}
body{background:linear-gradient(135deg,#6a11cb,#2575fc);min-height:100vh;display:flex;justify-content:center;align-items:center;padding:20px;}
.container{width:100%;max-width:500px;background:white;border-radius:15px;box-shadow:0 15px 35px rgba(0,0,0,0.2);overflow:hidden;padding:30px;}
h1{text-align:center;color:#4a6fc0;margin-bottom:20px;}
.form-group{margin-bottom:20px;}
.form-group label{display:block;margin-bottom:8px;font-weight:600;color:#333;}
.form-control{width:100%;padding:12px 15px;border:1px solid #ddd;border-radius:6px;font-size:16px;}
.form-control:focus{border-color:#4a6fc0;outline:none;box-shadow:0 0 0 3px rgba(74,111,192,0.2);}
.btn{display:block;width:100%;padding:14px;background:#4a6fc0;color:white;border:none;border-radius:6px;font-size:18px;cursor:pointer;text-align:center;text-decoration:none;}
.btn:hover{background:#3b5aa6;}
.error{color:red;text-align:center;margin-bottom:15px;}
</style>
</head>
<body>
<div class="container">
<h1><i class="fas fa-user-plus"></i> Register as Student</h1>
<?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
<form method="POST">
    <div class="form-group">
        <label>Student ID</label>
        <input type="text" name="studentId" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Name</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    <button type="submit" class="btn">Register</button>
</form>
</div>
</body>
</html>
