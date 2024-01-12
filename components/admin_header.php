<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Document</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<?php
session_start();

if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

   
<header class="header">

   <section class="flex">

      <a href="dashboard.php" class="logo">Admin.</a>

      <form action="search_page.php" method="post" class="search-form">
         <input type="text" name="search" placeholder="search here..." required maxlength="100">
         <button type="submit" class="fas fa-search" name="search_btn"></button>
      </form>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="search-btn" class="fas fa-search"></div>
         <div id="user-btn" class="fas fa-user"></div>
         <div id="toggle-btn" class="fas fa-sun"></div>
      </div>

      <div class="profile">

      <img src="../uploaded_files/character.jpg" alt="">
      <h3><?php echo "$_SESSION[username]";echo "<br>";?> </h3>
    <a href="../index.html" onclick="return confirm('logout from this website?');" class="delete-btn">logout</a>
    
         
      </div>

   </section>

</header>

<!-- header section ends -->

<!-- side bar section starts  -->

<div class="side-bar">

   <div class="close-side-bar">
      <i class="fas fa-times"></i>
   </div>

   <div class="profile">
   <img src="../uploaded_files/character.jpg" alt="">
   <h3><?php echo "$_SESSION[username]";
        echo "<br>";
    ?> </h3>
         
      </div>

   <nav class="navbar">
      <a href="dashboard.php"><i class="fas fa-home"></i><span>home</span></a>
      <a href="playlists.php"><i class="fa-solid fa-bars-staggered"></i><span>playlists</span></a>
      <a href="contents.php"><i class="fa fa-youtube-play"></i><span>contents</span></a>
      <!-- <a href="papersection.php"><i class="fas fa-graduation-cap"></i><span>Paper section</span></a> -->
      <a href="paper.php"><i class="fas fa-edit"></i><span>Paper </span></a>
      <a href="book.php"><i class="fas fa-book-reader"></i><span>Books </span></a>
      <!-- <a href="comments.php"><i class="fas fa-comment"></i><span>comments</span></a> -->
      <a href="http://localhost/tech/index.php" onclick="return confirm('logout from this website?');"><i class="fas fa-right-from-bracket"></i><span>logout</span></a>
   </nav>

</div>

<!-- side bar section ends -->