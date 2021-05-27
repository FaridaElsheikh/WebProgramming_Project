<?php


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

    <div class="w-75 p-3">

        <div>
            <h1>Best Shopping Website</h1>
            <p align='justify'>
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ducimus nisi esse optio qui veniam eveniet dolore ea, ipsum quod iure, perferendis vel dicta, in ipsa numquam totam cupiditate accusamus aspernatur.
                Lorem ipsum dolor sit amet consectetur, adipisicing elit. Perspiciatis, doloremque quis quod sint, doloribus minus, sequi eligendi hic itaque animi ducimus pariatur id explicabo officia molestiae! Rerum architecto ipsam consectetur!
            </p>
        </div>

        <h1>Our Customers</h1>
        <table class="table table-dark table-striped">
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Phone Number</th>
                <th>Birth Date</th>
            </tr>

            <?php
                require_once('config.php');

                // Connect to database
                $conn = mysqli_connect($server, $user, $password, $database);

                // Check connection
                if (!$conn) {
                    die("Connection failed " . mysqli_connect_error());
                }

                $sql = "SELECT id, first_name, last_name, phone_number, birth_date FROM customers";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    // Output data of each row
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>" .
                                "<td>" . $row['id'] . "</td>" .
                                "<td>" . $row['first_name'] . "</td>" .
                                "<td>" . $row['last_name'] . "</td>" .
                                "<td>" . $row['phone_number'] . "</td>" .
                                "<td>" . $row['birth_date'] . "</td>" .
                            "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "No results";
                }
                
                mysqli_close($conn);
            ?>    

        </table> 
          
    </div>

</body>
</html>