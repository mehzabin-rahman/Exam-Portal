

<?php
session_start();
if(!isset($_SESSION['teacherId'])){
    header("Location: teacher_login.php");
    exit();
}
$teacherId = $_SESSION['teacherId'];

$host = "localhost";
$user = "root";
$pass = "";
$db = "exam_portal";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $courseCode = $_POST['courseCode'];
    $session = $_POST['session'];
    $studentCount = $_POST['studentCount'];
    $duration = $_POST['duration'];

    $_SESSION['courseCode'] = $courseCode;
    $_SESSION['session'] = $session;
    $_SESSION['studentCount'] = $studentCount;
    $_SESSION['duration'] = $duration;

    header("Location: questions.php");
    exit();
}
?>
