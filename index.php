<?php
require_once('common.php');
if(isset($_SESSION["cart"])) {
    $ids = array();
    foreach ($_SESSION["cart"] as $key => $value) {
        $ids[] = $value["Id"];
    }
    $stringIds = implode(", ", $ids);
    if(count($_SESSION["cart"]) == 0) {
        $myvar = count($_SESSION["cart"]);
    }
    $sql = "SELECT * FROM productsnew WHERE Id NOT IN ($stringIds)";
} 
$result = $conn->query($sql);
if(isset($_POST["add_to_cart"])) {
    if(!isset($_SESSION["cart"])) {
        $_SESSION["cart"] = array();
    }   
    $item_array_id = array_column($_SESSION["cart"], "Id");
    if(!in_array($_POST["hidden_id"], $item_array_id)) {
        $_SESSION["cart"][]= array(
            "Id" => $_POST["hidden_id"]
                );
    } 
}
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<?php if($result->num_rows > 0): ?>
    <?php while($row = $result->fetch_assoc()): ?>
        <div class="product">
            <form method="post" action="index.php">
                <div class="image">
                    <img src="<?= $row["Image"]; ?>">
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