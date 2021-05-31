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
          <li class="flex-header-item"><a href="InstructorPage.php">Home</a></li>
          <li class="flex-header-item"><a href="AssignedCourses.php">Courses</a></li>
          <li class="flex-header-item"><a class="active" href="InstructorResearchGroup.php">Research Groups</a></li>
          <li class="flex-header-item"><div class="dropdown">
                <a href=""><?php echo $fname.' ' .$lname;?></a>
                <div class="dropdown-content">
                    <a href="MainPage.php">Logout</a>
                </div>
            </div></li>
      </ul>
  </div>

  <div class="courses">

    <h2>Research Project</h2>

    <h6>Research Group Members</h6>
    <table class="zebra">
       <tr>
          <th>Student ID</th>
          <th>First Name</th>
          <th>Last Name</th>
          <th>GPA</th>
          <th>Class</th>
          <th>Courses Taken</th>
       </tr>
       <?php
                require_once('config.php');
                
                // Connect to database
                $conn = mysqli_connect($server, $user, $password, $database);

                // Check connection
                if (!$conn) {
                    die("Connection failed " . mysqli_connect_error());
                }

                //get the research group id
                $sql = 'SELECT id FROM research_group  WHERE i_username=?';
                //$stmt = mysqli_query($conn, $sql);
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt,'s', $i_username);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $id);
                mysqli_stmt_fetch($stmt);
                

                $conn = mysqli_connect($server, $user, $password, $database);
                //get every student username in the research group
                $sql2= "SELECT st_username FROM joins WHERE group_id=? and approval='yes'";
                //$stmt = mysqli_query($conn, $sql);
                $stmt2 = mysqli_prepare($conn, $sql2);
                mysqli_stmt_bind_param($stmt2,'i',$id);
                mysqli_stmt_execute($stmt2);
                $students=mysqli_stmt_get_result($stmt2);

                $conn = mysqli_connect($server, $user, $password, $database);
                //get student data
                $sql = 'SELECT st_id , fname, lname,gpa,class FROM student  WHERE username=?';
                //$stmt = mysqli_query($conn, $sql);
                $stmt = mysqli_prepare($conn, $sql);


                while ($row = mysqli_fetch_array($students))
                {
                    $st= $row['st_username'] ;

                        mysqli_stmt_bind_param($stmt,'s', $st);
                        mysqli_stmt_execute($stmt);
                        print(mysqli_stmt_error($stmt) . "\n");
                        $result=mysqli_stmt_get_result($stmt);
                        // Output data of each row
                        while($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>" .
                                    "<td>" . $row['st_id'] . "</td>" .
                                    "<td>" . $row['fname'] . "</td>" .
                                    "<td>" . $row['lname'] . "</td>" .
                                    "<td>" . $row['gpa'] . "</td>" .
                                    "<td>" . $row['class'] . "</td>" .
                                    "<td>courses</td>" .
                                "</tr>";
                        }
                        
                   
                }
                
                
                mysqli_close($conn);
                echo "</table>";
            ?>
   </table>
   <h6>Research Group Requests</h6>
   <form method='POST'>
        <table class="zebra">
        
        <tr>
            <th>Student ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>GPA</th>
            <th>Class</th>
            <th>Courses Taken</th>
            <th colspan="2">Respond</th>
        </tr>
         <?php
            require_once('config.php');
            
            // Connect to database
            $conn = mysqli_connect($server, $user, $password, $database);

            // Check connection
            if (!$conn) {
                die("Connection failed " . mysqli_connect_error());
            }

            //get the research group id
            $sql = 'SELECT id FROM research_group  WHERE i_username=?';
            //$stmt = mysqli_query($conn, $sql);
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt,'s', $i_username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $id);
            mysqli_stmt_fetch($stmt);
            

            $conn = mysqli_connect($server, $user, $password, $database);
            //get every student username in the research group
            $sql2= "SELECT st_username FROM joins WHERE group_id=? and approval=''";
            //$stmt = mysqli_query($conn, $sql);
            $stmt2 = mysqli_prepare($conn, $sql2);
            mysqli_stmt_bind_param($stmt2,'i',$id);
            mysqli_stmt_execute($stmt2);
            $students=mysqli_stmt_get_result($stmt2);

            $conn = mysqli_connect($server, $user, $password, $database);
            //get student data
            $sql = 'SELECT st_id , fname, lname,gpa,class FROM student  WHERE username=?';
            //$stmt = mysqli_query($conn, $sql);
            $stmt = mysqli_prepare($conn, $sql);


            while ($row = mysqli_fetch_array($students))
            {
                $st= $row['st_username'] ;

                    mysqli_stmt_bind_param($stmt,'s', $st);
                    mysqli_stmt_execute($stmt);
                    print(mysqli_stmt_error($stmt) . "\n");
                    $result=mysqli_stmt_get_result($stmt);
                    $st_courses='';
                    
                    $sql2 = 'SELECT course_name FROM takes,courses  WHERE st_username=? and code=course_code';
                    $stmt2 = mysqli_prepare($conn, $sql2);
                    mysqli_stmt_bind_param($stmt2,'s', $st);
                    mysqli_stmt_execute($stmt2);
                    print(mysqli_stmt_error($stmt2) . "\n");
                    $result2=mysqli_stmt_get_result($stmt2);
                    $total=mysqli_num_rows($result2);
                    $i=0;
                    while($row = mysqli_fetch_assoc($result2)){
                        if(++$i==$total ){
                            $st_courses.=$row['course_name'];
                        }
                        else{
                            $st_courses.=$row['course_name'].',';
                        }
                                    
                    }
                    // Output data of each row
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>" .
                                "<td>" . $row['st_id'] . "</td>" .
                                "<td>" . $row['fname'] . "</td>" .
                                "<td>" . $row['lname'] . "</td>" .
                                "<td>" . $row['gpa'] . "</td>" .
                                "<td>" . $row['class'] . "</td>" .
                                "<td>".$st_courses."</td>" .
                                '<td ><button class="btn" name="approve" value='.$st.' >Approve</button></td>' .
                                '<td ><button class="btn" name="reject" value='.$st.' >Reject</button></td>' .
                            "</tr>";
                    }
                    
               
            }
            mysqli_close($conn);
            echo "</table>";
        ?>
    </table>



   </form>

</div>
</table>

</body>
</html>


<?php
    require_once('config.php');

    if (isset($_POST['approve'])) {
        $value = $_POST['approve'];

        // Connect to database
        $conn = mysqli_connect($server, $user, $password, $database);

        // Check connection
        if (!$conn) {
            die("Connection failed " . mysqli_connect_error());
        }

            // Prepare an insert statement
        $query = "UPDATE joins
        SET approval='yes'
        WHERE st_username=? and group_id=? "; 
             
        $statement = mysqli_prepare($conn, $query);
        
        mysqli_stmt_bind_param($statement, 'si', $value, $id);

        // Execute the prepared statement
        mysqli_stmt_execute($statement);
        print(mysqli_stmt_error($statement) . "\n");

        // Close the statement and the connection
        mysqli_stmt_close($statement);
        
        mysqli_close($conn);
    }
    
    if (isset($_POST['reject'])) {
        $value = $_POST['reject'];


        // Connect to database
        $conn = mysqli_connect($server, $user, $password, $database);

        // Check connection
        if (!$conn) {
            die("Connection failed " . mysqli_connect_error());
        }

            // Prepare an insert statement
        $query = "UPDATE joins
        SET approval='no'
        WHERE st_username=? and group_id=? "; 
             
        $statement = mysqli_prepare($conn, $query);
        
        mysqli_stmt_bind_param($statement, 'si', $value, $id);

        // Execute the prepared statement
        mysqli_stmt_execute($statement);
        print(mysqli_stmt_error($statement) . "\n");

        // Close the statement and the connection
        mysqli_stmt_close($statement);
        
        mysqli_close($conn);
    }

?>
