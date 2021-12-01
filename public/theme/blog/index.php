<?php 
date_default_timezone_get("Europe/Bratislava");
	include'../../assets/hlavicka.php';
	include'../../assets/navbar.php';
	include'../../assets/rozne.php';

?>
<?php 
$servername = "localhost";
$username = "admin";
$password = "heslo";
$dbname = "demo4c";
$conn = new mysqli("localhost", "kbsk", "FNiyZeesaAlze0mp", "demo4c");

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM prispevky";
$result = $conn->query($sql);

$meno ="";
$chyba = "";
$sprava ="";


if($_SERVER['REQUEST_METHOD'] == 'POST'){


if(kontrola($_POST['odpoved']) == $_POST['spravna_odpoved']){

$suborPrispevky = fopen('prispevky.csv', 'a');

$novyPrispevok = array();


$novyPrispevok[] = $_POST['pocet'] + 1;	
$novyPrispevok[] = kontrola($_POST['meno']);
$novyPrispevok[] = kontrola($_POST['sprava']);
$novyPrispevok[] = date('Y-m-d H:i:s',time());
header("Location: index.php");

fputcsv($suborPrispevky, $novyPrispevok, ';');
fclose($suborPrispevky);
}
else
{
 $chyba = "Nespravna odpoved na otazku";
 $meno = kontrola($_POST['meno']);
 $sprava = kontrola($_POST['sprava']);
}



 ?>

<?php  
if(!empty($chyba)){

?>	

<div class="alert alert-danger alert-dismissible fade show" role="alert">
 <strong> Ups! </strong> <?php echo $chyba; ?>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>

<?php }  ?>

<?php  
if(empty($chyba)){

?>	

<div class="alert alert-success alert-dismissible fade show" role="alert">
 <strong> Výborne </strong> <?php echo "Uspešne sme pridali váš názor" ?>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>

<?php } 

}
 ?>


<?php 



	$suborCaptcha = file('captcha.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	$antiSpam = [];

	for ($i=0; $i < count($suborCaptcha) ; $i+=2){

		$antiSpam[str_replace("odpoved: ","",$suborCaptcha[$i+1])] = str_replace("otazka: ","", $suborCaptcha[$i]);

	}


	$vybranyKluc = array_rand($antiSpam);
	$casy = [];
	$mena = [];
	$prispevky = [];

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$casy[] = $row["cas"];
			$mena[] = $row["meno"];
			$prispevky[] = $row["prispevok"];
		}
	  } else {
		echo "0 results";
	  }
	  $conn->close();
 ?>
<section>
	<h1 class="py-3 text-center">Blog na Tému: </h1>

	<div class="container">
		<form action="index.php"  method="post">
			<div class="form-group was-validated">
				<small id="emailHelp" class="form-text text-muted">Napište nam svoje meno</small>
				<input type="text" placeholder="Meno autora" class="form-control" required autocomplete="" 	pattern="\S.{4,19}" name="meno"  value="<?php echo $meno ?>"> 
				<div class="invalid-feedback">
					Prosim vyplnte túto položku. Zadajte 5-20 znakov!
				</div>
			</div>
		
			
			<div class="form-group was-validated ">
				 <small id="emailHelp" class="form-text text-muted">Povedzte nam svoj názor</small>
				<textarea name="sprava"  cols="98" rows="5" placeholder="Váš text" class="form-control" required><?php echo $sprava?></textarea>
			</div>
		
			<div class="form-group">
					 <small> Antispam: <?php echo $antiSpam[$vybranyKluc]  ?> </small> 
			 <div class="form-group was-validated">

			 	<div class="row" >	
						<div class="col-md-7"> <input type="text" placeholder="Odpoveď" class="form-control just" required name="odpoved" pattern="<?php echo $vybranyKluc ?>" > </div>
						<div class="col-md-4">
						 <button type="reset" class="btn btn-primary float-right">Vynulovať </button>
										
						</div>
						<div class="col-md-1">
							
					    <button type="submit" class="btn btn-primary float-right">Odoslať </button>
						</div>
					 
				</div>
				
		</div>
		
		<input type="hidden" name="spravna_odpoved" value="<?php echo $vybranyKluc ?>">
		<input type="hidden" name="pocet" value="<?php echo count($prispevky) ?>">
		</form>
	</div>

	<hr class="border-dark"> 
	<div class="container">
		<?php 
			for ($i = 0; $i < count($prispevky); $i++) {
				$datum = strtotime($casy[$i]);
				$datumTxt = date('j. ', $datum) .$mesiace[date('n', $datum) - 1]. date(' Y H:i', $datum); 
			
		 ?>	
		 <?php echo '<h4>'.$mena[$i],'</h4>'?>
			<small><i> Odoslane: <?php echo $datumTxt ?></i></small>
			<?php echo '<p>'.$prispevky[$i].'</p>' ?>
			<hr>
		<?php 
			}
		 ?>
	</div>
</section>

<?php 
include'../../assets/pata.php';
?>