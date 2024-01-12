<?php
include '../components/connect.php';

if (isset($_COOKIE['tutor_id'])) {
    $tutor_id = $_COOKIE['tutor_id'];
} else {
    $tutor_id = '';
    header('location:login.php');
}

if (isset($_GET['get_id'])) {
    $get_id = $_GET['get_id'];
} else {
    $get_id = '';
    header('location:paper.php');
}

if (isset($_POST['delete_video'])) {
    // Your existing code to delete a video
}

if (isset($_POST['delete_comment'])) {
    // Your existing code to delete a comment
}

// Add code here to retrieve the PDF file path from your database for the specific paper
$select_pdf_query = $conn->prepare("SELECT pdf FROM `books` WHERE id = ? AND tutor_id = ?");
$select_pdf_query->execute([$get_id, $tutor_id]);

$fetch_pdf = $select_pdf_query->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Your head section with CSS and other resources -->
</head>

<body>

    <section class="view-content">

        <div class="container">
            <!-- Display the PDF file using an iframe -->
            <iframe src="../uploaded_files/<?= $fetch_pdf['pdf']; ?>" class="pdf-iframe" style="width: 100%; height: 100vh;"></iframe>

            <!-- Your existing code for other content details, likes, comments, etc. -->

        </div>

    </section>

    <section class="comments">
        <!-- Your existing code for comments display -->
    </section>

    <!-- <?php include '../components/footer.php'; ?> -->

    <script src="../js/admin_script.js"></script>

</body>

</html>
