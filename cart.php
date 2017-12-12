<?php

require_once("common.php");

if(!empty($_GET["action"])) {  
        if($_GET["action"] == "remove") {
            foreach ($_SESSION["cart"] as $keys => $values) {
                if ($values["id"] == $_GET["id"]) {
                    unset($_SESSION["cart"][$keys]);
                }
            }
        }
}

// if(isset($_POST["checkout"])) {
// 	$to = "ancarusu2000@gmail.com";
// 	$from = $_POST["contactDetails"];
// 	$name = $_POST["name"];
// 	$subject = "Form submision";
// 	$message = $name . "wrote: " . $_POST["comments"];
// 	$headers = "From: " . $from;
// 	mail($to, $subject, $message, $headers);
// 	ini_set("SMTP","ssl://smtp.gmail.com");
// 	ini_set("smtp_port","465");
// }

// ini_set("SMTP","ssl://smtp.gmail.com");
// ini_set("smtp_port","465");

?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<?php if(!empty($_SESSION["cart"])):?>
		<?php foreach ($_SESSION["cart"] as $keys => $values):?>
		<div class="product"> 
		    	<div class="image">
                    <img src="<?= $values["url"]?>">
                </div>
                <div class="productdetails">
                    <div class="productTitle"><?= $values["title"]?></div>
                    <div class="productDescription"><?=$values["description"]?></div>
                    <div class="productPrice"><?=$values["price"]?></div>
                   <a href="cart.php?action=remove&amp;id=<?= $values["id"] ?>" class="remove"><?=translate("Remove")?></a>
                </div>
        </div>
        <?php endforeach;?>
	<?php endif; ?>

<div class="linkToIndex">
    <a href="index.php"><?=translate("Go to index")?></a>
</div>

<form action="cart.php" method="post">
	<input type="text" name="name" placeholder="Name"><br />
	<input type="text" name="contactDetails" placeholder="Contact details"><br />
	<input type="text" name="comments" placeholder="Comments"><br />
	<input type="submit" name="checkout" value="Checkout">
</form>
</body>
</html>