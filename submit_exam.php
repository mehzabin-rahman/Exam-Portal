<?php
session_start();

// --- 1️⃣ Check if student is logged in ---
if(!isset($_SESSION['studentId'])){
    die("Not logged in!");
}

// --- 2️⃣ Get session and POST data ---
$studentId = $_SESSION['studentId'];
$courseCode = $_SESSION['courseCode'];
$sessionAcad = $_SESSION['session'];

if(!isset($_POST['examNo'], $_POST['totalQuestions'])){
    die("Incomplete exam data!");
}

$examNo = intval($_POST['examNo']);
$totalQuestions = intval($_POST['totalQuestions']);

// --- 3️⃣ Database connection ---
$host = "localhost";
$user = "root";
$pass = "";
$db = "exam_portal";

$conn = new mysqli($host, $user, $pass, $db);
if($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}

// --- 4️⃣ Calculate score ---
$score = 0;
for($i = 1; $i <= $totalQuestions; $i++){
    $ansKey = "ans$i";
    $correctKey = "correct$i";
    if(isset($_POST[$ansKey], $_POST[$correctKey]) && $_POST[$ansKey] === $_POST[$correctKey]){
        $score++;
    }
}

// --- 5️⃣ Save result with error check ---
$stmt = $conn->prepare("INSERT INTO exam_results (studentId, courseCode, session, examNo, score, totalQuestions) VALUES (?, ?, ?, ?, ?, ?)");
if(!$stmt){
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("sssiii", $studentId, $courseCode, $sessionAcad, $examNo, $score, $totalQuestions);

if(!$stmt->execute()){
    die("Failed to save result: " . $stmt->error);
}

$stmt->close();

// --- 6️⃣ Fetch questions for display ---
$stmt2 = $conn->prepare("SELECT question, correctAnswer, questionDomain FROM exams WHERE courseCode=? AND examNo=?");
if(!$stmt2){
    die("Prepare failed: " . $conn->error);
}

$stmt2->bind_param("si", $courseCode, $examNo);
$stmt2->execute();
$result = $stmt2->get_result();
$questions = $result->fetch_all(MYSQLI_ASSOC);
$stmt2->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Exam Result</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
body{font-family:'Segoe UI',sans-serif;background:linear-gradient(135deg,#6a11cb,#2575fc);min-height:100vh;display:flex;justify-content:center;align-items:center;padding:20px;}
.container{background:white;padding:30px;border-radius:15px;box-shadow:0 15px 35px rgba(0,0,0,0.2);width:100%;max-width:900px;}
h1{color:#4a6fc0;text-align:center;margin-bottom:20px;}
.info p{margin:5px 0;font-weight:600;}
table{width:100%;border-collapse:collapse;margin-top:20px;}
th,td{border:1px solid #ddd;padding:10px;text-align:center;}
th{background-color:#4a6fc0;color:white;}
tr:nth-child(even){background-color:#f9f9f9;}
.btn{display:inline-block;margin-top:20px;padding:10px 20px;background:#4a6fc0;color:white;border:none;border-radius:6px;font-size:16px;cursor:pointer;text-decoration:none;}
.btn:hover{background:#3b5aa6;}
</style>
</head>
<body>
<div class="container">
    <h1><i class="fas fa-check-circle"></i> Exam Submitted!</h1>
    
    <!-- Student Info & Score -->
    <div class="info">
        <p><b>Student ID:</b> <?php echo htmlspecialchars($studentId); ?></p>
        <p><b>Session:</b> <?php echo htmlspecialchars($sessionAcad); ?></p>
        <p><b>Course Code:</b> <?php echo htmlspecialchars($courseCode); ?></p>
        <p><b>Exam No:</b> <?php echo $examNo; ?></p>
        <p><b>Score:</b> <?php echo $score; ?> / <?php echo $totalQuestions; ?></p>
    </div>

    <!-- Question Table -->
    <h2 style="text-align:center;margin-top:30px;">Questions & Answers</h2>
    <table>
        <tr>
            <th>#</th>
            <th>Question</th>
            <th>Correct Answer</th>
            <th>Question Domain</th>
        </tr>
        <?php foreach($questions as $index => $q): ?>
        <tr>
            <td><?php echo $index + 1; ?></td>
            <td><?php echo htmlspecialchars($q['question']); ?></td>
            <td><?php echo htmlspecialchars($q['correctAnswer']); ?></td>
            <td><?php echo htmlspecialchars($q['questionDomain']); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <a href="student_logout.php" class="btn">Logout</a>
</div>
</body>
</html>
