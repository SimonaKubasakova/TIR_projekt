<?php
include 'hlavickaAdmin.php';
include 'rozne.php';
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
    if(!(isset($_SESSION["user"]))){
      header('Location: index.php');
  }
  if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
  }
    
?>
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

<div id="Alertsuccess" class='alert alert-success alert-dismissible collapse' role='alert'>
    Prispevok bol úspešne odstránený!
    <button type='button' onclick="" id="hidebtn" class='btn-close' aria-label='Close' data-bs-dismiss="alert"></button>
</div>
          <table class="mt-2 table table-striped">
            <thead>
              <tr >
                <th scope="col">Meno</th>
                <th scope="col">Text</th>
                <th scope="col">Čas</th>
                <th scope="col"></th>
              </tr>
            </thead>
              <tbody>
            <?php while($row = $result->fetch_assoc()) {  ?>
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
          <a id="btn-remove" class="btn btn-dark text-white" data-bs-target="#deleteModal">Zmazať</a>
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

    if (sessionStorage.getItem('deleteModal') == 'true'){
                  document.getElementById('Alertsuccess').className = 'alert alert-success alert-dismissible';
                 

                }

  </script>
  

<div class="col-md-8 pt-5 pb-5">
<?php
        } else if (isset($_GET['page']) && $_GET['page'] == 'spravy') {
?>
          <div id="alertsuccess" class='alert alert-success alert-dismissible collapse' role='alert'>
    Prispevok bol úspešne odstránený!
    <button type='button' onclick="" id="hidebtn" class='btn-close' aria-label='Close' data-bs-dismiss="alert"></button>
</div>

    <textarea hidden id="lab" cols="30" rows="1" name="label"></textarea>
    <textarea hidden id="id" cols="30" rows="1"></textarea>

    <h4  class="text-center ">Správy</h4>  



        
<div> 

<div class="col-md-12 pt-1 pb-1">

          <table class="table table-striped" style="width: 100">
          <h4 style="float:left ">Zoznam článkov</h4>
            <button class="btn btn-dark text-white" style="float:right " data-bs-toggle="modal" data-bs-target="#novyModal">Nový článok</button>

            
            <thead>
            <tr>
                <th>#</th>
                <th>Názov Článku / Obsah / Čas publikovania</th>
                <th>Úpravy článku   </th>
            </tr>
            </thead>
            <tbody>
            <?php
            $sql = "SELECT * FROM spravy";

            $result = $conn->query($sql);
         
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    ?>
                        <tr>
                        <td style="text-align: center; vertical-align: middle;"><p style="font-weight: bold;"><?php echo $row['id'] ?></p></td>
                        <td><p style="font-weight: bold;"><?php echo $row['nadpis'] ?></p>
                         <img src="../public/theme/spravy/images/<?php echo $row['obrazok'] ?>" alt="" style="width: 100px; height: 70px; float: left;"> <span class="d-inline-block text-truncate" style="max-width: 800px; padding-left: 30px;"><?php echo $row['obsah'] ?></span></td>
                        <td style='text-align: center; vertical-align: middle;'>
                            <button type='button' class='btn btn-dark text-white' data-bs-toggle='modal' data-bs-target='#view-modal' onclick="document.getElementById('lab').innerHTML=JSON.stringify({id: '<?php echo $row['id'] ?>', nadpis: '<?php echo $row['nadpis'] ?>', cas: '<?php echo $row['cas'] ?>', obsah: '<?php echo preg_replace('/\r|\n/', '', $row['obsah']); ?>', obrazok: '<?php echo $row['obrazok'] ?>'}); document.getElementById('nadpis-view').innerHTML=(JSON.parse(document.getElementById('lab').innerHTML)).nadpis; document.getElementById('cas-view').innerHTML=(JSON.parse(document.getElementById('lab').innerHTML)).cas; document.getElementById('obsah-view').innerHTML=(JSON.parse(document.getElementById('lab').innerHTML)).obsah; document.getElementById('image-view').src='../public/theme/spravy/images/' + (JSON.parse(document.getElementById('lab').innerHTML)).obrazok;"><i class='bi bi-file-text-fill' ></i> Náhľad</button>
                        <button type='button' class='btn btn-dark text-white' data-bs-toggle='modal' data-bs-target='#edit-modal'><i class='bi bi-pencil-fill'></i> Úprava</button>
                        
                        <button type="button" class="btn btn-dark text-white" data-bs-toggle="modal" data-bs-target="#remove-modal" onclick="document.getElementById('lab').innerHTML=JSON.stringify({id: '<?php echo $row['id'] ?>', nadpis: '<?php echo $row['nadpis'] ?>', cas: '<?php echo $row['cas'] ?>', obsah: '<?php echo preg_replace('/\r|\n/', '', $row['obsah']); ?>', obrazok: '<?php echo $row['obrazok'] ?>'}); document.getElementById('nadpis').innerHTML=(JSON.parse(document.getElementById('lab').innerHTML)).nadpis; document.getElementById('cas').innerHTML=(JSON.parse(document.getElementById('lab').innerHTML)).cas; document.getElementById('obsah').innerHTML=(JSON.parse(document.getElementById('lab').innerHTML)).obsah; document.getElementById('image').src='../public/theme/spravy/images/' + (JSON.parse(document.getElementById('lab').innerHTML)).obrazok;
                        document.getElementById('nadpis-view').innerHTML=(JSON.parse(document.getElementById('lab').innerHTML)).nadpis; document.getElementById('cas-view').innerHTML=(JSON.parse(document.getElementById('lab').innerHTML)).cas; document.getElementById('obsah-view').innerHTML=(JSON.parse(document.getElementById('lab').innerHTML)).obsah; document.getElementById('image-view').src='../public/theme/spravy/images/' + (JSON.parse(document.getElementById('lab').innerHTML)).obrazok;"><i class="bi bi-trash3-fill"></i> Zmazať</button></td>
                        
                      </tr>
                    <?php
                }
              } else {
              }
              ?>  
            </div>
                            <div class="modal fade " id="view-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdrop" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="staticBackdropLabel">Náhľad článku</h5>
                      <button type="button" class="btn-close " data-bs-dismiss="modal"></button>
                      </button>
                    </div>
                    <div class="modal-body">
                    <div>
                        <p id="nadpis-view" style="font-weight: bold;"></p>
                        <i class="bi bi-clock">  </i><i id="cas-view" style="font-size: 12px;"></i>
                        </div>
                    <div>
                        <img id="image-view" src="" alt="" style="width: 200px; height: 150px; float: left; padding-right: 20px;">
                        <p id="obsah-view" style="font-size: 12px;"></p>
                    </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark text-white " data-bs-dismiss="modal">Zavrieť</button>
                    </div>
                  </div>
                </div>
              </div>

              <div class="modal fade" id="edit-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdrop" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="staticBackdropLabel">Úprava článku</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </button>
                    </div>
                    <div class="modal-body">
                <div class="row">
                    
                    <div>
                        <label for="basic-url" class="form-label">Nadpis: </label>
                        <input type="text" style="width:800px;" name="meno" id=editNadpis" class="form-control" placeholder="" value="">
                       
                        <label for="basic-url" class="form-label">Článok: </label>
                        <textarea class="form-control" aria-label="With textarea" id="editSprava" rows="6"><?php echo $row['obsah'] ?></textarea>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                
                <input type="hidden" name="sprava" id="edit_msg" value="">
                <input type="hidden" name="type" value="edit">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zavrieť</button>
                <button type="button" data-params="" id="editButton" class="btn btn-outline-dark" hidden>Upraviť</button>
                <button type="button" data-params="" id="pridatButton" class="btn btn-outline-dark">Pridať</button>
            </div>
                  </div>
                </div>
              </div>
              
              
              <div class="modal fade" id="novyModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdrop" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="staticBackdropLabel">Nový článok</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </button>
                    </div>
                    
                    <div class="modal-body">
                <div class="row">
                    
                    <div>
                        <label for="basic-url" class="form-label">Nadpis: </label>
                        <input type="text" style="width:800px;" name="meno" id="editNadpis" class="form-control" placeholder="Nadpis" value="">
                        <label for="basic-url" class="form-label">Článok: </label>
                        <textarea class="form-control" aria-label="With textarea" id="editSprava" rows="6"></textarea>
                    </div>
                </div>
            </div>
             <div class="m-3">
                 <label for="formFile" class="form-label">Pridať súbor</label>
                 <input class="form-control" type="file" id="formFile">
            </div>
            <div class="modal-footer">
                
                <input type="hidden" name="sprava" id="edit_msg" value="">
                <input type="hidden" name="type" value="edit">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zavrieť</button>
                <button type="button" data-params="" id="editButton" class="btn btn-outline-dark" hidden>Upraviť</button>
                <button type="button" data-params="" id="pridatButton" class="btn btn-outline-dark">Pridať</button>
            </div>
                  </div>
                </div>
              </div>

                           <div class="modal fade" id="remove-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdrop" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="staticBackdropLabel">Naozaj chceš vymazať článok?</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <p id="nadpis" style="font-weight: bold;"></p>
                            <i class="bi bi-clock">  </i><i id="cas" style="font-size: 12px;"></i>
                        </div>
                    <div>
                        <img id="image" src="" alt="" style="width: 200px; height: 150px; float: left; padding-right: 20px;">
                        <p id="obsah" style="font-size: 12px;"></p>
                    </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-dark text-white" data-bs-dismiss="modal">Zavrieť</button>
                      <form action="prihlaseny.php?page=spravy" method="post">
                        <button type="submit" class="btn btn-dark text-white" data-bs-dismiss="modal" name="zmazat" onclick="document.cookie='json_dat = ' + document.getElementById('lab').innerHTML;">Zmazať</button>
                      </form>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              
              <script>
                if (sessionStorage.getItem('wasRemoved') == 'true'){
                  document.getElementById('alertsuccess').className = 'alert alert-success alert-dismissible';
                  sessionStorage.setItem('wasRemoved', 'false');
                  document.cookie = 'json_dat = false; expires=Thu, 01 Jan 1970 00:00:01 GMT;';

                }
              </script>

              <?php
            if (isset($_POST['zmazat'])){

                $sql = "DELETE FROM spravy WHERE id = ".json_decode($_COOKIE['json_dat'])->id;

                if ($conn->query($sql)){
                    echo "<script>sessionStorage.setItem('wasRemoved', 'true');</script>";
                    echo "<script> document.location = 'prihlaseny.php?page=spravy'; </script>";
                    $conn->close();
                }
            }
  ?>
</div>
        

      <?php
        }else {
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