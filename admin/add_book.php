<?php

include '../components/connect.php';

if (isset($_COOKIE['tutor_id'])) {
    $tutor_id = $_COOKIE['tutor_id'];
} else {
    $tutor_id = '';
    header('location:login.php');
}

if (isset($_POST['submit'])) {

    $id = unique_id();
    $status = $_POST['status'];
    $status = filter_var($status, FILTER_SANITIZE_STRING);
    $title = $_POST['title'];
    $title = filter_var($title, FILTER_SANITIZE_STRING);

    $thumb = $_FILES['thumb']['name'];
    $thumb = filter_var($thumb, FILTER_SANITIZE_STRING);
    $thumb_ext = pathinfo($thumb, PATHINFO_EXTENSION);
    $rename_thumb = unique_id() . '.' . $thumb_ext;
    $thumb_size = $_FILES['thumb']['size'];
    $thumb_tmp_name = $_FILES['thumb']['tmp_name'];
    $thumb_folder = '../uploaded_files/' . $rename_thumb;

    $pdf = $_FILES['video']['name']; // Corrected the file field name to 'video'
    $pdf = filter_var($pdf, FILTER_SANITIZE_STRING);
    $pdf_ext = pathinfo($pdf, PATHINFO_EXTENSION);
    $rename_pdf = unique_id() . '.' . $pdf_ext;
    $pdf_tmp_name = $_FILES['video']['tmp_name']; // Corrected the file field name to 'video'
    $pdf_folder = '../uploaded_files/' . $rename_pdf;

    if ($thumb_size > 2000000) {
        $message[] = 'Image size is too large!';
    } else {
        $add_booksection = $conn->prepare("INSERT INTO `books` (id, tutor_id, title, pdf, thumb, status) VALUES (?,?,?,?,?,?)");
        $add_booksection->execute([$id, $tutor_id, $title, $rename_pdf, $rename_thumb, $status]);
        move_uploaded_file($thumb_tmp_name, $thumb_folder);
        move_uploaded_file($pdf_tmp_name, $pdf_folder);
        $message[] = 'New Book uploaded!';
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

<section class="video-form">

    <h1 class="heading">Upload Books</h1>

    <form action="" method="post" enctype="multipart/form-data">
        <p>Book Status <span>*</span></p>
        <select name="status" class="box" required>
            <option value="" selected disabled>-- Select Status</option>
            <option value="active">Active</option>
            <option value="deactive">Deactive</option>
        </select>
        <p>Book Title <span>*</span></p>
        <input type="text" name="title" maxlength="100" required placeholder="Enter PDF Title" class="box">
        <p>Select Thumbnail <span>*</span></p>
        <input type="file" name="thumb" accept="image/*" required class="box">
        <p>Select Book (PDF) <span>*</span></p>
        <input type="file" name="video" accept=".pdf" required class="box">
        <input type="submit" value="Upload Book" name="submit" class="btn">
    </form>

</section>

<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>
