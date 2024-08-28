<?php
session_start();

$title = $_REQUEST['title']?? null;
$detail = $_REQUEST['detail'] ?? null;
$cta_title = $_REQUEST['cta_title']?? null;
$cta_link = $_REQUEST['cta_link']?? null;
$video_link = $_REQUEST['video_link']?? null;
$banner_img = $_FILES['banner_img']?? null;
$extension = pathinfo($banner_img['name'])['extension'] ?? null; //* png
$acceptedExtension = [
    'jpg',
    'png',
];
//  print_r($banner_img);
//  exit();

$errors = [];





//* Validation Rules
if(empty($title)){
    $errors['title_error'] = 'title is missing!';
}
if(empty($detail)){
    $errors['detail_error'] = 'detail is missing!';
}

//* PHOTO VALIDATION
if($banner_img['size'] == 0){
     $errors['banner_img_error'] = "Banner Image is missing";
} else if(!in_array($extension,$acceptedExtension)){
    $errors['banner_img_error'] = "$extension is not acceptable. Accepted types are " . join(', ',$acceptedExtension);
}


if(count($errors) > 0){
    //* ERROR FOUND
    $_SESSION['errors'] = $errors;
    header("Location: ../dashboard/Banner.php");
}else{
    define("UPLOAD_PATH", "../Uploads");
    if (!file_exists(UPLOAD_PATH)) {
        mkdir(UPLOAD_PATH);
    }
  $fileName = 'Banner-' . uniqid() . ".$extension";
  move_uploaded_file($banner_img['tmp_name'], UPLOAD_PATH . "/$fileName");



        include "../database/env.php";


        $query = "UPDATE banners SET status = 0";
        mysqli_query($conn, $query);


        $query = "INSERT INTO banners( title, detail,cta_title,cta_link,video_link, banner_img) VALUES ('$title','$detail','$cta_title','$cta_link','$video_link','./Uploads/$fileName')";

        $res = mysqli_query($conn, $query);


        if ($res) {
          $query = "SELECT * FROM banners WHERE status = 1";
          $result = mysqli_query($conn, $query);
          $banner = mysqli_fetch_assoc($result);
          $_SESSION["banner"] = $banner;
          $_SESSION["success"] = true;
          header("Location: ../dashboard/banner.php");
        }
      }
        
        




















?>
