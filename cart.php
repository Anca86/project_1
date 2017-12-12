<?php

require_once("common.php");

$sql = "SELECT * from productsnew";
$result = $conn->query($sql);

if(!empty($_GET["action"])) {  
        if($_GET["action"] == "remove") {
            foreach ($_SESSION["cart"] as $keys => $values) {
                if ($values["id"] == $_GET["id"]) {
                    unset($_SESSION["cart"][$keys]);
                }
            }
        }
}

// $x = array_column($_SESSION["cart"], "id");
// print_r($x);

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
	<?php if(!empty($_SESSION["cart"])): ?>
		<?php if($result->num_rows > 0): ?>
	    <?php while($row = $result->fetch_assoc()): ?>
		<?php foreach (array_column($_SESSION["cart"], "id") as $keys => $values):?> 
			<?php if($values == $row["Id"]): ?>
		<div class="product"> 
		    	<div class="image">
                    <img src="<?= $row["Image"]?>">
                </div>
                <div class="productdetails">
                    <div class="productTitle"><?= $row["Title"]?></div>
                    <div class="productDescription"><?=$row["Description"]?></div>
                    <div class="productPrice"><?=$row["Price"]?></div>
                   <a href="cart.php?action=remove&amp;id=<?= $row["Id"] ?>" class="remove"><?=translate("Remove")?></a>
                </div>
        </div>
        <?php endif; ?>
		<?php endforeach;?> 
    <?php endwhile; ?>
    <?php endif; ?>
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