<?php
require_once('common.php');

if(!$_SESSION["admin"]){ 
    header("location:login.php"); 
    die();
}

if (isset($_GET["id"])) {
    $productId = $_GET["id"];
    $stmt = $conn->prepare("DELETE from products where id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
}

if(isset($_GET["action"]) && $_GET["action"] == "logout") {
    session_destroy();
    header("location:login.php");
    die();
}

$sql = "SELECT * from products";
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
                    <img src="uploads/<?= $row["image"]; ?>">
                </div>
                <div class="productdetails">
                    <div class="productTitle"><?= $row["title"] ?></div>
                    <div class="productDescription"><?= $row["description"] ?></div>
                    <div class="productPrice"><?= $row["price"] ?></div>
                    <a href="product.php?id=<?= $row["id"] ?>" class="edit"><?= translate("Edit")?></a>
                    <a href="products.php?id=<?= $row["id"] ?>" class="delete"><?= translate("Delete")?></a>
                </div>
        </div>               
    <?php endwhile; ?>
<?php endif; ?>
<a href="product.php"><?= translate("Add") ?></a>
<a href="?action=logout" class="logout"><?= translate("Logout") ?></a>
</body>
</html>