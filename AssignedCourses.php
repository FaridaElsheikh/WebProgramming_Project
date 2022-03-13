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
            <li class="flex-header-item"><div class="dropdown">
                <a href=""><?php echo $fname.' ' .$lname;?></a>
                <div class="dropdown-content">
                    <a href="MainPage.php">Logout</a>
                </div>
            </div></li>
        </ul>
    </div>
 <div class="courses">
    <h1>Assigned Courses</h1>
    <br>
    <br>

    <table class="zebra">
        
        <tr>
           <th> Code</th>
           <th> Name</th>
           <th> Type</th>
           <th>Number of Registered Students</th>
           
           <th colspan="2">Time</th>
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

                $sql = 'SELECT course_code , course_name, course_type,course_day,course_hour FROM courses  WHERE course_instructor =?';
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
                                "<td>" . $row['course_code'] . "</td>" .
                                "<td>" . $row['course_name'] . "</td>" .
                                "<td>" . $row['course_type'] . "</td>" .
                                "<td>".$count."</td>".
                                "<td>" . $row['course_day'] . "</td>" .
                                "<td>" . $row['course_hour'] . "</td>" .
                                '<form  method="POST"><td><button class="btn" name="download">Download </button></td></form>'.
                                '<td><input class="file" type="file" webkitdirectory="" directory=""></td>'.
                            "</tr>";
                    }
                    echo "</table>";
                }
                mysqli_close($conn);
                echo "</table>";
            ?>

    </table>
</div>
       
     </table>
    
</body>
</html>

<?php 

if (isset($_POST['download'])) {
    $d=',';
    
    // Connect to database
    $conn = mysqli_connect($server, $user, $password, $database);

    // Check connection
    if (!$conn) {
        die("Connection failed " . mysqli_connect_error());
    }
    
    $sql = 'SELECT fname,lname,class,gpa FROM takes ,student,courses WHERE  course_instructor=? AND course_code=code AND st_username=username';
            //$stmt = mysqli_query($conn, $sql);


    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt,'s', $i_username);
    mysqli_stmt_execute($stmt);
    print(mysqli_stmt_error($stmt) . "\n");
    $result=mysqli_stmt_get_result($stmt);

    

    if(mysqli_num_rows($result)>0){
        $f=fopen('significant.csv','w');
        $fields=array('fname','lname','class','gpa');
        fputcsv($f,$fields,$d);
        
        while($row=mysqli_fetch_assoc($result)){
            
            $linedata=array($row["fname"],$row["lname"],$row["class"],$row["gpa"]);
            fputcsv($f,$linedata,$d);
        }
        fseek($f,0);
        
    }


    // Initialize a file URL to the variable
    $url = './significant.csv';
    
    header('Location: ./significant.csv');
            
    // Close the statement and the connection
    mysqli_stmt_close($stmt);
    
    mysqli_close($conn);
}

?>
