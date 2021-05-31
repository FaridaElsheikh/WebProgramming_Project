<?php
    session_start();
    $st_username=($_SESSION['username']);
    require_once('config.php');
                
    // Connect to database
    $conn = mysqli_connect($server, $user, $password, $database);

    // Check connection
    if (!$conn) {
        die("Connection failed " . mysqli_connect_error());
    }

    $sql = 'SELECT fname,lname,st_id,gpa,class FROM student WHERE username =?';

                
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt,'s', $st_username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $fname,$lname,$st_id,$gpa,$class);
    mysqli_stmt_fetch($stmt);

                
    print(mysqli_stmt_error($stmt) . "\n");

    // Close the statement and the connection
    mysqli_stmt_close($stmt);
        
    mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MEBIS</title>
    <link rel="stylesheet" href="./Header-Background.css">
    <link rel="stylesheet" href="./StudentProfile.css">
    
</head>
<body>
    <div class="header">
        <ul class="flex-header-container">
            <li class="flex-header-item"><img class="header-logo" src="./logo.jpg" alt=""></li>
            <li class="flex-header-item"><a class="active" href="./StudentPage.html">Home</a></li>
            <li class="flex-header-item"><div class="dropdown">
                <a href="">Courses</a>
                <div class="dropdown-content">
                    <a href="./StudentCoursesOffered.php">Offered Courses</a>
                    <a href="./StudentCoursesTaken.php">Taken Courses</a>
                </div>
            </div></li>
            
            <li class="flex-header-item"><a href="./StudentResearchGroup.php">Research Groups</a></li>
            <li class="flex-header-item"><div class="dropdown">
                <a href=""><?php echo $fname.' ' .$lname;?></a>
                <div class="dropdown-content">
                    <a href="MainPage.php">Logout</a>
                </div>
            </div></li>
        </ul>
    </div>

    <div class="card">
        <img src="./student.jpg">
        <h1><?php echo $fname.' ' .$lname;?></h1>
        <p class="title"><?php echo $class;?> Year</p>
        <p>School of Engineering and Natural Sciences</p>
        <p>GPA: <?php echo $gpa;?> </p> 
    </div>
</body>
</html>