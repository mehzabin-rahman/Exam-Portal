<?php
session_start();
if(!isset($_SESSION['teacherId'])){
    header("Location: teacher_login.php");
    exit();
}
$teacherId = $_SESSION['teacherId'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Teacher Dashboard</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
body{font-family:'Segoe UI',sans-serif;background:linear-gradient(135deg,#6a11cb,#2575fc);min-height:100vh;display:flex;justify-content:center;align-items:center;}
.container{background:white;padding:40px;border-radius:15px;box-shadow:0 15px 35px rgba(0,0,0,0.2);text-align:center;width:90%;max-width:800px;}
h1{color:#4a6fc0;margin-bottom:30px;}
.cards{display:flex;justify-content:center;gap:30px;flex-wrap:wrap;}
.card{background:#f2f6ff;padding:30px;border-radius:12px;box-shadow:0 10px 25px rgba(0,0,0,0.1);width:250px;cursor:pointer;transition:0.3s;}
.card:hover{transform:translateY(-5px);box-shadow:0 15px 35px rgba(0,0,0,0.2);}
.card h2{color:#4a6fc0;margin-bottom:15px;}
.card i{font-size:50px;color:#4a6fc0;}
.card a{display:block;margin-top:20px;text-decoration:none;color:white;background:#4a6fc0;padding:12px;border-radius:8px;}
.card a:hover{background:#3b5aa6;}
</style>
</head>
<body>
<div class="container">
    <h1>Welcome, <?php echo htmlspecialchars($teacherId); ?>!</h1>
    <div class="cards">
        <div class="card">
            <i class="fas fa-pencil-alt"></i>
            <h2>Create New Exam</h2>
            <a href="choosequestion.html">Go</a>
        </div>
        <div class="card">
            <i class="fas fa-chart-bar"></i>
            <h2>View Results</h2>
            <a href="fetch_result_teacher.php">Go</a>
        </div>
    </div>
</div>
</body>
</html>
