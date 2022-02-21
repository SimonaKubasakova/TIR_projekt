<?php
include 'hlavickaAdmin.php';
include 'rozne.php';
//include 'navbarAdmin.php';
include 'database.php';

$mysqli -> set_charset("utf8");
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
 
 if( isset($_GET['delete2'])){

			$id = $_GET['delete2'];

			$sql = "DELETE FROM spravy WHERE id=$id";
			$mysqli->query($sql);
			header("Location: prihlaseny.php?page=spravy");
 }

 
?>
<div id="alertsuccess" class='alert alert-success alert-dismissible collapse' role='alert'>
    Prispevok bol úspešne odstránený!
    <button type='button' onclick="" id="hidebtn" class='btn-close' aria-label='Close' data-bs-dismiss="alert"></button>
</div>

	<body class="bg-white">

	<nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
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
      	<div class="d-flex flex-column flex-shrink-0 p-3 text-dark bg-secondary" style="height: 1100px; width: 200px;">
		  <img src="https://icon-library.com/images/icon-user/icon-user-13.jpg" alt="" class="img-thumbnail rounded-circle mx-auto d-block w-85">
          <h2 style="color: white;"><?php echo $_SESSION["user"]; ?></h2>
            <h5 style="color: silver;"><?php echo $_SESSION["role"]; ?></h5>
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
                <li>
                  <a href="prihlaseny.php?page=blog" class="nav-link text-white">
                    Blog
                  </a>
                </li>
                <li>
                <a href="prihlaseny.php?page=spravy" class="nav-link text-white">
                  Správy
                </a>
                </li>
                <li>
                  <a href="odhlasenie.php" class="nav-link btn btn-dark text-white" style="margin-top: 15%">
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
          <table class="mt-2 table table-striped" style="width: 80% margin:50%">
          
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
        } else if (isset($_GET['page']) && $_GET['page'] == 'spravy') {
        ?>
        <?php
        $sql = 'SELECT * FROM spravy';
        $result = $mysqli->query($sql);
      ?>
        <h4 style="text-align: center">Správy</h4>
        <div class="col-md-12 pt-1 pb-1">
         <table class="mt-2 table table-striped" style="">
         <h4 style="float:left ">Zoznam článkov</h4>
          <button class="btn btn-dark text-white" style="float:right " data-bs-toggle="modal" data-bs-target="#novyModal">Nový článok</button>
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Názov článku/Čas publikovania/Obsah</th>
                
                <th scope="col"></th>
                <th scope="col" >Publik</th>
                <th scope="col">Úpravy článku</th>
               
              </tr>
            </thead>
              <tbody>
            <?php
			 
			while($row = $result->fetch_assoc()) {	?>
             <tr style="width: 30px;">
                <td><?= $row["id"] ?></td>
                <td style="max-width: 500px;">
                <b><?= $row["nadpis"] ?></b>
                <i> Publikované <?= $row["cas"] ?></i>  <br>
                <img src="../public/theme/spravy/images/<?php echo $row['obrazok'] ?>" alt="" style="width: 100px; height: 60px; float: left;"><span class="d-inline-block text-truncate" style="max-width: 500px; padding-left: 30px;"><?php echo $row['obsah'] ?></span></td>
                <td></td>
                <td><div class="form-check" style="float:right">
                  <input type="checkbox" class="form-check-input" name="" id="" value="checkedValue" checked>
                </div></td>
                <td><a type="button"style="float:right" data-bs-id="<?= $row["id"] ?>" data-bs-autor="<?= $row["nadpis"] ?>"  data-bs-publ="<?= $row["cas"] ?>"  data-bs-prisp="<?= $row["obsah"] ?>"data-bs-obr="<?= $row["obrazok"] ?>" class="btn btn-dark text-white" data-bs-toggle="modal" data-bs-target="#nahladModal">Náhľad</a></td>
                <td><a type="button" style="float:right" data-bs-id="<?= $row["id"] ?>" data-bs-name="<?= $row["nadpis"] ?>"  data-bs-obsah="<?= $row["obsah"] ?>"  data-bs-cas="<?= $row["cas"] ?>"data-bs-img="<?= $row["obrazok"] ?>" class="btn btn-dark text-white" data-bs-toggle="modal" data-bs-target="#deleteModal2">Zmazať</a></td>
          
          <td><a type="button"class="btn btn-dark text-white" style="float:right">Upraviť</td>
        </tr>
            </div>
              
  <?php } ?>
    </tbody>
  </table>

  <div  class="modal fade m" id="deleteModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Naozaj chceš zmazať článok?</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
         <b><span id="spanName"></span></b> <br>
         <i><span id="spanCas"></span></i> <br> <br>
         <span id="spanObsah"></span>
         <span id="spanImg"></span>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zavrieť</button>
          <a id="btn-remove" class="btn btn-dark" data-bs-target="#deleteModal2">Zmazať</a>
        </div>
      </div>
    </div>
  </div>


  <script>
    var myModal = document.getElementById('deleteModal2')
    myModal.addEventListener('shown.bs.modal', function (event) {
        let BtnClick = event.relatedTarget;

        let id = BtnClick.getAttribute('data-bs-id');

        let Name = BtnClick.getAttribute('data-bs-name');
        document.getElementById('spanName').innerHTML = Name;

        let Obsah = BtnClick.getAttribute('data-bs-obsah');
        document.getElementById('spanObsah').innerHTML = Obsah;

        let Cas = BtnClick.getAttribute('data-bs-cas');
        document.getElementById('spanCas').innerHTML = Cas;

        let Img = BtnClick.getAttribute('data-bs-img');
        document.getElementById('spanImg').innerHTML = Img;

        let link = "?page=spravy&delete2="+id;

        document.getElementById('btn-remove').href = link;
    })

  </script>
  <div  class="modal fade m" id="nahladModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Náhľad článku</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
         <b><span id="spanAutor"></span></b>
         <i><span id="spanPubl"></span></i> <br> <br>
         <span id="spanPrisp"></span>
         <span id="spanObr"></span>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zavrieť</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    var myModal = document.getElementById('nahladModal')
    myModal.addEventListener('shown.bs.modal', function (event) {
        let BtnClick = event.relatedTarget;

        let id = BtnClick.getAttribute('data-bs-id');

        let Autor = BtnClick.getAttribute('data-bs-autor');
        document.getElementById('spanAutor').innerHTML = Autor;

        let Pripevok = BtnClick.getAttribute('data-bs-prisp');
        document.getElementById('spanPrisp').innerHTML = Prispevok;

        let Publ = BtnClick.getAttribute('data-bs-publ');
        document.getElementById('spanPubl').innerHTML = Publ;

        let Obr = BtnClick.getAttribute('data-bs-obr');
        document.getElementById('spanObr').innerHTML = Obr;

    })

  </script>
  <div  class="modal fade m" id="novyModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Nový článok</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zavrieť</button>
        </div>
      </div>
    </div>
  </div>

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