<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>JSTU Exam Portal</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;}
body{
    background:linear-gradient(135deg,#6a11cb,#2575fc);
    min-height:100vh;
    display:flex;
    flex-direction:column;
    align-items:center;
    padding:30px;
}
.header{
    width:100%;
    max-width:1000px;
    background:white;
    border-radius:15px;
    box-shadow:0 10px 25px rgba(0,0,0,0.2);
    text-align:center;
    padding:30px 20px;
    margin-bottom:30px;
}
.header img{
    width:90px;
    height:auto;
    display:block;
    margin:0 auto 15px auto;
}
.header h1{
    color:#333;
    font-size:28px;
    margin-bottom:5px;
}
.sections{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:30px;
    width:100%;
    max-width:1000px;
}
.section{
    background:white;
    border-radius:15px;
    box-shadow:0 10px 25px rgba(0,0,0,0.2);
    padding:25px;
}
.section h2{
    color:#4a6fc0;
    text-align:center;
    margin-bottom:20px;
}
.cards{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:20px;
}
.card{
    background:#f2f6ff;
    border-radius:10px;
    padding:20px;
    text-align:center;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
    transition:transform 0.2s ease, box-shadow 0.2s ease;
}
.card:hover{
    transform:translateY(-5px);
    box-shadow:0 10px 20px rgba(0,0,0,0.2);
}
.card i{
    font-size:40px;
    color:#4a6fc0;
    margin-bottom:15px;
}
.card a{
    display:inline-block;
    text-decoration:none;
    color:white;
    background:#4a6fc0;
    padding:10px 20px;
    border-radius:6px;
    font-size:16px;
    margin-top:10px;
}
.card a:hover{background:#3b5aa6;}
</style>
</head>
<body>

<div class="header">
    <img src="jstu.png" alt="JSTU Logo">
    <h1>Jamalpur Science & Technology University</h1>
    <h2>Exam Portal</h2>
</div>

<div class="sections">

    <!-- Student Section -->
    <div class="section">
        <h2>👨‍🎓 Student</h2>
        <div class="cards">
            <div class="card">
                <i class="fas fa-user-plus"></i>
                <h3>Register</h3>
                <a href="register_student.php">Go</a>
            </div>
            <div class="card">
                <i class="fas fa-sign-in-alt"></i>
                <h3>Login</h3>
                <a href="student_login.php">Go</a>
            </div>
        </div>
    </div>

    <!-- Teacher Section -->
    <div class="section">
        <h2>👨‍🏫 Teacher</h2>
        <div class="cards">
            <div class="card">
                <i class="fas fa-user-plus"></i>
                <h3>Register</h3>
                <a href="teacher_register.php">Go</a>
            </div>
            <div class="card">
                <i class="fas fa-sign-in-alt"></i>
                <h3>Login</h3>
                <a href="teacher_login.php">Go</a>
            </div>
        </div>
    </div>

</div>

</body>
</html>
