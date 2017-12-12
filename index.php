<?php
require_once('common.php');

$sql = "SELECT * from productsnew";
$result = $conn->query($sql);

if(isset($_POST["add_to_cart"])) {
    if(isset($_SESSION["cart"])) {
        $item_array_id = array_column($_SESSION["cart"], "id");

        if(!in_array($_GET["id"], $item_array_id)) {

            $count = count($_SESSION["cart"]);
            $item_array = array(
                    "id" => $_GET["id"], 
                    "url" => $_POST["hidden_url"],
                    "title" => $_POST["hidden_title"],
                    "description" => $_POST["hidden_description"],
                    "price" => $_POST["hidden_price"]
                );
            $_SESSION["cart"][$count] = $item_array;
        } else {
            echo "I am already added";
        }

    } else {
        $item_array = array(
                "id" => $_GET["id"],
                "url" => $_POST["hidden_url"],
                "title" => $_POST["hidden_title"],
                "description" => $_POST["hidden_description"],
                "price" => $_POST["hidden_price"]
            );
        $_SESSION["cart"][0] = $item_array;
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
    <?php while($row = $result->fetch_assoc()):?>
            <div class="product">
            <form method="post" action="index.php?action=add&amp;id=<?=$row['Id'] ?>">
                <div class="image">
                    <img src="<?= $row["Image"];?>">
                </div>
                <div class="productdetails">
                    <div class="productTitle"><?= $row["Title"]?></div>
                    <div class="productDescription"><?=$row["Description"]?></div>
                    <div class="productPrice"><?=$row["Price"]?></div>
                    <input type="hidden" name="hidden_url" value="<?= $row["url"]?>">
                    <input type="hidden" name="hidden_title" value="<?= $row["Title"]?>">
                    <input type="hidden" name="hidden_description" value="<?=$row["Description"]?>">
                    <input type="hidden" name="hidden_price" value="<?=$row["Price"]?>">
                    <input type="submit" name="add_to_cart" value="Add">
                </div>
            </form>
            </div>               
    <?php endwhile; ?>
<?php endif;?>

<div class="linkToCart">
    <a href="cart.php"><?=translate("Go to cart")?></a>
</div>

</body>
</html>