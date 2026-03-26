<?php
session_start();
if(!isset($_SESSION['studentId'])){
    echo "Not logged in!";
    exit();
}

if(!isset($_SESSION['studentId'])){
    header("Location: student_login.php");
    exit();
}


if(!isset($_SESSION['studentId'])){
    echo "Not logged in!";
    exit();
}

$studentId = $_SESSION['studentId'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['courseCode'] = $_POST['courseCode'];
    $_SESSION['session'] = $_POST['session'];
    $_SESSION['examNo'] = $_POST['examNo'];
    header("Location: take_exam.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Fetch Your Exam</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
* {margin:0;padding:0;box-sizing:border-box;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;}
body{background:linear-gradient(135deg,#6a11cb 0%,#2575fc 100%);min-height:100vh;display:flex;justify-content:center;align-items:center;padding:20px;}
.container{width:100%;max-width:600px;background:white;border-radius:15px;box-shadow:0 15px 35px rgba(0,0,0,0.2);overflow:hidden;padding:30px;}
.header{background:#4a6fc0;color:white;padding:25px 30px;text-align:center;margin:-30px -30px 20px -30px;border-top-left-radius:15px;border-top-right-radius:15px;}
.header h1{font-size:28px;margin-bottom:10px;}
.form-group{margin-bottom:20px;}
.form-group label{display:block;margin-bottom:8px;font-weight:600;color:#333;}
.form-control{width:100%;padding:12px 15px;border:1px solid #ddd;border-radius:6px;font-size:16px;}
.form-control:focus{border-color:#4a6fc0;outline:none;box-shadow:0 0 0 3px rgba(74,111,192,0.2);}
.btn{display:block;width:100%;padding:14px;background:#4a6fc0;color:white;border:none;border-radius:6px;font-size:18px;cursor:pointer;}
.btn:hover{background:#3b5aa6;}
</style>
</head>
<body>
<div class="container">
<div class="header"><h1><i class="fas fa-user-graduate"></i> Fetch Your Exam</h1></div>
<form method="POST">
    <div class="form-group">
        <label>Course Code</label>
        <input type="text" name="courseCode" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Session</label>
        <input type="text" name="session" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Exam No</label>
        <input type="number" name="examNo" class="form-control" required>
    </div>
    <button type="submit" class="btn">Start Exam</button>
</form>
</div>
</body>
</html>
