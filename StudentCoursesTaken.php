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
    <link rel="stylesheet" href="./StudentCourses.css">
    <link rel="stylesheet" href="./PopupForm.css">
    <script src="./ButtonFunctions.js"></script>

</head>
<body>
    <div class="header">
        <ul class="flex-header-container">
            <li class="flex-header-item"><img class="header-logo" src="./logo.jpg" alt=""></li>
            <li class="flex-header-item"><a href="./StudentPage.php">Home</a></li>
            <li class="flex-header-item"><div class="dropdown">
                <a a class="active" href="">Courses</a>
                <div class="dropdown-content">
                    <a href="./StudentCoursesOffered.php">Offered Courses</a>
                    <a a class="active" href="./StudentCoursesTaken.php">Taken Courses</a>
                </div>
            </div></li>
            
            <li class="flex-header-item"><a href="./StudentResearchGroup.php">Research Groups</a></li>
            <li class="flex-header-item"><p><?php echo $fname.' ' .$lname;?></p><img class="header-img" src="./profile.jpg" alt=""></li>
            <li class="flex-header-item"><a href="./MainPage.php">Logout</a></li>
        </ul>
    </div>
   
    <div class="content">

        <div class="option-bar">
            <ul class="flex-option-container">
                <li class="flex-option-item"><h1>Taken Courses</h1></li>
                <li class="flex-option-item"> <button class="btn" onclick="openForm()">Add</button></li>
                <li class="flex-option-item"><a href="./material/Project.pdf"><button class="btn">Download</button></a></li>
            </ul>
        </div>
    
        <div class="form-popup" id="myForm">
            <form class="form-container" method="POST">
              <h1>Add Course</h1>
              <hr>
              <label action="StudentCoursesTaken.php" method="post" for="course_opt"><b>Course</b></label>
              <select name="course_opt"  required>
                <?php
                    require_once('config.php');

                    // Connect to database
                    $conn = mysqli_connect($server, $user, $password, $database);

                    // Check connection
                    if (!$conn) {
                        die("Connection failed " . mysqli_connect_error());
                    }

                    $sql = "SELECT course_name FROM courses";
                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        // Output data of each row
                        while($row = mysqli_fetch_assoc($result)) {
                            echo 
                                    "<option>" . $row['course_name'] . "</option>" ;
                        }
                    } else {
                        echo "No results";
                    }
                    mysqli_close($conn);
                ?>
              </select>
              <br><br>
              <button type="submit" class="btn" name='add'>Add</button>
              <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
            </form>
          </div>
        <?php ?>
        <div class="table">
        <form  method="POST">
            <table class="courses-table">
                <tr>
                    <th></th>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Material</th>
                </tr>
                <?php
                require_once('config.php');
                
                // Connect to database
                $conn = mysqli_connect($server, $user, $password, $database);

                // Check connection
                if (!$conn) {
                    die("Connection failed " . mysqli_connect_error());
                }

                $sql = 'SELECT code , course_name, course_type FROM takes , courses  WHERE code=course_code  AND st_username =?';
                //$stmt = mysqli_query($conn, $sql);


                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt,'s', $st_username);
                mysqli_stmt_execute($stmt);
                print(mysqli_stmt_error($stmt) . "\n");
                $result=mysqli_stmt_get_result($stmt);


                if (mysqli_num_rows($result) > 0) {
                    // Output data of each row
                    while($row = mysqli_fetch_assoc($result)) {
                        $code=$row['code'];
                        echo "<tr>" .
                                '<td class="delete"  ><button class="btn" name="delete" value='.$row['code'].' >Delete</button></td>' .
                                "<td>" . $row['code'] . "</td>" .
                                "<td>" . $row['course_name'] . "</td>" .
                                "<td>" . $row['course_type'] . "</td>" .
                                '<td class="download"><a href="./material/Project.pdf"><button class="btn">Download</button></a></td>'.
                            "</tr>";
                    }
                    echo "</table>";
                }
                mysqli_close($conn);
                echo "</table>";
            ?>
            </table>
        </form>
        </div>


    </div>
</body>
</html>


<?php
    require_once('config.php');

    if (isset($_POST['add'])) {
        $course_opt = $_POST['course_opt'];

        // Connect to database
        $conn = mysqli_connect($server, $user, $password, $database);

        // Check connection
        if (!$conn) {
            die("Connection failed " . mysqli_connect_error());
        }
        
        $sql = 'SELECT course_code  FROM  courses WHERE course_name=? ';
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt,'s', $course_opt);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $code);
        mysqli_stmt_fetch($stmt);
        print(mysqli_stmt_error($stmt) . "\n");

        // Connect to database
        $conn = mysqli_connect($server, $user, $password, $database); 
            // Prepare an insert statement
        $query = "INSERT INTO takes (st_username,code) VALUES (?,?)"; 
             
        $statement = mysqli_prepare($conn, $query);
        
        mysqli_stmt_bind_param($statement, 'si', $st_username, $code);

        // Execute the prepared statement
        mysqli_stmt_execute($statement);
        print(mysqli_stmt_error($statement) . "\n");

        // Close the statement and the connection
        mysqli_stmt_close($statement);
        
        mysqli_close($conn);
    }
    
    if (isset($_POST['delete'])) {
        $code = $_POST['delete'];

        // Connect to database
        $conn = mysqli_connect($server, $user, $password, $database);

        // Check connection
        if (!$conn) {
            die("Connection failed " . mysqli_connect_error());
        }
        
        $query = "DELETE FROM takes WHERE code = ?"; 
             
        $statement = mysqli_prepare($conn, $query);
        
        mysqli_stmt_bind_param($statement, 'i', $code);

        // Execute the prepared statement
        mysqli_stmt_execute($statement);
        print(mysqli_stmt_error($statement) . "\n");

        // Close the statement and the connection
        mysqli_stmt_close($statement);
        
        mysqli_close($conn);
    }

?>
