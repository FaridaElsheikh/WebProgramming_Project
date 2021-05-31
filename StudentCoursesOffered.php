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
    <link rel="stylesheet" href="./StudentCourses.css">
    <link rel="stylesheet" href="./Header-Background.css">
</head>
<body>
    <div class="header">
        <ul class="flex-header-container">
            <li class="flex-header-item"><img class="header-logo" src="./logo.jpg" alt=""></li>
            <li class="flex-header-item"><a href="./StudentPage.php">Home</a></li>
            <li class="flex-header-item"><div class="dropdown">
                <a a class="active" href="">Courses</a>
                <div class="dropdown-content">
                    <a a class="active" href="./StudentCoursesOffered.php">Offered Courses</a>
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
    <div class="content">

        <h1 class="offered" >Offered Courses</h1>
        <div class="table">
            <table class="courses-table">
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Type</th>
                </tr>
                <?php
                    require_once('config.php');

                    // Connect to database
                    $conn = mysqli_connect($server, $user, $password, $database);

                    // Check connection
                    if (!$conn) {
                        die("Connection failed " . mysqli_connect_error());
                    }

                    $sql = "SELECT course_code , course_name, course_type FROM courses";
                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        // Output data of each row
                        while($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>" .
                                    "<td>" . $row['course_code'] . "</td>" .
                                    "<td>" . $row['course_name'] . "</td>" .
                                    "<td>" . $row['course_type'] . "</td>" .
                                "</tr>";
                        }
                        echo "</table>";
                    }                
                    mysqli_close($conn);
            ?>
            </table>
        </div>

    </div>
</body>
</html>