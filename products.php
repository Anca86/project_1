<?php
require_once('common.php');

if(!$_SESSION["admin"]){ 
    header("location:login.php"); 
    die();
}

if (isset($_GET["action"]) && $_GET["action"] == "delete") {
    $productId = $_GET["id"];
    $stmt = $conn->prepare("DELETE from productsnew where Id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
}

if(isset($_GET["action"]) && $_GET["action"] == "logout") {
    session_destroy();
    header("location:login.php");
    die();
}

$sql = "SELECT * from productsnew";
$result = $conn->query($sql);

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= translate("Products") ?></title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<?php if($result->num_rows > 0): ?>
    <?php while($row = $result->fetch_assoc()): ?>
        <div class="product">
                <div class="image">
                    <img src="<?= "uploads/". $row["Image"]; ?>">
                </div>
                <div class="productdetails">
                    <div class="productTitle"><?= $row["Title"] ?></div>
                    <div class="productDescription"><?= $row["Description"] ?></div>
                    <div class="productPrice"><?= $row["Price"] ?></div>
                    <a href="product.php?action=edit&amp;id=<?= $row["Id"] ?>" class="edit"><?= translate("Edit")?></a>
                    <a href="products.php?action=delete&amp;id=<?= $row["Id"] ?>" class="delete"><?= translate("Delete")?></a>
                </div>
        </div>               
    <?php endwhile; ?>
<?php endif; ?>
<a href="product.php"><?= translate("Add") ?></a>
<a href="?action=logout" class="logout"><?= translate("Logout") ?></a>
</body>
</html>