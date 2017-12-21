<?php
require_once('common.php');
$sql = "SELECT * from productsnew";
if (isset($_POST["delete"])) {
    $productId = $_POST["hidden_id"];
    $stmt = $conn->prepare("DELETE from productsnew where Id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
}
$result = $conn->query($sql);
if(!$_SESSION["admin"]){ 
    header("location:login.php"); 
} else {
    header( 'Content-Type: text/html; charset=utf-8' );
}
if(isset($_GET["action"]) && $_GET["action"] == "logout") {
    session_destroy();
    header("location:login.php");
}
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
            <form method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="image">
                    <img src="<?= "uploads/". $row["Image"]; ?>">
                </div>
                <div class="productdetails">
                    <div class="productTitle"><?= $row["Title"] ?></div>
                    <div class="productDescription"><?= $row["Description"] ?></div>
                    <div class="productPrice"><?= $row["Price"] ?></div>
                    <input type="hidden" name="hidden_id" value="<?= $row["Id"] ?>">
                    <input type="submit" name="edit" value="<?= translate("Edit") ?>" formaction="product.php">
                    <input type="submit" name="delete" value="<?= translate("Delete") ?>">
                </div>
            </form>
        </div>               
    <?php endwhile; ?>
<?php endif; ?>
<a href="product.php"><?= translate("Add") ?></a>
<a href="?action=logout" class="logout"><?= translate("Logout") ?></a>
</body>
</html>