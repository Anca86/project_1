<?php
$servername = "localhost";
$username = "root";
$password = "ancar";
$dbname = "project1";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conection error: " . $conn->connect_error);
}

$target_dir = "images/";
$target_file = $target_dir . basename($_FILES["file"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);

	// $stmt = mysqli_stmt_init($conn);
$stmt = $conn->prepare("INSERT INTO images(productId) VALUES (?)");
$stmt->bind_param("i", $productId);

$productId = $_POST['productId'];

if($stmt->execute()) {
	$fileId = mysqli_stmt_insert_id($stmt);
	copy($_FILES['file']['tmp_name'], __DIR__ . "/images/".$fileId.".jpg");
}

// $stmt->execute();
$stmt->close();

?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

<form method="post" enctype="multipart/form-data">
	<input type="file" name="file" id="file"> <br>
	<input type="text" name="productId" id="productId">
	<input type="submit" name="submit" value="Upload">
</form>

</body>
</html>