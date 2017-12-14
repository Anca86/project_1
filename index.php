<?php
require_once('common.php');

// option 1 

// $stmt = mysqli_stmt_init($conn);
// $i = str_repeat('i', count($_SESSION["cart"]));
// mysqli_stmt_prepare($stmt, "SELECT * FROM productsnew WHERE Id NOT IN (" .
// str_repeat('?,', count($_SESSION["cart"]) - 1) . '?' . ")"); 

// mysqli_stmt_bind_param($stmt, $i, );
// mysqli_stmt_execute($stmt);
// $result = mysqli_stmt_get_result($stmt);

//option 2 - that works
// $sql = "SELECT * FROM productsnew";
if(isset($_SESSION["cart"])) {
    $x = array();
    foreach ($_SESSION["cart"] as $key => $value) {
        $x[] = $value["Id"];
    }
    $myvar = implode(", ", $x);
    if(count($_SESSION["cart"]) == 0) {
        $myvar = count($_SESSION["cart"]);
    }
    $sql = "SELECT * FROM productsnew WHERE Id NOT IN ($myvar)";
} 

$result = $conn->query($sql);

//////option 3
// $stmt = mysqli_stmt_init($conn);
// function myFunc() {
//     global $stmt;
//     foreach ($_SESSION["cart"] as $key => $value) { 
//         $n = "i";;
//         mysqli_stmt_prepare($stmt, "SELECT * FROM productsnew WHERE Id NOT IN (?)");
//         mysqli_stmt_bind_param($stmt, $n, $value["Id"]);
//     }
// }

// $x = array();
// foreach ($_SESSION["cart"] as $key => $value) {
//     $x[] = $value["Id"];
// }
// call_user_func_array("myFunc", $x);
// mysqli_stmt_execute($stmt);
// $result = mysqli_stmt_get_result($stmt);





///////code for session
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