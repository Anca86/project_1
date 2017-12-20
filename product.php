<?php
require_once("common.php");

if(isset($_POST["edit"])) {
	$buttonValue = "Update";
} else {
	$buttonValue = "Save";
}
$succes = "";
if(isset($_POST["Save"])) {
	$target_dir = "uploads/";
	$target_file = idate("U") . basename($_FILES["file"]["name"]);
	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	$title = test_user_input($_POST["Title"]);
	$description = test_user_input($_POST["Description"]);
	$price = test_user_input($_POST["Price"]);
	if(!empty($title) && !empty($description) && !empty($price)) {
		$stmt = $conn->prepare("INSERT INTO productsnew (Title, Description, Price, Image) VALUES (?, ?, ?, ?)");
		$stmt->bind_param("ssis", $title, $description, $price, $target_file);
		if($stmt->execute()) {
			$fileId = mysqli_stmt_insert_id($stmt);
			copy($_FILES["file"]["tmp_name"], __DIR__ . "/uploads/".$target_file);
		}
	}
	$stmt->close();
}
if(isset($_POST["edit"])) {
	$editId = $_POST["hidden_id"];
    $stmt = $conn->prepare("SELECT * FROM productsnew WHERE Id=?");
    $stmt->bind_param("i", $editId);
	$stmt->execute();
	$result = $stmt->get_result(); 
	$row = $result->fetch_assoc(); 
	$stmt->close();
}
if(isset($_POST["Update"])) {
	$target_dir = "uploads/";
	$target_file = idate("U") . basename($_FILES["file"]["name"]);
	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	$editId = $_POST["hidden_id"];
	$title = test_user_input($_POST["Title"]);
	$description = test_user_input($_POST["Description"]);
	$price = test_user_input($_POST["Price"]);
	if(!empty($title) && !empty($description) && !empty($price)) {
		$sql = $conn->prepare("UPDATE productsnew set Title =?, Description =?, Price =? WHERE Id=?");
		$sql->bind_param("ssii", $title, $description, $price, $editId);
		if(is_uploaded_file($_FILES["file"]["tmp_name"])) {
			$sql = $conn->prepare("UPDATE productsnew set Title =?, Description =?, Price =?, Image =? WHERE Id=?");
			$sql->bind_param("ssisi", $title, $description, $price, $target_file, $editId);
			if($sql->execute()) {
				$fileId = mysqli_stmt_insert_id($sql);
				copy($_FILES["file"]["tmp_name"], __DIR__ . "/uploads/".$target_file);
			}
		}
		$sql->execute();
	}
	$sql->close();
	$succes = translate("Product was updated!");
}
?>
<!DOCTYPE html>
<html>
<head>
	<title><?= translate("Product") ?></title>
</head>
<body>
<form method="post" enctype="multipart/form-data" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
	<span><?= $succes ?></span><br />
	<input type="text" name="Title" required="required" placeholder="Title"
	value="<?php if (isset($_POST["edit"])) :?><?= translate($row["Title"])?><?php endif; ?>">
	<br />
	<input type="text" name="Description" required="required" placeholder="Description"
	value="<?php if (isset($_POST["edit"])) :?><?= translate($row["Description"])?><?php endif; ?>"><br />
	<input type="text" name="Price" required="required" placeholder="Price"
	value="<?php if (isset($_POST["edit"])) :?><?= translate($row["Price"])?><?php endif; ?>"><br />
	<input type="hidden" name="hidden_id" value="<?php if (isset($_POST["edit"])) :?><?= translate($row["Id"])?><?php endif; ?>">
	<input type="file" name="file" id="file"><br /><br />
	<a href="products.php">Products</a>
	<input type="submit" name="<?= $buttonValue ?>" value="<?= $buttonValue?>">
</form>
</body>
</html>