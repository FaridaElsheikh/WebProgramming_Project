<?php

    session_start();
    $sec_username=($_SESSION['username']);
    require_once('config.php');
                
    // Connect to database
    $conn = mysqli_connect($server, $user, $password, $database);

    // Check connection
    if (!$conn) {
        die("Connection failed " . mysqli_connect_error());
    }

    $sql = 'SELECT fname, lname FROM secretary WHERE username =?';
              
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt,'s', $sec_username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $fname, $lname);
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
    <link rel="stylesheet" href="CoursesPage.css">
</head>
<body>

    <div class="header">
        <ul class="flex-header-container">
            <li class="flex-header-item"><img class="header-logo" src="logo.jpg" alt=""></li>
            <li class="flex-header-item"><a href="SecretaryPage.php">Home</a></li>
            <li class="flex-header-item"><a class="active" href="CoursesPage.php">Courses</a></li>
            <li class="flex-header-item"><p><?php echo $fname.' ' .$lname;?></p><img class="header-img" src="profile.jpg" alt=""></li>
            <li class="flex-header-item"><a href="MainPage.php">Logout</a></li>
        </ul>
    </div>
  
    <div class="courses">

        <div class="option-bar">
            <ul class="flex-option-container">
                <li class="flex-option-item"><h1>Courses</h1></li>
                <li class="flex-option-item"><a href="CreateCoursePage.php"><button class="addbtn">Create</button></a></li>
                <li class="flex-option-item"><a href="./material/Project.pdf"><button class="dowbtn"><i class="fa fa-download"></i>Download</button></a></li>
            </ul>
        </div>
        
        <table class="zebra">
            <tr>
                <th>Course Code</th>
                <th>Course Name</th>
                <th>Course Type</th>
                <th>Course Instructor</th>
                <th>Corse Credit</th>
                <th colspan="2">Course Time</th>
                <th></th>
            </tr>

            <?php

                require_once('config.php');

                // Connect to database
                $conn = mysqli_connect($server, $user, $password, $database);

                // Check connection
                if (!$conn) {
                    die("Connection failed " . mysqli_connect_error());
                }

                $sql = "SELECT course_code, course_name, course_type, course_instructor, course_credit, course_day, course_hour FROM courses";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    // Output data of each row
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>" .
                                "<td>" . $row['course_code'] . "</td>" .
                                "<td>" . $row['course_name'] . "</td>" .
                                "<td>" . $row['course_type'] . "</td>" .
                                "<td>" . $row['course_instructor'] . "</td>" .
                                "<td>" . $row['course_credit'] . "</td>" .
                                "<td>" . $row['course_day'] . "</td>" .
                                "<td>" . $row['course_hour'] . "</td>" .
                                '<td class="delete"><button class="delbtn" name="delete" value='.$row['course_code'].'>Delete</button></td>' .
                            "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "No results";
                }
                
                mysqli_close($conn);

            ?>   

        </table>

        <!-- <input type="file" name="cv_file"> --> 

        <br>
        
    </div>

</body>
</html>


    <?php

    require_once('config.php');
    
    if (isset($_POST['delete'])) {

        $c_code = $_POST['delete'];

        // Connect to database
        $conn = mysqli_connect($server, $user, $password, $database);

        // Check connection
        if (!$conn) {
            die("Connection failed " . mysqli_connect_error());
        }
        
        $query = "DELETE FROM courses WHERE course_code = ?"; 
             
        $statement = mysqli_prepare($conn, $query);
        
        mysqli_stmt_bind_param($statement, 'i', $c_code);

        // Execute the prepared statement
        mysqli_stmt_execute($statement);
        print(mysqli_stmt_error($statement) . "\n");

        // Close the statement and the connection
        mysqli_stmt_close($statement);
        
        mysqli_close($conn);
    }

?>


    
