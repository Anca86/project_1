<?php
require_once("common.php");

if(!$_SESSION["admin"]){ 
    header("location:login.php"); 
    die();
}

$uploadOk = 1;
$title = $description = $price = $uploadMsg = $errMsg = $titleErr = $descriptionErr = $priceErr = $imageErr = "";
$isId = isset($_GET["id"]);
$isSave = isset($_POST["save"]);

if($isSave) {
    $target_file = time() . basename($_FILES["file"]["name"]);
    $title = clean_user_input($_POST["title"]);
    $description = clean_user_input($_POST["description"]);
    $price = clean_user_input($_POST["price"]);
    $stmt = "";
    //if add
    if(!$isId) {
        if(!empty($title) && !empty($description) && !empty($price) && is_uploaded_file($_FILES["file"]["tmp_name"])) {
            if(getimagesize($_FILES["file"]["tmp_name"])) {
                $stmt = $conn->prepare("INSERT INTO products (title, description, price, image) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssis", $title, $description, $price, $target_file);
                $uploadMsg = translate("Product was added!");
            } else {
                $imageErr = translate("File uploaded is not an image!");
            }
        } elseif (empty($title)) {
            $titleErr = "Title is empty. No spaces allowed.";
        } elseif (empty($description)) {
            $descriptionErr = "Description is empty. No spaces allowed.";
        } elseif (empty($price)) {
            $priceErr = "Price is empty. No spaces allowed.";
        } elseif(!is_uploaded_file($_FILES["file"]["tmp_name"])) {
            $imageErr = "Image must be uploaded!";
        }
    } else { // if edit
        $editId = $_GET["id"];
        if(!empty($title) && !empty($description) && !empty($price)) {
            // if we want to update the image
            if(is_uploaded_file($_FILES["file"]["tmp_name"])) { 
                if(getimagesize($_FILES["file"]["tmp_name"])) {
                    $stmt = $conn->prepare("UPDATE products set title =?, description =?, price =?, image =? WHERE id=?");
                    $stmt->bind_param("ssisi", $title, $description, $price, $target_file, $editId);
                    $uploadMsg = translate("Product was updated!");
                } else {
                    $imageErr = translate("File uploaded is not an image!");
                }
            } else {
                $stmt = $conn->prepare("UPDATE products set title =?, description =?, price =? WHERE id=?");
                $stmt->bind_param("ssii", $title, $description, $price, $editId);
                $uploadMsg = translate("Product was updated!");
            }
        }
    }
    if($stmt && $stmt->execute()) {
        if(is_uploaded_file($_FILES["file"]["tmp_name"]) && getimagesize($_FILES["file"]["tmp_name"])) {
            move_uploaded_file($_FILES["file"]["tmp_name"], __DIR__ . "/uploads/".$target_file);
        }
        $stmt->close();
    } else {
        $errMsg = translate("Something went wrong! Please try again!");
    }
}

//display product for edit
if($isId && !$isSave) {
    $editId = $_GET["id"];
    $stmt = $conn->prepare("SELECT * FROM products WHERE id=?");
    $stmt->bind_param("i", $editId);
    $stmt->execute();
    $result = $stmt->get_result(); 
    $row = $result->fetch_assoc(); 
    $stmt->close();
    $title = $row["title"];
    $description = $row["description"];
    $price = $row["price"];
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
    <span><?= $errMsg ?></span><br />
    <input type="text" name="title" required="required" placeholder="<?= translate("Title") ?>"
    value="<?= $title; ?>" >  <span><?= $titleErr ?></span><br />
    <input type="text" name="description" required="required" placeholder="<?= translate("Description") ?>"
    value="<?= $description; ?>"> <span><?= $descriptionErr ?></span><br />
    <input type="number" name="price" required="required" placeholder="<?= translate("Price") ?>"
    value="<?= $price; ?>" ><br /> <span><?= $priceErr ?></span>
    <input type="file" name="file" id="file"><span><?= $imageErr ?></span><br /><br /> 
    <a href="products.php"><?= translate("Products") ?></a>
    <input type="submit" name="save" 
    value="<?= $isId ? translate("Update"): translate("Save"); ?>">
</form>
</body>
</html>
