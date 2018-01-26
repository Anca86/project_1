<?php
require_once("common.php");

if(!$_SESSION["admin"]){ 
    header("location:login.php"); 
    die();
}

$uploadOk = 1;
$title = $description = $price = $uploadMsg = "";

if(isset($_POST["save"])) {
    $target_file = time() . basename($_FILES["file"]["name"]);
    $title = clean_user_input($_POST["title"]);
    $description = clean_user_input($_POST["description"]);
    $price = clean_user_input($_POST["price"]);
    $stmt = "";
    // if action is equal to add
    if(!isset($_GET["id"])) {
        if(!empty($title) && !empty($description) && !empty($price) && getimagesize($_FILES["file"]["tmp_name"])) {
            $stmt = $conn->prepare("INSERT INTO products (title, description, price, image) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssis", $title, $description, $price, $target_file);
            $uploadMsg = translate("Product was added!");
        }
    // if action is equal to edit
    } else {
        $editId = $_GET["id"];
        if(!empty($title) && !empty($description) && !empty($price )) {
            if(is_uploaded_file($_FILES["file"]["tmp_name"])) {
                if(getimagesize($_FILES["file"]["tmp_name"])) {
                    $stmt = $conn->prepare("UPDATE products set title =?, description =?, price =?, image =? WHERE id=?");
                    $stmt->bind_param("ssisi", $title, $description, $price, $target_file, $editId);
                    $uploadMsg = translate("Product was updated!");
                }
            } else {
                $stmt = $conn->prepare("UPDATE products set title =?, description =?, price =? WHERE id=?");
                $stmt->bind_param("ssii", $title, $description, $price, $editId);
                $uploadMsg = translate("Product was updated!");
            }
        }
    }

    if($stmt && $stmt->execute()) {
        move_uploaded_file($_FILES["file"]["tmp_name"], __DIR__ . "/uploads/".$target_file);
        $stmt->close();
    } else {
        $uploadMsg = "Something went wrong! Please try again!";
    }
}

//display product for edit
if(isset($_GET["action"]) && $_GET["action"] == "edit" && !isset($_POST["save"])) {
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
    <input type="text" name="title" required="required" placeholder="<?= translate("Title") ?>"
    value="<?= $title; ?>" >
    <br />
    <input type="text" name="description" required="required" placeholder="<?= translate("Description") ?>"
    value="<?= $description; ?>"><br />
    <input type="text" name="price" required="required" placeholder="<?= translate("Price") ?>"
    value="<?= $price; ?>" ><br />
    <input type="file" name="file" id="file"><br /><br />
    <a href="products.php"><?= translate("Products") ?></a>
    <input type="submit" name="save" 
    value="<?= (isset($_GET["id"])) ? "Update": "Save"; ?>">
</form>
</body>
</html>
