<?php

include '../components/connect.php';

if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:login.php');
}

if(isset($_GET['get_id'])){
   $get_id = $_GET['get_id'];
}else{
   $get_id = '';
   header('location:dashboard.php');
}

if(isset($_POST['update'])){

   $paper_id = $_POST['paper_id'];
   $paper_id = filter_var($paper_id, FILTER_SANITIZE_STRING);
   $status = $_POST['status'];
   $status = filter_var($status, FILTER_SANITIZE_STRING);
   $title = $_POST['title'];
   $title = filter_var($title, FILTER_SANITIZE_STRING);
 

   $update_paper = $conn->prepare("UPDATE `paper` SET title = ?, status = ? WHERE id = ?");
   $update_paper->execute([$title, $status, $paper_id]);

   
   $old_thumb = $_POST['old_thumb'];
   $old_thumb = filter_var($old_thumb, FILTER_SANITIZE_STRING);
   $thumb = $_FILES['thumb']['name'];
   $thumb = filter_var($thumb, FILTER_SANITIZE_STRING);
   $thumb_ext = pathinfo($thumb, PATHINFO_EXTENSION);
   $rename_thumb = unique_id().'.'.$thumb_ext;
   $thumb_size = $_FILES['thumb']['size'];
   $thumb_tmp_name = $_FILES['thumb']['tmp_name'];
   $thumb_folder = '../uploaded_files/'.$rename_thumb;

   if(!empty($thumb)){
      if($thumb_size > 2000000){
         $message[] = 'image size is too large!';
      }else{
         $update_thumb = $conn->prepare("UPDATE `paper` SET thumb = ? WHERE id = ?");
         $update_thumb->execute([$rename_thumb, $paper_id]);
         move_uploaded_file($thumb_tmp_name, $thumb_folder);
         if($old_thumb != '' AND $old_thumb != $rename_thumb){
            unlink('../uploaded_files/'.$old_thumb);
         }
      }
   }

   $old_pdf = $_POST['old_pdf'];
$old_pdf = filter_var($old_pdf, FILTER_SANITIZE_STRING);
$pdf = $_FILES['video']['name'];
$pdf = filter_var($pdf, FILTER_SANITIZE_STRING);
$pdf_ext = pathinfo($pdf, PATHINFO_EXTENSION);
$rename_pdf = unique_id().'.'.$pdf_ext;
$pdf_tmp_name = $_FILES['video']['tmp_name'];
$pdf_folder = '../uploaded_files/'.$rename_pdf;


   if(!empty($pdf)){
      $update_pdf = $conn->prepare("UPDATE `paper` SET pdf = ? WHERE id = ?");
      $update_pdf->execute([$rename_pdf, $paper_id]);
      move_uploaded_file($pdf_tmp_name, $pdf_folder);
      if($old_pdf != '' AND $old_pdf != $rename_pdf){
         unlink('../uploaded_files/'.$old_pdf);
      }
   }

   $message[] = 'paper updated!';

}

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update paper</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>
   
<section class="video-form">

   <h1 class="heading">update paper</h1>

   <?php
      $select_pdf = $conn->prepare("SELECT * FROM `paper` WHERE id = ? AND tutor_id = ?");
      $select_pdf->execute([$get_id, $tutor_id]);
      if($select_pdf->rowCount() > 0){
         while($fecth_pdf = $select_pdf->fetch(PDO::FETCH_ASSOC)){ 
            $paper_id = $fecth_pdf['id'];
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="paper_id" value="<?= $fecth_pdf['id']; ?>">
      <input type="hidden" name="old_thumb" value="<?= $fecth_pdf['thumb']; ?>">
      <input type="hidden" name="old_pdf" value="<?= $fecth_pdf['pdf']; ?>">
      <p>update status <span>*</span></p>
      <select name="status" class="box" required>
         <option value="<?= $fecth_pdf['status']; ?>" selected><?= $fecth_pdf['status']; ?></option>
         <option value="active">active</option>
         <option value="deactive">deactive</option>
      </select>
      <p>update title <span>*</span></p>
      <input type="text" name="title" maxlength="100" required placeholder="enter paper title" class="box" value="<?= $fecth_pdf['title']; ?>">
     
        <img src="../uploaded_files/<?= $fecth_pdf['thumb']; ?>" alt="">
      <p>update thumbnail</p>
      <input type="file" name="thumb" accept="image/*" class="box">
      <!-- <video src="../uploaded_files/<?= $fecth_pdf['pdf']; ?>" controls></video> -->
      <iframe src="../uploaded_files/<?= $fecth_pdf['pdf']; ?>" width="100%" height="300px"></iframe>

      <p>update paper</p>
      <!-- <input type="file" name="video" accept="video/*" class="box"> -->
      <input type="file" name="video" accept=".pdf" required class="box">
      <input type="submit" value="update content" name="update" class="btn">
      <div class="flex-btn">
         <a href="view_content.php?get_id=<?= $paper_id; ?>" class="option-btn">view content</a>
         <input type="submit" value="delete content" name="delete_video" class="delete-btn">
      </div>
   </form>
   <?php
         }
      }else{
         echo '<p class="empty">paper not found! <a href="add_paper.php" class="btn" style="margin-top: 1.5rem;">add videos</a></p>';
      }
   ?>

</section>


<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>