

<?php
session_start();

// --- Use your existing teacher session check ---
if(!isset($_SESSION['teacherId'])){
    header("Location: teacher_login.php");
    exit();
}
$teacherId = $_SESSION['teacherId']; // your logged-in teacher ID

// --- Database connection ---
$host = "localhost";
$user = "root";
$pass = "";
$db = "exam_portal"; // change to your database
$conn = new mysqli($host,$user,$pass,$db);
if($conn->connect_error){ die("Connection failed: ".$conn->connect_error); }

// --- Fetch results if search submitted ---
$results = [];
if(isset($_POST['search'])){
    $courseCode = $_POST['courseCode'];
    $session_input = $_POST['session'];
    $examNo = $_POST['examNo'];
    $sql = "SELECT * FROM exam_results WHERE courseCode='$courseCode' AND session='$session_input' AND examNo='$examNo'";
    $res = $conn->query($sql);
    if($res->num_rows>0){
        while($row = $res->fetch_assoc()){
            $results[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Teacher Exam Results</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
<style>
* {margin:0;padding:0;box-sizing:border-box;font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;}
body {background: linear-gradient(135deg,#6a11cb 0%,#2575fc 100%); min-height:100vh; padding:20px;}
.container {max-width:1200px; margin:20px auto; background:white; border-radius:15px; padding:20px; box-shadow:0 15px 35px rgba(0,0,0,0.2);}
.header {background:#4a6fc0; color:white; padding:25px 30px; border-radius:10px 10px 0 0; text-align:center; margin-bottom:20px;}
.header h1 {font-size:28px;}
.form-group {margin-bottom:20px;}
.form-group label{font-weight:600; display:block; margin-bottom:8px;}
.form-control{width:100%; padding:12px; border:1px solid #ddd; border-radius:6px; font-size:16px;}
.form-control:focus{border-color:#4a6fc0; outline:none; box-shadow:0 0 0 3px rgba(74,111,192,0.2);}
.btn{padding:12px 20px;background:#4a6fc0;color:white;border:none;border-radius:6px;cursor:pointer;font-size:16px;}
.btn:hover{background:#3b5aa6;}
.results-table{width:100%; border-collapse:collapse; margin-top:20px;}
.results-table th, .results-table td{border:1px solid #ddd; padding:12px; text-align:left;}
.results-table th{background:#f2f6ff; font-weight:600;}
.results-table tr:nth-child(even){background:#f9f9f9;}
.results-table tr:hover{background:#f1f5ff;}
.logout{float:right;color:white;background:red;padding:5px 10px;border-radius:5px;text-decoration:none;}
</style>
</head>
<body>
<div class="container">
<div class="header">
<h1><i class="fas fa-file-alt"></i> Teacher Exam Results</h1>

</div>

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
<input type="text" name="examNo" class="form-control" required>
</div>
<button type="submit" name="search" class="btn"><i class="fas fa-search"></i> Search Results</button>
</form>

<?php if(!empty($results)): ?>
<table class="results-table" id="resultsTable">
<thead>
<tr>
 <th>Exam No</th>
<th>Student ID</th>
<th>Score</th>
<th>Total Marks</th>
<th>Submission Time</th>
</tr>
</thead>
<tbody>
<?php foreach($results as $row): ?>
<tr>
<td><?php echo $row['examNo']; ?></td>
<td><?php echo $row['studentId']; ?></td>
<td><?php echo $row['score']; ?></td>
<td><?php echo $row['totalQuestions']; ?></td>
<td><?php echo $row['submittedAt']; ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<button class="btn" onclick="downloadPDF()"><i class="fas fa-download"></i> Download PDF</button>
<button class="btn" onclick="window.location.href='teacher_dashboard.php'" style="margin-left:10px;">
    <i class="fas fa-home"></i> Go to Dashboard
</button>

<button class="btn" onclick="window.location.href='teacher_logout.php'" style="margin-left:10px; background:red;">
    <i class="fas fa-sign-out-alt"></i> Logout
</button>

<?php elseif(isset($_POST['search'])): ?>
<p>No results found for this course and session.</p>
<?php endif; ?>
</div>

<script>
function downloadPDF(){
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    doc.text("Exam Results",14,20);

    let rows = [];
    const table = document.getElementById("resultsTable");
    for(let i=1;i<table.rows.length;i++){
        let rowData = [];
        for(let j=0;j<table.rows[i].cells.length;j++){
            rowData.push(table.rows[i].cells[j].innerText);
        }
        rows.push(rowData);
    }
    doc.autoTable({head:[['Exam No','Student ID','Score','Total Marks','Submission Time']],body:rows,startY:30});
    doc.save("Exam_Results.pdf");
}
</script>
</body>
</html>
