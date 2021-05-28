<?php
    $username = $password = $fname =$lname= '';
    $errors = array('fname'=>'','lname'=>'','username'=>'', 'password'=>'');
    if (isset($_POST['login'])) {
		$type=$_POST['tab'];
		if($type='signin'){
			if(empty($_POST['fname1']) ) {
				echo "First Name:" . $_POST['fname'] . "<br>";
				$errors['fname'] = 'A first name is required ';
			} 
			else {
				$fname = $_POST['fname'];
			}
			if(empty($_POST['lname1'])) {
				$errors['lname'] = 'A last name is required';
			} else {
				$lname = $_POST['lname'];
			}
			if(empty($_POST['username1'])) {
				$errors['username'] = 'A username is required';
			} else {
				$username = $_POST['username'];
			}
	
			echo "<br>";
			if(empty($_POST['password1'])) {
				$errors['password'] = 'A password is required';
			} else {
				$password = $_POST['password1'];
				if(!preg_match('/[a-z][0-9]{3}/', $password)) {
					$errors['password'] = 'Not a valid password';
				}
			}
	
			if(array_filter($errors)) {
				 //echo 'errors in the form';
			} else {
				echo 'no errors in the form';
				// Connect to database
				$conn = mysqli_connect($server, $user, $password, $database);

				// Check connection
				if (!$conn) {
					die("Connection failed " . mysqli_connect_error());
				}
				$tablename;
				// Prepare an insert statement
				$query = "INSERT INTO ? (first_name, last_name, phone_number, birth_date) VALUES (?,?,?,?)";        
				$statement = mysqli_prepare($conn, $query);
				mysqli_stmt_bind_param($statement, 'ssss', $firstname, $lastname, $phonenumber, $birthdate);
		
				// Execute the prepared statement
				mysqli_stmt_execute($statement);
				print(mysqli_stmt_error($statement) . "\n");
		
				echo "Account created successfully";
				echo "First Name:" . $firstname . "<br>";
				echo "Last Name:" . $lastname . "<br>";
				echo "Phone Number:" . $phonenumber . "<br>";
				echo "Birth Date:" . $birthdate . "<br>";
		
				// Close the statement and the connection
				mysqli_stmt_close($statement);
				mysqli_close($conn);
				/*
				if($_POST['tab']=='student'){
					header( 'Location: StudentPage.html' );
				}
				if($_POST['tab']=='instructor'){
					header( 'Location: InstructorPage.html' );
				}
				if($_POST['tab']=='secretary'){
					header( 'Location: SecretaryPage.html' );
				}
				*/
			}
		}
		else{
			if(empty($_POST['username'])) {
				$errors['username'] = 'A username is required';
			} else {
				$username = $_POST['username'];
			}
	
			echo "<br>";
			if(empty($_POST['password'])) {
				$errors['password'] = 'A password is required';
			} else {
				$password = $_POST['password'];
				if(!preg_match('/[a-z][0-9]{3}/', $password)) {
					$errors['password'] = 'Not a valid password';
				}
			}
	
			if(array_filter($errors)) {
				 //echo 'errors in the form';
			} else {
				echo 'no errors in the form';
				if($_POST['tab']=='student'){
					header( 'Location: StudentPage.html' );
				}
				if($_POST['tab']=='instructor'){
					header( 'Location: InstructorPage.html' );
				}
				if($_POST['tab']=='secretary'){
					header( 'Location: SecretaryPage.html' );
				}
			}

		}

    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MEBIS</title>
    <link rel="stylesheet" href="MainPage.css">
    <script src="./LoginFunction.js"></script>
	<link rel="stylesheet" href="Header-Background.css">
</head>
<body>

	<div class="header">
        <ul class="flex-header-container">
            <li class="flex-header-item"><img class="header-logo" src="logo.jpg" alt=""></li>
        </ul>
    </div>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">

	<div class="login-wrap">
		<div class="login-html">
			<input id="tab-1" type="radio" name="tab" class="secretary" value="signin" checked><label for="tab-1" class="tab">Sign in</label>
			<input id="tab-2" type="radio" name="tab" class="instructor" value="signup"><label for="tab-2" class="tab">Sign up</label>
			<input id="tab-3" type="radio" name="tab" class="student" value="student"><label for="tab-3" class="tab">Student</label>
			<div class="login-form">
				<div class="secretary-htm">
					<div class="group">
						<label for="user" class="label">First Name</label>
						<input id="user" type="text" name="fname1" class="input">
						<div style="color: red;">
							<?php echo $errors['fname']; ?>
						</div>
					</div>
					<div class="group">
						<label for="user" class="label">Last name</label>
						<input id="user" type="text" name="lname1" class="input">
						<div style="color: red;">
							<?php echo $errors['lname']; ?>
						</div>
					</div>
					<div class="group">
						<label for="user" class="label">Username</label>
						<input id="user" type="text" name="username1" class="input">
						<div style="color: red;">
							<?php echo $errors['username']; ?>
						</div>
					</div>
					<div class="group">
						<label for="pass" class="label">Password</label>
						<input id="pass" type="password" class="input" name="password1" data-type="password">
						<div style="color: red;">
							<?php echo $errors['password']; ?>
						</div>
					</div>
					<div class="group">
						<input type="submit" class="button" value="Sign In"  name="login" >
					</div>
					<div class="hr"></div>	
				</div>

				<div class="instructor-htm">
					<div class="group">
						<label for="user" class="label">First Name</label>
						<input id="user" type="text" name="fname" class="input">
						<div style="color: red;">
							<?php echo $errors['fname']; ?>
						</div>
					</div>
					<div class="group">
						<label for="user" class="label">Last name</label>
						<input id="user" type="text" name="lname" class="input">
						<div style="color: red;">
							<?php echo $errors['lname']; ?>
						</div>
					</div>
					<div class="group">
						<label for="user" class="label">Username</label>
						<input id="user" type="text" name="username" class="input">
						<div style="color: red;">
							<?php echo $errors['username']; ?>
						</div>
					</div>
					<div class="group">
						<label for="pass" class="label">Password</label>
						<input id="pass" type="password" class="input" name="password" data-type="password">
						<div style="color: red;">
							<?php echo $errors['password']; ?>
						</div>
					</div>
					<div class="group">
						<input type="submit" class="button" value="Sign In"  name="login" >
					</div>
					<div class="hr"></div>				
				</div>

				<div class="student-htm">
					<div class="group">
						<label for="user" class="label">First Name</label>
						<input id="user" type="text" name="fname" class="input">
						<div style="color: red;">
							<?php echo $errors['fname']; ?>
						</div>
					</div>
					<div class="group">
						<label for="user" class="label">Last name</label>
						<input id="user" type="text" name="lname" class="input">
						<div style="color: red;">
							<?php echo $errors['lname']; ?>
						</div>
					</div>
					<div class="group">
						<label for="user" class="label">Username</label>
						<input id="user" type="text" name="username" class="input">
						<div style="color: red;">
							<?php echo $errors['username']; ?>
						</div>
					</div>
					<div class="group">
						<label for="pass" class="label">Password</label>
						<input id="pass" type="password" class="input" name="password" data-type="password">
						<div style="color: red;">
							<?php echo $errors['password']; ?>
						</div>
					</div>
					<div class="group">
						<input type="submit" class="button" value="Sign In" name="login" >
					</div>
					<div class="hr"></div>				
				</div>
			</div>
		</div>
	</div>
</form>
</body>
</html>