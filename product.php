<?php
require_once("common.php");

if(isset($_POST["submit"])) {
	$target_dir = "uploads/";
	$target_file = $target_dir . idate("U") . basename($_FILES["file"]["name"]);
	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	$title = $_POST["Title"];
	$description = $_POST["Description"];
	$price = $_POST["Price"];
	$image = $target_file;
	if(!empty($title) && !empty($description) && !empty($price)) {
		$stmt = $conn->prepare("INSERT INTO productsnew (Title, Description, Price, Image) VALUES (?, ?, ?, ?)");
		$stmt->bind_param("ssis", $title, $description, $price, $image);
		if($stmt->execute()) {
			$fileId = mysqli_stmt_insert_id($stmt);
			copy($_FILES['file']['tmp_name'], __DIR__ . "/".$image);
		}
	}
	$stmt->close();
}

// if(isset($_POST["edit"])) {
// 	$editId = $_POST["hidden_id"];
// 	print_r($editId);
//     $stmt = $conn->prepare("SELECT * FROM productsnew WHERE Id=?");
//     $stmt->bind_param("i", $editId);
// 	$stmt->execute();
// 	$result = $stmt->get_result(); 
// 	$row = $result->fetch_assoc(); 
// 	$title= "";
// 	if(isset($_POST["submit"])) {
// 		$editId = $_POST["hidden_id"];
// 		print_r($editId);
// 		$title = $_POST["Title"];
// 		print_r($title);
// 		$description = $_POST["Description"];
// 		$price = $_POST["Price"];
// 		$x = 14;
// 		$sql = $conn->prepare("UPDATE 'productsnew' set Title =?, Description =?, Price =? WHERE Id=$x");
// 		$sql->bind_param("ssi", $title, $description, $price, $x);
// 		$sql->execute();
// 	} else {echo "string";}
// 	$stmt->close();
// }

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
// 	if(isset($_POST["edit"]) && isset($_POST["submit"])) {
// 		$editId = $_POST["hidden_id"];
// 	    $stmt = $conn->prepare("SELECT * FROM productsnew WHERE Id=?");
// 	    $stmt->bind_param("i", $editId);
// 		$stmt->execute();
// 		$result = $stmt->get_result(); 
// 		$row = $result->fetch_assoc(); 
// 		$title = $_POST["Title"];
// 		$description = $_POST["Description"];
// 		$price = $_POST["Price"];
// 		$sql = $conn->prepare("UPDATE productsnew set Title =?, Description =?, Price =? WHERE Id=$editId");
// 		$sql->bind_param("ssi", $title, $description, $price);
// 		$sql->execute();
// 		$stmt->close();
// 	}
// }

?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>


<form method="post" enctype="multipart/form-data" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
	<input type="text" name="Title" required="required" placeholder="Title"
	value="<?php if (isset($_POST["edit"]) && isset($_POST["submit"])) :?><?= translate($row["Title"])?><?php endif; ?>">
	<br />
	<input type="text" name="Description" required="required" placeholder="Description"
	value="<?php if (isset($_POST["edit"])) :?><?= translate($row["Description"])?><?php endif; ?>"><br />
	<input type="text" name="Price" required="required" placeholder="Price"
	value="<?php if (isset($_POST["edit"])) :?><?= translate($row["Price"])?><?php endif; ?>"><br />
	<input type="hidden" name="hidden_id" value="<?php if (isset($_POST["edit"])) :?><?= translate($row["Id"])?><?php endif; ?>">
	<input type="file" name="file" id="file"><br /><br />
	<a href="products.php">Products</a>
	<input type="submit" name="submit" value="<?= translate("Save") ?>">
</form>


</body>
</html>