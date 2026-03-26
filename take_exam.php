<?php
session_start();
if(!isset($_SESSION['studentId'])){
    header("Location: student_login.php");
    exit();
}

// Fetch session values sent from fetch_exam.php
$studentId = $_SESSION['studentId'];
$courseCode = $_POST['courseCode'] ?? $_SESSION['courseCode'];
$sessionAcad = $_POST['session'] ?? $_SESSION['session'];
$examNo = $_POST['examNo'] ?? $_SESSION['examNo'];

// Save to session for later use
$_SESSION['courseCode'] = $courseCode;
$_SESSION['session'] = $sessionAcad;
$_SESSION['examNo'] = $examNo;

$host = "localhost";
$user = "root";
$pass = "";
$db = "exam_portal";
$conn = new mysqli($host,$user,$pass,$db);
if($conn->connect_error) die("Connection failed: ".$conn->connect_error);

// Fetch questions for this exam
$sql = "SELECT question, optionA, optionB, optionC, optionD, correctAnswer, questionDomain, duration
        FROM exams 
        WHERE courseCode=? AND session=? AND examNo=?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssi",$courseCode,$sessionAcad,$examNo);
$stmt->execute();
$result = $stmt->get_result();

// Store questions in array
$questions = [];
while($row = $result->fetch_assoc()){
    $questions[] = $row;
}

// Shuffle using studentId as seed
mt_srand(crc32($studentId)); // ensures same order per student
shuffle($questions);
$totalQuestions = count($questions);

// Fetch exam duration (assuming all questions have same duration)
$examDuration = $questions[0]['duration'] ?? 10; // default 10 mins
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Take Exam</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
/* same styles as before */
* {margin:0;padding:0;box-sizing:border-box;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;}
body{background:linear-gradient(135deg,#6a11cb,#2575fc);min-height:100vh;display:flex;justify-content:center;align-items:flex-start;padding:20px;}
.container{width:100%;max-width:900px;background:white;border-radius:15px;box-shadow:0 15px 35px rgba(0,0,0,0.2);overflow:hidden;padding:30px;margin-top:20px;}
.header{background:#4a6fc0;color:white;padding:25px 30px;text-align:center;margin:-30px -30px 20px -30px;border-top-left-radius:15px;border-top-right-radius:15px;}
.header h1{font-size:28px;margin-bottom:10px;}
form{margin-top:20px;}
.questions-table{width:100%;border-collapse:collapse;}
.questions-table th,.questions-table td{border:1px solid #ddd;padding:12px;text-align:left;}
.questions-table th{background-color:#f2f6ff;font-weight:600;}
.questions-table tr:nth-child(even){background-color:#f9f9f9;}
.questions-table tr:hover{background-color:#f1f5ff;}
.btn{display:block;padding:14px 20px;background:#4a6fc0;color:white;border:none;border-radius:6px;font-size:18px;cursor:pointer;margin-top:15px;}
.btn:hover{background:#3b5aa6;}
.timer{font-size:20px;color:#4a6fc0;margin-bottom:15px;text-align:right;}
</style>
<script>
// Timer in minutes
let timeLeft = <?php echo $examDuration * 60; ?>;
function startTimer() {
    let timerDisplay = document.getElementById("timer");
    let timer = setInterval(function(){
        let minutes = Math.floor(timeLeft / 60);
        let seconds = timeLeft % 60;
        timerDisplay.textContent = `Time Left: ${minutes}m ${seconds}s`;
        timeLeft--;
        if(timeLeft < 0){
            clearInterval(timer);
            alert("Time is up! Submitting your exam.");
            document.getElementById("examForm").submit();
        }
    },1000);
}
window.onload = startTimer;
</script>
</head>
<body>
<div class="container">
<div class="header"><h1><i class="fas fa-pencil-alt"></i> Exam: <?php echo htmlspecialchars($courseCode." - ".$examNo); ?></h1></div>
<div class="timer" id="timer"></div>
<form method="POST" action="submit_exam.php" id="examForm">
<input type="hidden" name="totalQuestions" value="<?php echo $totalQuestions; ?>">
<input type="hidden" name="courseCode" value="<?php echo htmlspecialchars($courseCode); ?>">
<input type="hidden" name="session" value="<?php echo htmlspecialchars($sessionAcad); ?>">
<input type="hidden" name="examNo" value="<?php echo htmlspecialchars($examNo); ?>">

<?php foreach($questions as $index => $q): ?>
<div class="form-group">
    <p><strong>Q<?php echo $index+1; ?>:</strong> <?php echo htmlspecialchars($q['question']); ?></p>
    <input type="hidden" name="correct<?php echo $index+1; ?>" value="<?php echo htmlspecialchars($q['correctAnswer']); ?>">
    <input type="hidden" name="domain<?php echo $index+1; ?>" value="<?php echo htmlspecialchars($q['questionDomain']); ?>">
    <label><input type="radio" name="ans<?php echo $index+1; ?>" value="A" required> A. <?php echo htmlspecialchars($q['optionA']); ?></label><br>
    <label><input type="radio" name="ans<?php echo $index+1; ?>" value="B"> B. <?php echo htmlspecialchars($q['optionB']); ?></label><br>
    <label><input type="radio" name="ans<?php echo $index+1; ?>" value="C"> C. <?php echo htmlspecialchars($q['optionC']); ?></label><br>
    <label><input type="radio" name="ans<?php echo $index+1; ?>" value="D"> D. <?php echo htmlspecialchars($q['optionD']); ?></label>
</div>
<hr>
<?php endforeach; ?>
<button type="submit" class="btn">Submit Exam</button>
</form>
</div>
</body>
</html>
