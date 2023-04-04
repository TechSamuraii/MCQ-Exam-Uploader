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

// Check if file was uploaded
if (isset($_FILES['csv_file'])) {
    $file = $_FILES['csv_file']['tmp_name'];

    // Open the CSV file
    if (($handle = fopen($file, "r")) !== FALSE) {
        // Loop through each row of the CSV file
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            // Skip the first row (header)
            if ($data[0] == 'Question') {
                continue;
            }

            // Extract the values from the row
            $question = mysqli_real_escape_string($conn, $data[0]);
            $option1 = mysqli_real_escape_string($conn, $data[1]);
            $option2 = mysqli_real_escape_string($conn, $data[2]);
            $option3 = mysqli_real_escape_string($conn, $data[3]);
            $option4 = mysqli_real_escape_string($conn, $data[4]);
            $answer = mysqli_real_escape_string($conn, $data[5]);
            $image = mysqli_real_escape_string($conn, $data[6]);

            // Upload the question and answers
            if (!empty($question) && !empty($option1) && !empty($option2) && !empty($option3) && !empty($option4) && !empty($answer)) {
                // Check if an image was uploaded
                if (!empty($image)) {
                    // Save the image to the uploads folder
                    $folder = 'uploads/';
                    move_uploaded_file($_FILES['csv_file']['tmp_name'], $folder.$image);

                    // Insert the question into the database
                    $sql = "INSERT INTO questions (question, option1, option2, option3, option4, answer, image) VALUES ('$question', '$option1', '$option2', '$option3', '$option4', '$answer', '$image')";
                } else {
                    // Insert the question into the database without an image
                    $sql = "INSERT INTO questions (question, option1, option2, option3, option4, answer) VALUES ('$question', '$option1', '$option2', '$option3', '$option4', '$answer')";
                }

                if (!mysqli_query($conn, $sql)) {
                    echo 'Error: ' . $sql . '<br>' . mysqli_error($conn);
                }
            }
        }

        fclose($handle);
        echo 'OK';
    } else {
        echo 'Error uploading file.';
    }
}

mysqli_close($conn);
?>
