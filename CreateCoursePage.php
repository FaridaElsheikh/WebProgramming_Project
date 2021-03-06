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


<?php

    $error='';
    require_once('config.php');

    if (isset($_POST['create'])) {
        $c_code = $_POST['c_code']; // coursecode = c_code
        $c_name = $_POST['c_name'];
        $c_type = $_POST['c_type'];
        $c_instructor = $_POST['c_instructor'];
        $c_credit = $_POST['c_credit'];
        $c_day = $_POST['c_day'];
        $c_hour = $_POST['c_hour'];

        // Connect to database
        $conn = mysqli_connect($server, $user, $password, $database);

        // Check connection
        if (!$conn) {
            die("Connection failed " . mysqli_connect_error());
        }
        // Prepare an insert statement
        $query = "Select course_code FROM courses WHERE course_day=? and course_hour=?";        
        $statement = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($statement, 'ss', $c_day, $c_hour); // coursecode
        // Execute the prepared statement
        mysqli_stmt_execute($statement);
        

        $result=mysqli_stmt_get_result($statement);
        print(mysqli_stmt_error($statement) . "\n");
        if (mysqli_num_rows($result) == 0) {
            // Connect to database
             $conn = mysqli_connect($server, $user, $password, $database);
            // Prepare an insert statement
            $query1 = "INSERT INTO courses (course_code, course_name, course_type, course_instructor, course_credit, course_day, course_hour, s_username) VALUES (?,?,?,?,?,?,?,?)";        
            $statement1 = mysqli_prepare($conn, $query1);
            mysqli_stmt_bind_param($statement1, 'isssisss', $c_code, $c_name, $c_type, $c_instructor, $c_credit, $c_day, $c_hour, $sec_username); // coursecode

            // Execute the prepared statement
            mysqli_stmt_execute($statement1);
            print(mysqli_stmt_error($statement1) . "\n");
        }
        else{
            $error='There is another course at that time';
        }

        

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
            <li class="flex-header-item"><a  href="SecretaryPage.php">Home</a></li>
            <li class="flex-header-item"><a class="active" href="CoursesPage.php">Courses</a></li>
            <li class="flex-header-item"><div class="dropdown">
                <a href=""><?php echo $fname.' ' .$lname;?></a>
                <div class="dropdown-content">
                    <a href="MainPage.php">Logout</a>
                </div>
            </div></li>
        </ul>
    </div>
    

    <div class="create">
        <h1>Create a Course</h1>

        <form action="CreateCoursePage.php" method="post">

       <h3>Course Information</h3>

       <p id="code">
           Code: <br>
           <input type="text" name="c_code" required> 
       </p>

       <p id="name">
           Name: <br>
           <input type="text" name="c_name" required>
       </p>
 
       <p id="type">
           Type: <br>
           <div id="opt">
            <input class="opt" type="radio" name="c_type" value="Mandatory" required>Mandatory<br> 
            <input class="opt" type="radio" name="c_type" value="Elective" required>Elective<br>
           </div>
       </p>

       <p id="inst">
                    Instructor: <br>
                    <select name="c_instructor" required>
                        <?php
                            require_once('config.php');

                            // Connect to database
                            $conn = mysqli_connect($server, $user, $password, $database);

                            // Check connection
                            if (!$conn) {
                                die("Connection failed " . mysqli_connect_error());
                            }

                            $sql = "SELECT fname, lname, username FROM instructor";
                            $result = mysqli_query($conn, $sql);

                            if (mysqli_num_rows($result) > 0) {
                            // Output data of each row
                            while($row = mysqli_fetch_assoc($result)) {
                                echo 
                                    "<option  value='".$row['username'] ."' >" . $row['fname'] ." ". $row['lname']. "</option>" ;                               
                            }
                            }
                            mysqli_close($conn);
                        ?>
                        </select>
            </p>

       <p id="cred">
            Credit: <br>
               <select name="c_credit" required>
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
           <select name="c_day" required>
               <option>Monday</option>
               <option>Tuesday</option>
               <option>Wednesday</option>
               <option>Thursday</option>
               <option>Friday</option>
               <option>Saturday</option>
           </select>

           <input type="time" name="c_hour" required>
       </p>

       <input id="sendbtn" type="submit" name="create" value="Create"> 
       <div style="color: red;">
							<?php echo $error; ?>
		</div>

    </div>

</body>
</html>