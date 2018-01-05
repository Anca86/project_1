<?php
require_once("common.php");

if(!$_SESSION["admin"]){ 
    header("location:login.php"); 
} else {
    header( 'Content-Type: text/html; charset=utf-8' );
}

$uploadOk = 1;
$target_dir = "uploads/";
$buttonValue = "Save";
if(isset($_POST["edit"]) || isset($_POST["Update"])) {
    $buttonValue = "Update";
}
$title = $description = $price = $succes = "";

//add product
if(isset($_POST["Save"])) {
    $target_file = idate("U") . basename($_FILES["file"]["name"]);
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
    $succes = "Product was added!";
}

//display product for edit
if(isset($_POST["edit"])) {
    $editId = $_POST["hidden_id"];
    $stmt = $conn->prepare("SELECT * FROM productsnew WHERE Id=?");
    $stmt->bind_param("i", $editId);
    $stmt->execute();
    $result = $stmt->get_result(); 
    $row = $result->fetch_assoc(); 
    $stmt->close();
}
//edit product
if(isset($_POST["Update"])) {
    $target_file = idate("U") . basename($_FILES["file"]["name"]);
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    $editId = $_POST["hidden_id"];
    $title = test_user_input($_POST["Title"]);
    $description = test_user_input($_POST["Description"]);
    $price = test_user_input($_POST["Price"]);
    if(!empty($title) && !empty($description) && !empty($price)) {
        $sql = $conn->prepare("UPDATE productsnew set Title =?, Description =?, Price =? WHERE Id=?");
        $sql->bind_param("ssii", $title, $description, $price, $editId);
        //if image is uploaded
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
    $succes = "Product was updated!";
}
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= translate("Product") ?></title>
</head>
<body>
<form method="post" enctype="multipart/form-data">
    <span><?= $succes ?></span><br />
    <input type="text" name="Title" required="required" placeholder="Title"
    value="<?= (isset($_POST["edit"])) ? translate($row["Title"]) : $title; ?>" >
    <br />
    <input type="text" name="Description" required="required" placeholder="Description"
    value="<?= (isset($_POST["edit"])) ? translate($row["Description"]) : $description; ?>"><br />
    <input type="text" name="Price" required="required" placeholder="Price"
    value="<?= (isset($_POST["edit"])) ? translate($row["Price"]) : $price; ?>" ><br />
    <input type="hidden" name="hidden_id" 
    value="<?= (isset($_POST["edit"])) ? translate($row["Id"]) : $editId; ?>">
    <input type="file" name="file" id="file"><br /><br />
    <a href="products.php"><?= translate("Products") ?></a>
    <input type="submit" name="<?= $buttonValue ?>" value="<?= $buttonValue?>">
</form>
</body>
</html>