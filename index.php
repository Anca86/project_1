<?php
require_once('common.php');
$sql = "SELECT * FROM productsnew";
$result = $conn->query($sql);


if(isset($_SESSION["cart"])) {
    $stringIds = implode(", ", $_SESSION["cart"]);
    $stmt =$conn->prepare("SELECT * FROM productsnew WHERE Id NOT IN ($stringIds)");
    $stmt->bind_param("s", $_SESSION["cart"]);
    $stmt->execute();
    $result = mysqli_stmt_get_result($stmt);
} 

if(isset($_POST["add_to_cart"])) {
    if(!isset($_SESSION["cart"])) {
        $_SESSION["cart"] = array();
    } 
    if(!in_array($_POST["hidden_id"], $_SESSION["cart"])) {
        array_push($_SESSION["cart"], $_POST["hidden_id"]);
    }
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
            <form method="post" action="index.php">
                <div class="image">
                    <img src="<?= "uploads/". $row["Image"]; ?>">
                </div>
                <div class="productdetails">
                    <div class="productTitle"><?= $row["Title"] ?></div>
                    <div class="productDescription"><?= $row["Description"] ?></div>
                    <div class="productPrice"><?= $row["Price"] ?></div>
                    <input type="hidden" name="hidden_id" value="<?= $row["Id"] ?>">
                    <input type="submit" name="add_to_cart" value="<?= translate("Add") ?>">
                </div>
            </form>
        </div>               
    <?php endwhile; ?>
<?php endif; ?>
<div class="linkToCart">
    <a href="cart.php"><?= translate("Go to cart") ?></a>
</div>
</body>
</html>