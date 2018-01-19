<?php
require_once("common.php");

if(!$_SESSION["admin"]){ 
    header("location:login.php"); 
    die();
}

$uploadOk = 1;
$target_dir = translate("uploads/");
$title = $description = $price = $uploadMsg = "";
// if $_GET["action"] is equal to edit
$isActionEdit = isset($_GET["action"]) && $_GET["action"] == "edit"; 
// if we have product Id from $_GET["action"]
$ifProductId = isset($_GET["action"]) && $_GET["id"];

if(isset($_POST["save"]) || isset($_POST["update"])) {
    $target_file = time() . basename($_FILES["file"]["name"]);
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    $editId = $_POST["hidden_id"];
    $title = clean_user_input($_POST["Title"]);
    $description = clean_user_input($_POST["Description"]);
    $price = clean_user_input($_POST["Price"]);
    // if action is equal to add
    if(isset($_POST["save"])) {
        if(!empty($title) && !empty($description) && !empty($price) && getimagesize($_FILES["file"]["tmp_name"])) {
            $stmt = $conn->prepare("INSERT INTO productsnew (Title, Description, Price, Image) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssis", $title, $description, $price, $target_file);
            $uploadMsg = translate("Product was added!");
        }
    // if action is equal to edit
    } else {
        if(!empty($title) && !empty($description) && !empty($price)) {
            $stmt = $conn->prepare("UPDATE productsnew set Title =?, Description =?, Price =? WHERE Id=?");
            $stmt->bind_param("ssii", $title, $description, $price, $editId);
            $uploadMsg = translate("Product was updated!");
            //if image is uploaded
            if(is_uploaded_file($_FILES["file"]["tmp_name"])) {
                if(getimagesize($_FILES["file"]["tmp_name"])) {
                    $stmt = $conn->prepare("UPDATE productsnew set Title =?, Description =?, Price =?, Image =? WHERE Id=?");
                    $stmt->bind_param("ssisi", $title, $description, $price, $target_file, $editId);
                    $uploadMsg = translate("Product was updated!");
                } else {
                    $uploadMsg = translate("The file is not an image!");
                }
            }
        }
    }
    if($stmt->execute()) {
        $fileId = mysqli_stmt_insert_id($stmt);
        move_uploaded_file($_FILES["file"]["tmp_name"], __DIR__ . "/uploads/".$target_file);
    } else {
        $uploadMsg = "Something went wrong! Please try again!";
    }
    $stmt->close();
}

//display product for edit
if(isset($_GET["action"]) && $_GET["action"] == "edit") {
    $editId = $_GET["id"];
    $stmt = $conn->prepare("SELECT * FROM productsnew WHERE Id=?");
    $stmt->bind_param("i", $editId);
    $stmt->execute();
    $result = $stmt->get_result(); 
    $row = $result->fetch_assoc(); 
    $stmt->close();
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
    <span><?= $uploadMsg ?></span><br />
    <input type="text" name="Title" required="required" placeholder="Title"
    value="<?= ($isActionEdit) ? translate($row["Title"]) : $title; ?>" >
    <br />
    <input type="text" name="Description" required="required" placeholder="Description"
    value="<?= ($isActionEdit) ? translate($row["Description"]) : $description; ?>"><br />
    <input type="text" name="Price" required="required" placeholder="Price"
    value="<?= ($isActionEdit) ? translate($row["Price"]) : $price; ?>" ><br />
    <input type="hidden" name="hidden_id" 
    value="<?= ($isActionEdit) ? translate($row["Id"]) : $editId; ?>">
    <input type="file" name="file" id="file"><br /><br />
    <a href="products.php"><?= translate("Products") ?></a>
    <input type="submit" name="<?= ($ifProductId) ? "update": "save"; ?>" 
    value="<?= ($ifProductId) ? "Update": "Save"; ?>">
</form>
</body>
</html>
