<?php
include 'hlavickaAdmin.php';
//include 'navbarAdmin.php';
session_start();
?>

<?php
			  if(isset($_POST['signOut'])){
				  unset($_SESSION['user']);
				  unset($_SESSION['role']);
				  header('Location: index.php');
			  }
			?>

	<body style="background-color:white;">

	<nav class="navbar navbar-expand-lg navbar-light bg-info">
	<a class="navbar-brand text-white" href="#">Administracia:</a>
	  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
	    <span class="navbar-toggler-icon"></span>
	  </button>
	  <div class="collapse navbar-collapse " id="navbarNavSupportedContent">
	  </div>
	</nav>
	<div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-info" style="width: 280px;height: 1000px;">
	<img src="https://icon-library.com/images/icon-user/icon-user-13.jpg" alt="" class="img-thumbnail rounded-circle mx-auto d-block w-75">
	<h2 style="color: white;"><?php echo $_SESSION["user"]; ?></h2>
	  <h5 style="color: silver;"><?php echo $_SESSION["role"]; ?></h5>
	    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
	     <img src="" alt="">
	    </a>
	    <hr>
	    <ul class="nav nav-pills flex-column mb-auto">
	      <li class="nav-item">
	        <a href="../public/theme/domov/index.php" class="nav-link  text-white" aria-current="page">
	          Domov
	        </a>
	      </li>
	      <li>
	        <a href="../public/theme/spravy/index.php" class="nav-link text-white">
	          Správy
	        </a>
	      </li>
	      <li>
	        <a href="../public/theme/onas/index.php" class="nav-link text-white">
	          O nás
	        </a>
	      </li>
	      <li>
	        <a href="../public/theme/galeria/index.php" class="nav-link text-white">
	          Fotogaléria
	        </a>
	      </li>
	      <li>
	        <a href="../public/theme/blog/index.php" class="nav-link text-white">
	          Blog
	        </a>
	      </li>
	      <li>
	        <a href="../public/theme/kontakt/index.php../public/theme/blog/index.php" class="nav-link text-white">
	          Menu
	        </a>
	      </li>
	    </ul>
		<div>
		<ul class="navbar-nav ml-auto">
		<form method="post">
            <input type="submit" value="Odhlásiť sa" name="signOut" style="margin-right:auto; margin-left:auto;
            display:block; margin-bottom:5%">
        </form>
		
	    </ul>
	</div>
	</div>

<?php
include 'pataAdmin.php';
?>
