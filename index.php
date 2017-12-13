<?php
require_once('common.php');

// $sql = "SELECT * from productsnew";
// $result = $conn->query($sql);


$stmt = mysqli_stmt_init($conn);
// $x = array();
//     foreach ($_SESSION["cart"] as $key => $value) {
//         $x[] = $value["Id"];
// }

$i = str_repeat('i', count($_SESSION["cart"]));
mysqli_stmt_prepare($stmt, "SELECT * FROM productsnew WHERE Id NOT IN (" .
str_repeat('?,', count($_SESSION["cart"]) - 1) . '?' . ")"); 

$a = 13;
$b = 12;
mysqli_stmt_bind_param($stmt, $i, $a, $b);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);


////d
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

print_r($_SESSION["cart"]);

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