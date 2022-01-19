<?php
include 'hlavickaAdmin.php';
include 'ostatne.php';
//include 'navbarAdmin.php';

  $mysqli= new mysqli("localhost","kbsk","FNiyZeesaAlze0mp","demo4c");
 

  $sql = 'SELECT * FROM prispevky';
  $result = $mysqli->query($sql);

session_start();
if(isset($_SESSION['user'])) {
 if( isset($_GET['delete'])){

			$id = $_GET['delete'];

			$sql = "DELETE FROM prispevky WHERE id=$id";
			$mysqli->query($sql);
			header("Location: prihlaseny.php?page=blog");
		}
?>
	<body class="bg-white">

	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
	<a class="navbar-brand text-white" href="#">Administrácia:</a>

	  <div class="collapse navbar-collapse " id="navbarNavSupportedContent">
	  <ul class="navbar-nav ml-auto">

<?php
	$aktivnaStranka = basename(dirname($_SERVER['SCRIPT_NAME']));
	$riadky = file('../admin/menuAdmin.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES); 

	foreach ($riadky as  $riadok) {
	  list($k,$h) = explode('::', $riadok); 
	  $menu[$k] = $h;
	}
foreach ($menu as $odkaz => $hodnota) {
	  echo  '<li class="nav-item">
		  <a class="nav-link  '.($aktivnaStranka == $odkaz? 'active':'').'" href="' .$odkaz. '.php">'.$hodnota.'</a>
		</li>';
	}
 ?>  
</ul>
	  </div>
	</nav>
  <div class="row">
    <div class="col-md-2">
      	<div class="d-flex flex-column flex-shrink-0 p-3 text-dark bg-dark" style="height: 900px; width: 200px;">
		  <img src="https://icon-library.com/images/icon-user/icon-user-13.jpg" alt="" class="img-thumbnail rounded-circle mx-auto d-block w-85">
          <h2 style="color: white;"><?php echo $_SESSION["user"]; ?></h2>
            <h5 style="color: grey;"><?php echo $_SESSION["role"]; ?></h5>
              <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
              <img src="" alt="">
              </a>
              <hr>
              <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                  <a href="prihlaseny.php" class="nav-link  text-white" aria-current="page">
                    Domov
                  </a>
                </li>
                
                <li class="pb-3">
                  <a href="prihlaseny.php?page=blog" class="nav-link text-white">
                    Blog
                  </a>
                </li>
                <li>
                  <a href="odhlasenie.php" class="nav-link btn btn-dark text-white">
                    Odhlásiť sa
                  </a>
                </li>
              </ul>
            <div>
          </div>
    </div>
    </div>
    <div class="col-md-10 pt-5 pb-5">
      <?php
        if (isset($_GET['page']) && $_GET['page'] == 'blog') {
      ?>
          <table class="mt-2 table table-striped">
            <thead>
              <tr>
                <th scope="col">Meno</th>
                <th scope="col">Text</th>
                <th scope="col">Čas</th>
                <th scope="col"></th>
              </tr>
            </thead>
              <tbody>
            <?php
			 
			while($row = $result->fetch_assoc()) {	?>
              <tr>
                <td><?= $row["meno"] ?></td>
                <td><?= prelozBBCode($row["prispevok"]) ?></td>
                <td><?= $row["cas"] ?></td>
          <td><a type="button" data-bs-id="<?= $row["id"] ?>" data-bs-name="<?= $row["meno"] ?>" class="btn btn-dark text-white" data-bs-toggle="modal" data-bs-target="#deleteModal">Zmazať</a></td>
    </tr>
  <?php } ?>
    </tbody>
  </table>

  <div class="modal fade m" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Zmazať článok?</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Naozaj chcete zmazať článok od autora: <span id="spanName"></span>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zavrieť</button>
          <a id="btn-remove" class="btn btn-dark" data-bs-target="#deleteModal">Zmazať</a>
        </div>
      </div>
    </div>
  </div>


  <script>
    var myModal = document.getElementById('deleteModal')
    myModal.addEventListener('shown.bs.modal', function (event) {
        let BtnClick = event.relatedTarget;

        let id = BtnClick.getAttribute('data-bs-id');

        let Name = BtnClick.getAttribute('data-bs-name');
        document.getElementById('spanName').innerHTML = Name;

        let link = "?page=blog&delete="+id;

        document.getElementById('btn-remove').href = link;
    })

  </script>
      <?php
        }else{
      ?>
        <p>Domov</p>
      <?php
        }
      ?>
    </div>
  </div>



<?php
}
else{
	header("Location: index.php");
}
include 'pataAdmin.php';
?>
