<?php
require_once('common.php');

// $result->bind_params("i", $x)
$x = array();
foreach ($_SESSION["cart"] as $key => $value) {
    $x[] = $value["id"];
}

$n = implode(", ", $x);
echo $n;
/////
$sql = "SELECT * from productsnew
 where Id not in (?, ?, ?);
 ";
$result = $conn->query($sql);
$result->bind_params("iii", $n);
$result->execute();


if(isset($_POST["add_to_cart"])) {
    if(!isset($_SESSION["cart"])) {
        $_SESSION["cart"] = array();
    }   
    $item_array_id = array_column($_SESSION["cart"], "id");
    if(!in_array($_POST["hidden_id"], $item_array_id)) {
        $_SESSION["cart"][] = array(
            "id" => $_POST["hidden_id"]
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
<?php endif;?>

<div class="linkToCart">
    <a href="cart.php"><?= translate("Go to cart") ?></a>
</div>

</body>
</html>