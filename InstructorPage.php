<?php
    session_start();
    $i_username=($_SESSION['username']);
    require_once('config.php');
                
    // Connect to database
    $conn = mysqli_connect($server, $user, $password, $database);

    // Check connection
    if (!$conn) {
        die("Connection failed " . mysqli_connect_error());
    }

    $sql = 'SELECT fname,lname,research_area FROM instructor WHERE username =?';

                
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt,'s', $i_username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $fname,$lname,$area);
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
    <link rel="stylesheet" href="Header-Background.css">
    <link rel="stylesheet" href="InstructorPage.css">

</head>
<body>
    <div class="header">
        <ul class="flex-header-container">
            <li class="flex-header-item"><img class="header-logo" src="logo.jpg" alt=""></li>
            <li class="flex-header-item"><a class="active"  href="InstructorPage.php">Home</a></li>
            <li class="flex-header-item"><a href="AssignedCourses.php">Courses</a></li>
            <li class="flex-header-item"><a href="InstructorResearchGroup.php">Research Groups</a></li>
            <li class="flex-header-item"><p><?php echo $fname.' ' .$lname;?></p><img class="header-img" src="./profile.jpg" alt=""></li>
            <li class="flex-header-item"><a href="MainPage.php">Logout</a></li>
        </ul>
    </div>
    
    <div class="card">
        <img src="instructor.png" alt="na">
        <h1><?php echo $fname.' ' .$lname;?></h1>
        <p class="title">Teaching Assistant</p>
        <p>School of Engineering and Natural Sciences</p>
        <a href="#"><i class="fa fa-linkedin"></i></a> 
    </div>


</body>
</html>
  
