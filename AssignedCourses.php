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
    <link rel="stylesheet" href="AssignedCourses.css">
</head>
<body>
    <div class="header">
        <ul class="flex-header-container">
            <li class="flex-header-item"><img class="header-logo" src="logo.jpg" alt=""></li>
            <li class="flex-header-item"><a  href="InstructorPage.php">Home</a></li>
            <li class="flex-header-item"><a class="active" href="AssignedCourses.php">Courses</a></li>
            <li class="flex-header-item"><a href="InstructorResearchGroup.php">Research Groups</a></li>
            <li class="flex-header-item"><p><?php echo $fname.' ' .$lname;?></p><img class="header-img" src="./profile.jpg" alt=""></li>
            <li class="flex-header-item"><a  href="MainPage.php">Logout</a></li>
        </ul>
    </div>
 <div class="courses">
    <h1>Assigned Courses</h1>
    <br>
    <br>

    <table class="zebra">
        
        <tr>
           <th>Course Type</th>
           <th>Course Code</th>
           <th>Course Name</th>
           <th>Number of Registered Students</th>
           <th>Registered Student List</th>
           <th>Class Materials</th>
        </tr>
        <?php
                require_once('config.php');
                
                // Connect to database
                $conn = mysqli_connect($server, $user, $password, $database);

                // Check connection
                if (!$conn) {
                    die("Connection failed " . mysqli_connect_error());
                }

                $sql = 'SELECT course_code , course_name, course_type FROM courses  WHERE course_instructor =?';
                //$stmt = mysqli_query($conn, $sql);

                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt,'s', $i_username);
                mysqli_stmt_execute($stmt);
                print(mysqli_stmt_error($stmt) . "\n");
                $result=mysqli_stmt_get_result($stmt);

                $sql1='SELECT count(st_username) FROM takes  WHERE code =?';
                $stmt1 = mysqli_prepare($conn, $sql1);

                if (mysqli_num_rows($result) > 0) {
                    // Output data of each row
                    while($row = mysqli_fetch_assoc($result)) {
                                             
                        
                        mysqli_stmt_bind_param($stmt1,'i', $row['course_code']);
                        mysqli_stmt_execute($stmt1);
                        mysqli_stmt_bind_result($stmt1, $count);
                        mysqli_stmt_fetch($stmt1);
                        print(mysqli_stmt_error($stmt1) . "\n");


                        echo "<tr>" .
                                "<td>" . $row['course_type'] . "</td>" .
                                "<td>" . $row['course_code'] . "</td>" .
                                "<td>" . $row['course_name'] . "</td>" .
                                "<td>".$count."</td>".
                                '<td><a href="./material/Project.pdf"><button class="btn">Download </button></a></td>'.
                                '<td><input class="file" type="file" webkitdirectory="" directory=""></td>'.
                            "</tr>";
                    }
                    echo "</table>";
                }
                mysqli_close($conn);
                echo "</table>";
            ?>

        <!-- <tr>
           <td>Elective</td>
           <td>COE536</td>
           <td>Pogramming for Engineers</td>
           <td>130</td>
           <td><a href="./material/Project.pdf"><button class="btn">Download </button></a></td>
           <td><input class="file" type="file" webkitdirectory="" directory=""></td>
           
        </tr>
        <tr>
            <td>Elective</td>
            <td>COE526</td>
            <td>Databases</td>
            <td>60</td>
            <td><a href="./material/Project.pdf"><button class="btn">Download </button></a></td>
            <td><input class="file" type="file" webkitdirectory="" directory=""></td>
           
         </tr>
         <tr>
            <td>Mandatory</td>
            <td>COE516</td>
            <td>Algorithm Analysis</td>
            <td>80</td>
            <td><a href="./material/Project.pdf"><button class="btn">Download </button></a></td>
            <td><input class="file" type="file" webkitdirectory="" directory=""></td>
         </tr> -->
    </table>
</div>
       
     </table>
    
</body>
</html>