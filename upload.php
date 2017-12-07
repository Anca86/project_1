<?php 

// $target_dir = "images/";
// $target_file = $target_dir . basename($_FILES["file"]["name"]);
// $uploadOk =  1;
// $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

$target_dir = "images/";
$target_file = $target_dir . basename($_FILES["file"]["name"]);

$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
// Check if image file is a actual image or fake image
// if(isset($_POST["submit"])) {
//     $check = getimagesize($_FILES["file"]["tmp_name"]);
//     if($check !== false) {
//         echo "File is an image - " . $check["mime"] . ".";
//         $uploadOk = 1;
//     } else {
//         echo "File is not an image.";
//         $uploadOk = 0;
//     }
// }