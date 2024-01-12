<?php

include '../components/connect.php';

if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:login.php');
}


if (isset($_POST['delete_pdf'])) {
    $delete_id = $_POST['pdf_id'];
    $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

    $verify_pdf = $conn->prepare("SELECT * FROM `paper` WHERE id = ? LIMIT 1");
    $verify_pdf->execute([$delete_id]);

    if ($verify_pdf->rowCount() > 0) {
        $delete_pdf_info = $conn->prepare("SELECT thumb, pdf FROM `paper` WHERE id = ? LIMIT 1");
        $delete_pdf_info->execute([$delete_id]);
        $pdf_info = $delete_pdf_info->fetch(PDO::FETCH_ASSOC);

        $thumb = $pdf_info['thumb'];
        $pdf = $pdf_info['pdf'];

        // Delete the thumb and pdf files
        if (!empty($thumb)) {
            unlink('../uploaded_files/' . $thumb);
        }

        if (!empty($pdf)) {
            unlink('../uploaded_files/' . $pdf);
        }

        

        // Finally, delete the PDF record itself
        $delete_pdf = $conn->prepare("DELETE FROM `paper` WHERE id = ?");
        $delete_pdf->execute([$delete_id]);

        $message[] = 'PDF deleted!';
    } else {
        $message[] = 'PDF not found or has already been deleted!';
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>
   
<section class="contents">

   <h1 class="heading">your paper</h1>

   <div class="box-container">

   <div class="box" style="text-align: center;">
      <h3 class="title" style="margin-bottom: .5rem;">create new paper</h3>
      <a href="add_paper.php" class="btn">add paper</a>
   </div>

   <?php
      $select_pdf = $conn->prepare("SELECT * FROM `paper` WHERE tutor_id = ? ORDER BY date DESC");
      $select_pdf->execute([$tutor_id]);
      if($select_pdf->rowCount() > 0){
         while($fecth_pdf = $select_pdf->fetch(PDO::FETCH_ASSOC)){ 
            $pdf_id = $fecth_pdf['id'];
   ?>
      <div class="box">
         <div class="flex">
            <div><i class="fas fa-dot-circle" style="<?php if($fecth_pdf['status'] == 'active'){echo 'color:limegreen'; }else{echo 'color:red';} ?>"></i><span style="<?php if($fecth_pdf['status'] == 'active'){echo 'color:limegreen'; }else{echo 'color:red';} ?>"><?= $fecth_pdf['status']; ?></span></div>
            <div><i class="fas fa-calendar"></i><span><?= $fecth_pdf['date']; ?></span></div>
         </div>
         <img src="../uploaded_files/<?= $fecth_pdf['thumb']; ?>" class="thumb" alt="">
         <h3 class="title"><?= $fecth_pdf['title']; ?></h3>
         <form action="" method="post" class="flex-btn">
            <input type="hidden" name="pdf_id" value="<?= $pdf_id; ?>">
            <a href="update_paper.php?get_id=<?= $pdf_id; ?>" class="option-btn">update</a>
            <input type="submit" value="delete" class="delete-btn" onclick="return confirm('delete this pdf?');" name="delete_pdf">
         </form>
         <a href="view_paper.php?get_id=<?= $pdf_id; ?>" class="btn" target = "_blank">view paper</a>
      </div>
   <?php
         }
      }else{
         echo '<p class="empty">no contents added yet!</p>';
      }
   ?>

   </div>

</section>















<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>