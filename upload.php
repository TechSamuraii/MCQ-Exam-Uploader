<?php
// Database connection settings
$host = 'localhost';
$user = 'root'; // default XAMPP username
$password = ''; // default XAMPP password
$database = 'mcq_exam'; // replace with your desired database name

// Connect to database
$conn = mysqli_connect($host, $user, $password, $database);
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}

// Upload question and answers
$question = $_POST['question'];
$option1 = $_POST['option1'];
$option2 = $_POST['option2'];
$option3 = $_POST['option3'];
$option4 = $_POST['option4'];
$answer = $_POST['answer'];

// Check if an image was uploaded
if ($_FILES['image']['size'] > 0) {
	$image = $_FILES['image']['name'];
	$temp = $_FILES['image']['tmp_name'];
	$folder = 'uploads/';

	move_uploaded_file($temp, $folder.$image);

	$sql = "INSERT INTO questions (question, option1, option2, option3, option4, answer, image) VALUES ('$question', '$option1', '$option2', '$option3', '$option4', '$answer', '$image')";
} else {
	$sql = "INSERT INTO questions (question, option1, option2, option3, option4, answer) VALUES ('$question', '$option1', '$option2', '$option3', '$option4', '$answer')";
}

if (mysqli_query($conn, $sql)) {
	echo 'Question added successfully!';
	// Clear input fields
	$_POST['question'] = $_POST['option1'] = $_POST['option2'] = $_POST['option3'] = $_POST['option4'] = $_POST['answer'] = '';
} else {
	echo 'Error: ' . $sql . '<br>' . mysqli_error($conn);
}

mysqli_close($conn);
?>
