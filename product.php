<?php
require_once("common.php");

$target_dir = "uploads/";
$target_file = $target_dir . idate("U") . basename($_FILES["file"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

$stmt = $conn->prepare("INSERT INTO productsnew (Title, Description, Price, Image) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssis", $title, $description, $price, $image);

$title = $_POST["Title"];
$description = $_POST["Description"];
$price = $_POST["Price"];
$image = $target_file;


if($stmt->execute()) {
	$fileId = mysqli_stmt_insert_id($stmt);
	copy($_FILES['file']['tmp_name'], __DIR__ . "/".$image);
}

$stmt->close();

?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>


<form method="post" enctype="multipart/form-data" action="product.php">
	<input type="text" name="Title"><br />
	<input type="text" name="Description"><br />
	<input type="text" name="Price"><br />
	<input type="file" name="file" id="file"> <br>
	<input type="submit" name="submit" value="Save">
</form>


</body>
</html>