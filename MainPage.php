<?php
	require_once('config.php');
	session_start();
    $username = $pass = $fname =$lname= '';
    $errors = array('fname1'=>'','lname1'=>'','username1'=>'', 'password1'=>'','choice1'=>'',
					'fname2'=>'','lname2'=>'','username2'=>'', 'password2'=>'','choice2'=>'','other'=>'');
    if (isset($_POST['signup'])) {

			if(empty($_POST['fname2']) ) {
				$errors['fname2'] = 'A first name is required ';
			} 
			elseif (strlen($_POST['fname2'])>10){
				$errors['fname2'] = 'A first name has to be less than 10 characters ';
			}
			else {
				$fname = $_POST['fname2'];
			}
			if(empty($_POST['lname2'])) {
				$errors['lname2'] = 'A last name is required';
			}
			elseif(strlen($_POST['lname2'])>10){
				$errors['lname2'] = 'A last name has to be less than 10 characters ';
			}
			else {
				$lname = $_POST['lname2'];
			}
			if(empty($_POST['username2'])) {
				$errors['username2'] = 'A username is required';
			} 
			elseif(strlen($_POST['username2'])>20){
				$errors['username2'] = 'A username has to be less than 20 characters ';
			}
			else {
				$username = $_POST['username2'];
			}
			echo "<br>";
			if(empty($_POST['password2'])) {
				$errors['password2'] = 'A password is required';
			} 
			elseif(strlen($_POST['password2'])>10){
				$errors['password2'] = 'A password has to be less than 10 characters ';
			}
			else {
				$pass = $_POST['password2'];
				if(!preg_match('/[a-z][0-9]{3}/', $pass)) {
					$errors['password2'] = 'Not a valid password';
				}
			}
			if(!isset($_POST['type2'])) {
				$errors['choice2'] = 'Please select your role';
			} 
			if(array_filter($errors)) {
				// echo 'errors in the form';
			} else {				
				// Connect to database
				$conn = mysqli_connect($server, $user, $password, $database);

				// Check connection
				if (!$conn) {
					die("Connection failed " . mysqli_connect_error());
				}
				$tablename=$_POST['type2'];
				print($tablename);
				
				if($tablename=='student'){
					print('student loop');
					// Prepare an insert statement
					$query = "INSERT INTO student (fname, lname, username, password) VALUES (?,?,?,?)";        
					$statement = mysqli_prepare($conn, $query);
					mysqli_stmt_bind_param($statement, 'ssss', $fname, $lname, $username, $pass);
				}
				elseif($tablename=='instructor'){
					print('instructor loop');
					// Prepare an insert statement
					$query = "INSERT INTO instructor (fname, lname, username, password) VALUES (?,?,?,?)";        
					$statement = mysqli_prepare($conn, $query);
					mysqli_stmt_bind_param($statement, 'ssss', $fname, $lname, $username, $pass);
				}
				else{
					// Prepare an insert statement
					$query = "INSERT INTO secretary (fname, lname, username, password) VALUES (?,?,?,?)";        
					$statement = mysqli_prepare($conn, $query);
					mysqli_stmt_bind_param($statement, 'ssss', $fname, $lname, $username, $pass);
				}
				
				// Execute the prepared statement
				mysqli_stmt_execute($statement);
				print(mysqli_stmt_error($statement) . "\n");
		
				// Close the statement and the connection
				mysqli_stmt_close($statement);
				mysqli_close($conn);
				
				$_SESSION['username'] = $username;
				
				if($tablename=='student'){
					header( 'Location: StudentPage.php' );
				}
				elseif($tablename=='instructor'){
					header( 'Location: InstructorPage.html' );
				}
				else{
					header( 'Location: SecretaryPage.php' );
				}
			}
		}
		
		if(isset($_POST['signin'])){
			if(empty($_POST['username1'])) {
				$errors['username1'] = 'A username is required';
			} else {
				$username = $_POST['username1'];
			}
	
			echo "<br>";
			if(empty($_POST['password1'])) {
				$errors['password1'] = 'A password is required';
			} 
			else {
				$pass = $_POST['password1'];
			}
			if(!isset($_POST['type1'])) {
				$errors['choice1'] = 'Please select your role';
			} 
			
			if(array_filter($errors)) {
				 //echo 'errors in the form';
			} 
			else {
				// Connect to database
				$conn = mysqli_connect($server, $user, $password, $database);

				// Check connection
				if (!$conn) {
					die("Connection failed " . mysqli_connect_error());
				}
				$tablename=$_POST['type1'];
				
				if($tablename=='student'){
					// Prepare an insert statement
					$query = "Select username,password FROM student WHERE username=?";        
					$statement = mysqli_prepare($conn, $query);
					mysqli_stmt_bind_param($statement, 's',$username);
					mysqli_stmt_execute($statement);
					mysqli_stmt_bind_result($statement,$resultu, $resultp);
        			mysqli_stmt_fetch($statement);
				}
				elseif($tablename=='instructor'){
					// Prepare an insert statement
					$query = "Select username,password FROM instructor WHERE username=?";        
					$statement = mysqli_prepare($conn, $query);
					mysqli_stmt_bind_param($statement, 's',$username);
					mysqli_stmt_execute($statement);
					mysqli_stmt_bind_result($statement,$resultu, $resultp);
        			mysqli_stmt_fetch($statement);
				}
				else{
					// Prepare an insert statement
					$query = "Select username,password FROM secretary WHERE username=?";        
					$statement = mysqli_prepare($conn, $query);
					mysqli_stmt_bind_param($statement, 's',$username);
					mysqli_stmt_execute($statement);
					mysqli_stmt_bind_result($statement,$resultu, $resultp);
        			mysqli_stmt_fetch($statement);
				}
				if ($resultu!=Null and $resultp!=Null) {
						if($resultp==$pass){
							$_SESSION['username'] = $username;
							if($tablename=='student'){
								header( 'Location: StudentPage.php' );
							}
							elseif($tablename=='instructor'){
								header( 'Location: InstructorPage.html' );
							}
							else{
								header( 'Location: SecretaryPage.php' );
							}
						}
						else{
							$errors['password1']='password is not correct';
						}
				}
				else{
					$errors['other']='No user found';
				}

				// Execute the prepared statement
				
				print(mysqli_stmt_error($statement) . "\n");
		
				// Close the statement and the connection
				mysqli_stmt_close($statement);
				mysqli_close($conn);
				
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
						<label for="user" class="label">Username</label>
						<input id="user" type="text" name="username1" class="input">
						<div style="color: red;">
							<?php echo $errors['username1']; ?>
						</div>
					</div>
					<div class="group">
						<label for="pass" class="label">Password</label>
						<input id="pass" type="password" class="input" name="password1" data-type="password">
						<div style="color: red;">
							<?php echo $errors['password1']; ?>
						</div>
					</div>
					<div class="group">
						<input type="radio" name="type1"  value="student" ><label for="st" >student</label>
						<input  type="radio" name="type1"  value="instructor"><label for="inst" >instructor</label>
						<input  type="radio" name="type1"  value="secretary"><label for="sec" >secretary</label>
						<div style="color: red;">
							<?php echo $errors['choice1']; ?>
						</div>
					</div>
					<div class="group">
						<input type="submit" class="button" value="Sign In"  name="signin" >
						<div style="color: red;">
							<?php echo $errors['other']; ?>
						</div>
					</div>
					<div class="hr"></div>	
				</div>

				<div class="instructor-htm">
					<div class="group">
						<label for="user" class="label">First Name</label>
						<input id="user" type="text" name="fname2" class="input">
						<div style="color: red;">
							<?php echo $errors['fname2']; ?>
						</div>
					</div>
					<div class="group">
						<label for="user" class="label">Last name</label>
						<input id="user" type="text" name="lname2" class="input">
						<div style="color: red;">
							<?php echo $errors['lname2']; ?>
						</div>
					</div>
					<div class="group">
						<label for="user" class="label">Username</label>
						<input id="user" type="text" name="username2" class="input">
						<div style="color: red;">
							<?php echo $errors['username2']; ?>
						</div>
					</div>
					<div class="group">
						<label for="pass" class="label">Password</label>
						<input id="pass" type="password" class="input" name="password2" data-type="password">
						<div style="color: red;">
							<?php echo $errors['password2']; ?>
						</div>
					</div>
					<div class="group">
						<input type="radio" name="type2"  value="student" ><label for="st" >student</label>
						<input  type="radio" name="type2"  value="instructor"><label for="inst" >instructor</label>
						<input  type="radio" name="type2"  value="secretary"><label for="sec" >secretary</label>
						<div style="color: red;">
							<?php echo $errors['choice2']; ?>
						</div>
					</div>
					<div class="group">
						<input type="submit" class="button" value="Sign up"  name="signup" >
						
					</div>
					<div class="hr"></div>				
				</div>

			</div>
		</div>
	</div>
</form>
</body>
</html>