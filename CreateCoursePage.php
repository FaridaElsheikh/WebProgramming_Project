<?php

    require_once('connect.php');

    if (isset($_POST['create'])) {
        $c_code = $_POST['c_code']; // coursecode = c_code
        $c_name = $_POST['c_name'];
        $c_type = $_POST['c_type'];
        $c_instructor = $_POST['c_instructor'];
        $c_credit = $_POST['c_credit'];
        $c_time = $_POST['c_time'];

        // Connect to database
        $conn = mysqli_connect($server, $user, $password, $database);

        // Check connection
        if (!$conn) {
            die("Connection failed " . mysqli_connect_error());
        }

        // Prepare an insert statement
        $query = "INSERT INTO courses (course_code, course_name, course_type, course_instructor, course_credit, course_time) VALUES (?,?,?,?,?,?)";        
        $statement = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($statement, 'ssssss', $c_code, $c_name, $c_type, $c_instructor, $c_time);

        // Execute the prepared statement
        mysqli_stmt_execute($statement);
        print(mysqli_stmt_error($statement) . "\n");

        echo "Course created successfully <br>";
        echo "Cours Code:" . $c_code . "<br>";
        echo "Course Name:" . $c_name . "<br>";
        echo "Course Type:" . $c_type . "<br>";
        echo "Course Instructor:" . $c_instructor . "<br>";
        echo "Course Day:" . $c_day . "<br>";
        echo "Course Hour:" . $c_hour . "<br>";

        // Close the statement and the connection
        mysqli_stmt_close($statement);
        mysqli_close($conn);
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MEBIS</title>
    <link rel="stylesheet" href="Header-Background.css">
    <link rel="stylesheet" href="CreateCoursePage.css">
</head>
<body>

    <div class="header">
        <ul class="flex-header-container">
            <li class="flex-header-item"><img class="header-logo" src="logo.jpg" alt=""></li>
            <li class="flex-header-item"><a href="SecretaryPage.php">Home</a></li>
            <li class="flex-header-item"><a class="active" href="CoursesPage.php">Courses</a></li>
            <li class="flex-header-item"><p>Tugce Keskin</p><img class="header-img" src="profile.jpg" alt=""></li>
            <li class="flex-header-item"><a href="MainPage.php">Logout</a></li>
        </ul>
    </div>
    

    <div class="create">
        <h1>Create a Course</h1>

        <form action="CreateCoursePage.php" method="post">

       <h3>Course Information</h3>

       <p id="code">
           Code: <br>
           <input type="text" name="c_code"> 
       </p>

       <p id="name">
           Name: <br>
           <input type="text" name="c_name">
       </p>
 
       <p id="type">
           Type: <br>
           <div id="opt">
            <input class="opt" type="radio" name="c_type" value="man">Mandatory<br>
            <input class="opt" type="radio" name="c_type" value="elec">Elective<br>
           </div>
       </p>

       <p id="inst">
            Instructor: <br>
               <select name="c_instructor">
                   <option>Hasan Fehmi</option>
                   <option>Reda</option>
                   <option>Kemal</option>
               </select>
       </p>

       <p id="cred">
            Credit: <br>
               <select name="c_credit">
                   <option>1</option>
                   <option>2</option>
                   <option>3</option>
                   <option>4</option>
                   <option>5</option>
                   <option>6</option>
                   <option>7</option>
                   <option>8</option>
               </select>
       </p>

       <p id="time">
           Time: <br>
            <input type="time" name="c_time">
       </p>

       <input id="sendbtn" type="submit" name="create" value="Create"> 

    </div>

</body>
</html>