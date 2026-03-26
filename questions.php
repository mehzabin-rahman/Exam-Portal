<?php
session_start();
if(!isset($_SESSION['teacherId'])){
    header("Location: teacher_login.php");
    exit();
}
$teacherId = $_SESSION['teacherId'];
$courseCode = $_SESSION['courseCode'];
$session = $_SESSION['session'];
$studentCount = $_SESSION['studentCount'];
$duration = $_SESSION['duration'];

$host = "localhost";
$user = "root";
$pass = "";
$db = "exam_portal";
$conn = new mysqli($host,$user,$pass,$db);
if($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Handle exam save
if(isset($_POST['submit'])) {
    $examNo = $_POST['examNo'];
    $qCount = $_POST['questionCount'];

    for($i=1;$i<=$qCount;$i++){
        $q = $_POST["q$i"];
        $a = $_POST["a$i"];
        $b = $_POST["b$i"];
        $c = $_POST["c$i"];
        $d = $_POST["d$i"];
        $correct = $_POST["correct$i"];
        $domain = $_POST["domain$i"];

        $stmt = $conn->prepare("INSERT INTO exams (courseCode, session, examNo, question, optionA, optionB, optionC, optionD, correctAnswer,duration, questionDomain) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param("ssissssssss", $courseCode, $session, $examNo, $q, $a, $b, $c, $d, $correct, $duration, $domain);
        $stmt->execute();
    }

    // Submission confirmation
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Exam Saved</title>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css'>
    <style>
    body{font-family:'Segoe UI',sans-serif;background:linear-gradient(135deg,#6a11cb,#2575fc);min-height:100vh;display:flex;justify-content:center;align-items:center;}
    .container{background:white;padding:50px;border-radius:15px;box-shadow:0 15px 35px rgba(0,0,0,0.2);text-align:center;max-width:600px;width:90%;}
    h2{color:#4a6fc0;margin-bottom:30px;}
    .btn{display:inline-block;margin:10px;padding:14px 25px;background:#4a6fc0;color:white;border:none;border-radius:6px;font-size:18px;text-decoration:none;cursor:pointer;}
    .btn:hover{background:#3b5aa6;}
    </style>
    </head>
    <body>
    <div class='container'>
        <h2><i class='fas fa-check-circle'></i> Exam saved successfully!</h2>
        <a href='choosequestion.html' class='btn'>Create Another Exam</a>
        <a href='teacher_logout.php' class='btn'>Logout</a>
    </div>
    </body>
    </html>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Create Exam Questions</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
* {margin:0;padding:0;box-sizing:border-box;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;}
body{background:linear-gradient(135deg,#6a11cb 0%,#2575fc 100%);min-height:100vh;display:flex;justify-content:center;align-items:center;padding:20px;}
.container{width:100%;max-width:1200px;background:white;border-radius:15px;box-shadow:0 15px 35px rgba(0,0,0,0.2);overflow:hidden;}
.header{background:#4a6fc0;color:white;padding:25px 30px;text-align:center;}
.header h1{font-size:28px;margin-bottom:10px;}
.form-container{padding:30px;}
.form-group{margin-bottom:20px;}
.form-group label{display:block;margin-bottom:8px;font-weight:600;color:#333;}
.form-control{width:100%;padding:12px 15px;border:1px solid #ddd;border-radius:6px;font-size:16px;}
.form-control:focus{border-color:#4a6fc0;outline:none;box-shadow:0 0 0 3px rgba(74,111,192,0.2);}
.btn{display:block;padding:14px 20px;background:#4a6fc0;color:white;border:none;border-radius:6px;font-size:18px;cursor:pointer;margin:10px 0;text-align:center;text-decoration:none;}
.btn:hover{background:#3b5aa6;}
.questions-table{width:100%;border-collapse:collapse;margin-top:20px;}
.questions-table th,.questions-table td{border:1px solid #ddd;padding:12px;text-align:left;}
.questions-table th{background-color:#f2f6ff;font-weight:600;}
.questions-table tr:nth-child(even){background-color:#f9f9f9;}
.questions-table tr:hover{background-color:#f1f5ff;}
</style>
</head>
<body>
<div class="container">
<div class="header"><h1><i class="fas fa-question-circle"></i> Create Exam Questions</h1></div>
<div class="form-container">
<form method="POST">
    <div class="form-group">
        <label>Exam No</label>
        <input type="number" name="examNo" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Number of Questions</label>
        <input type="number" name="questionCount" class="form-control" required>
    </div>
    <button type="submit" name="generate" class="btn">Generate Questions</button>
</form>

<?php
if(isset($_POST['generate'])){
    $qCount = $_POST['questionCount'];
    $examNo = $_POST['examNo'];
    echo "<form method='POST'>";
    echo "<input type='hidden' name='examNo' value='$examNo'>";
    echo "<input type='hidden' name='questionCount' value='$qCount'>";
    echo "<table class='questions-table'>";
    echo "<tr><th>#</th><th>Question</th><th>Option A</th><th>Option B</th><th>Option C</th><th>Option D</th><th>Correct Answer</th><th>Domain</th></tr>";
    for($i=1;$i<=$qCount;$i++){
        echo "<tr>
            <td>$i</td>
            <td><input type='text' name='q$i' class='form-control' required></td>
            <td><input type='text' name='a$i' class='form-control' required></td>
            <td><input type='text' name='b$i' class='form-control' required></td>
            <td><input type='text' name='c$i' class='form-control' required></td>
            <td><input type='text' name='d$i' class='form-control' required></td>
            <td><input type='text' name='correct$i' class='form-control' required></td>
            <td><input type='text' name='domain$i' class='form-control' required></td>
        </tr>";
    }
    echo "</table><br><button type='submit' name='submit' class='btn'>Save Exam</button></form>";
}
?>
</div>
</div>
</body>
</html>
