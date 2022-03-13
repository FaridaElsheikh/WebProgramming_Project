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

    $sql = 'SELECT fname,lname,gpa,class FROM student WHERE username =?';

                
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt,'s', $st_username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $fname,$lname,$gpa,$class);
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
    <link rel="stylesheet" href="./StudentResearchGroup.css">
    <link rel="stylesheet" href="./PopupForm.css">
    <script src="./ButtonFunctions.js"></script>
</head>
<body>
    <div class="header">
        <ul class="flex-header-container">
            <li class="flex-header-item"><img class="header-logo" src="./logo.jpg" alt=""></li>
            <li class="flex-header-item"><a href="./StudentPage.php">Home</a></li>
            <li class="flex-header-item"><div class="dropdown">
                <a href="">Courses</a>
                <div class="dropdown-content">
                    <a href="./StudentCoursesOffered.php">Offered Courses</a>
                    <a href="./StudentCoursesTaken.php">Taken Courses</a>
                </div>
            </div></li>
            
            <li class="flex-header-item"><a  class="active" href="./StudentResearchGroup.php">Research Groups</a></li>
            <li class="flex-header-item"><div class="dropdown">
                <a href=""><?php echo $fname.' ' .$lname;?></a>
                <div class="dropdown-content">
                    <a href="MainPage.php">Logout</a>
                </div>
            </div></li>
        </ul>
    </div>

    <div class="content">

        <div class="option-bar">
            <ul class="flex-option-container">
                <li class="flex-option-item"><h1>Student Research Group Page</h1></li>
                <li class="flex-option-item"><button class="btn" onclick="openForm()">Join</button></li>
            </ul>
        </div>
    
    
    <div class="form-popup" id="myForm">
      <form class="form-container" method='POST'>
        <h1>Join Research Group</h1>
        <hr>
        <label for="recipient"><b>Recipient</b></label>
        <select name="recipient" required>
             <?php
                require_once('config.php');

                // Connect to database
                $conn = mysqli_connect($server, $user, $password, $database);

                // Check connection
                if (!$conn) {
                    die("Connection failed " . mysqli_connect_error());
                }

                $sql = "SELECT fname,lname,username FROM instructor";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    // Output data of each row
                    while($row = mysqli_fetch_assoc($result)) {
                        echo 
                            "<option  value='".$row['username'] ."' >" . $row['fname'] ." ". $row['lname']. "</option>" ;
                            
                    }
                } else {
                        echo "No results";
                    }
                mysqli_close($conn);
            ?>
        </select>
        <br><br>
    
        <label for="note"><b>Note</b></label>
        <textarea name="note"></textarea>
        <br><br>
    
        <label for="cv"><b>CV</b></label>
        <input type="file" name="cv">
        <br><br>
    
        <button type="submit" class="btn" name='join'>Join</button>
        <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
      </form>
    </div >
            <div class="table">
                <table class="instructor-table">
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Research Area</th>
    
                    </tr>
                    <?php
                        require_once('config.php');

                        // Connect to database
                        $conn = mysqli_connect($server, $user, $password, $database);

                        // Check connection
                        if (!$conn) {
                            die("Connection failed " . mysqli_connect_error());
                        }

                        $sql = "SELECT fname,lname,research_area FROM  instructor WHERE research_area!='' ";
                        $result = mysqli_query($conn, $sql);

                        if (mysqli_num_rows($result) > 0) {
                            // Output data of each row
                            while($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>" .
                                        "<td>" . $row['fname'] . "</td>" .
                                        "<td>" . $row['lname'] . "</td>" .
                                        "<td>" . $row['research_area'] . "</td>" .
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


<?php
    require_once('config.php');

    if (isset($_POST['join'])) {
        $iusername = $_POST['recipient'];
        $note=$_POST['note'];
        $cv;

        // Connect to database
        $conn = mysqli_connect($server, $user, $password, $database);

        // Check connection
        if (!$conn) {
            die("Connection failed " . mysqli_connect_error());
        }

        
        $sql = 'SELECT id  FROM  research_group WHERE i_username=? ';
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt,'s', $iusername);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $id);
        mysqli_stmt_fetch($stmt);
        print(mysqli_stmt_error($stmt) . "\n");


        // Connect to database
        $conn = mysqli_connect($server, $user, $password, $database); 
            // Prepare an insert statement
        $query = "INSERT INTO joins (st_username,group_id ) VALUES (?,?)"; 
             
        $statement = mysqli_prepare($conn, $query);
        
        mysqli_stmt_bind_param($statement, 'si', $st_username, $id);

        // Execute the prepared statement
        mysqli_stmt_execute($statement);
        //print(mysqli_stmt_error($statement) . "\n");

        // Close the statement and the connection
        mysqli_stmt_close($statement);
        
        mysqli_close($conn);
    }

?>
