<?php
require_once('common.php');

if(isset($_GET["id"])) {
    if(!isset($_SESSION["cart"])) {
        $_SESSION["cart"] = array();
    } 
    if(!in_array($_GET["id"], $_SESSION["cart"])) {
        array_push($_SESSION["cart"], $_GET["id"]);
    }
}

if(isset($_SESSION["cart"]) && count($_SESSION["cart"])) {
    $stmt =$conn->prepare("SELECT * FROM products WHERE id NOT IN 
    (" . implode(", ", array_fill(0, count($_SESSION["cart"]), '?')) . ")");
    $params = array(
        implode("", array_fill(0, count($_SESSION["cart"]), 'i'))
    );
    $params = array_merge($params, $_SESSION["cart"]);
    $paramsRef = array();
    foreach ($params as $key => $value) {
        $paramsRef[] = &$params[$key];
    }
    call_user_func_array(array($stmt, 'bind_param'), $paramsRef);
    $stmt->execute();
    $result = mysqli_stmt_get_result($stmt);
} else {
    $sql = "SELECT * FROM products";
    $result = $conn->query($sql);
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
            <div class="image">
                <img src="uploads/<?= $row["image"]; ?>">
            </div>
            <div class="productdetails">
                <div class="productTitle"><?= $row["title"] ?></div>
                <div class="productDescription"><?= $row["description"] ?></div>
                <div class="productPrice"><?= $row["price"] ?></div>
                <a href="index.php?action=add&amp;id=<?= $row["id"] ?>" class="add"><?= translate("Add") ?></a>
            </div>
        </div>               
    <?php endwhile; ?>
<?php endif; ?>
<div class="linkToCart">
    <a href="cart.php"><?= translate("Go to cart") ?></a>
</div>
</body>
</html>