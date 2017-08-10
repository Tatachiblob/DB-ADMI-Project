<?php
session_start();
require_once ('../mysqlConnect.php');
if(!isset($_SESSION['isLogin'])){
    header("Location: login.php");
}
if(!isset($_POST['custID'])){
	header("Location: rentMov.php");
}
$checkerStatus = false;
$msg = "";

$empStoreID = $_SESSION['storeId'];
$staffID = $_SESSION['staffId'];
$finalPrice = 0.00;

$custID = $_POST['custID'];
$custcheckSQL = "SELECT R.CUSTOMER_ID, COUNT(R.CUSTOMER_ID) AS NoRented,C.STORE_ID,CONCAT(C.LAST_NAME, ', ', C.FIRST_NAME) AS CFNAME
					FROM 	RENTAL R
					JOIN	CUSTOMER C
					ON		R.CUSTOMER_ID = C.CUSTOMER_ID
					WHERE	return_date IS NULL
					AND		R.customer_id = $custID;";

$custcheckRS = mysqli_query($conn, $custcheckSQL);

//------------------PAYMENT POST----------------------//
if(isset($_POST['action'])){
  if($_POST['action'] == 'makePayment'){
		$arrayOfInserts = $_SESSION["InsertsToRental"];
	  $custFullName = $_POST['custFullName'];
	  $custStoreID = $_POST['custStoreID'];
	  $arrayrentalIDs = $_SESSION["rentalIDs"];
	  $custFinalPayment = $_POST['custFinalPayment'];
	  $finalPrice = $_POST['finalPrice'];
	  echo "final: " . $finalPrice . " custFinalPayment: " . $custFinalPayment;
	  if($custFinalPayment != $finalPrice){
		 $msg .= "ERROR: Payment is below or above the final price. <br> Final price: $finalPrice <br> <input type='hidden' name='identifier' id='identifier' value='1'><input type='hidden' name='finalPrice' id='finalPrice' value='1'>";
	  }
	  else{
			foreach ($arrayOfInserts as $thisQuery) {
			$niwax = mysqli_query($conn,$thisQuery);
			if($niwax == FALSE){
				$msg .= "ERROR: " .mysqli_error($conn)."</br>";
			}
		}

			foreach ($arrayrentalIDs as $thisID) {
			$custcheckSQL2 = "SELECT F.RENTAL_RATE, R.CUSTOMER_ID
							FROM RENTAL R JOIN INVENTORY I
							ON R.INVENTORY_ID = I.INVENTORY_ID
							JOIN FILM F ON I.FILM_ID = F.FILM_ID WHERE R.RENTAL_ID = $thisID;";

			$custcheckRS2 = mysqli_query($conn, $custcheckSQL2);
			while($row = mysqli_fetch_array($custcheckRS2, MYSQLI_ASSOC)){
				$thisPrice = $row['RENTAL_RATE'];
				$thiscustID = $row['CUSTOMER_ID'];

				$updateStatement = mysqli_query($conn, "INSERT INTO PAYMENT (CUSTOMER_ID,STAFF_ID,RENTAL_ID,AMOUNT,PAYMENT_DATE) VALUES($thiscustID,$staffID,$thisID,$thisPrice,NOW());");
				if($updateStatement == TRUE){
					$msg .= "Payment accepted. <br>";
				}
				else{
					$msg .= "Something went wrong with the payment process. <br>";
				}
			} //while sqli end
		} // foreach end`
	  }
  }
}
//------------------PAYMENT POST----------------------//
else{
//------------------CHECKING SECTION------------------//
while($row = mysqli_fetch_array($custcheckRS, MYSQLI_ASSOC)){
	$custFullName = $row['CFNAME'];
	$custStoreID = $row['STORE_ID'];
	$arraySQLs = array();
	$numOfRents = $_POST['numEntries'];

	if((($row['NoRented']) + $numOfRents) > 3){
		$checkerStatus = false;
		$msg .= "Cannot process transaction: Customer has exceeded the number of rents. <br>";
	}
	else{
		$checkerStatus = true;
		echo "JAYZ0";
	}
	echo "CHECKER AT THIS POINT: " . $checkerStatus;
	for($i = 0;$checkerStatus == true && $i < $numOfRents; $i++){
		$inventoryID = $_POST['inventoryID_' . $i];
		$query = "SELECT I.INVENTORY_ID, I.FILM_ID, I.STORE_ID, I.STATUS,F.RENTAL_RATE FROM INVENTORY I JOIN FILM F ON I.FILM_ID = F.FILM_ID WHERE I.INVENTORY_ID = $inventoryID";
		$queryRS = mysqli_query($conn, $query);
		echo "PUTAEMPSTOREID: " . $empStoreID;

		$rentExistCheckQuery = "SELECT RENTAL_ID,INVENTORY_ID, CUSTOMER_ID FROM RENTAL WHERE INVENTORY_ID = 2047 AND CUSTOMER_ID = $custID AND RETURN_DATE IS NULL;";
		$rentExistCheckRS = mysqli_query($conn, $rentExistCheckQuery);

		while($row = mysqli_fetch_array($queryRS, MYSQLI_ASSOC)){
			$currFilmStoreID = $row['STORE_ID'];
			$currFilmStatus = $row['STATUS'];
			$currFilmInventoryID = $row['INVENTORY_ID'];
			$currFilmID = $row['FILM_ID'];
			if($currFilmStoreID != $empStoreID){
				$msg .= "Inventory ID $currFilmInventoryID is not available for this store <br>";
				$checkerStatus = false;
				echo "CHECKER AT THIS POINT1: " . $checkerStatus;
			}
			else{
				$checkerStatus = true;
				echo "JAYZ1";
			}
			if($checkerStatus == true && $currFilmStatus != 2){
				$msg .= "Inventory ID $currFilmInventoryID is not available for renting. <br>";
				$checkerStatus = false;
				echo "CHECKER AT THIS POINT2: " . $checkerStatus;
			}
			else{
				$checkerStatus = true;
				echo "JAYZ2";
			}
			if($checkerStatus == true && (mysqli_fetch_array($custcheckRS, MYSQLI_ASSOC)) != NULL){
				$msg.= "Inventory ID $currFilmInventoryID is already rented by user $custID <br>";
				$checkerStatus = false;
				echo "CHECKER AT THIS POINT3: " . $checkerStatus;
			}
			else{
				$checkerStatus = true;
				echo "JAYZ3";
			}
			if($checkerStatus == true){
				$finalPrice += $row['RENTAL_RATE'];
				ECHO "puta";
				array_push($arraySQLs, "INSERT INTO RENTAL(INVENTORY_ID,CUSTOMER_ID,STAFF_ID) VALUES(" . $inventoryID . "," . $custID . "," . $staffID . ")");
			}
		}

	}
	if($checkerStatus == true){
		$arrayawesome = array();
		$_SESSION["InsertsToRental"] = array();
		$_SESSION["InsertsToRental"] = $arraySQLs;
		  $k = 1;
			foreach ($arraySQLs as $thisQuery) {
				echo "Query added: " . $thisQuery . "<br>";

				$maxRS = mysqli_query($conn, "SELECT MAX(RENTAL_ID) AS ID FROM RENTAL;");
				$maxrow = mysqli_fetch_array($maxRS, MYSQLI_ASSOC);
				$maxrow = ($maxrow['ID']) + $k;
				array_push($arrayawesome,$maxrow);
				$k++;
			}
			$_SESSION["rentalIDs"] = array();
			$_SESSION["rentalIDs"] = $arrayawesome;
			$msg .= "Rent placed. <br> ";
			$msg .= "Final Price: " . $finalPrice . " <br> <input type='hidden' name='identifier' id='identifier' value='1'>";
		}else{
			echo "BOBO";
		}


}
}
//------------------CHECKING SECTION------------------//

$storeSQL = 'SELECT S.STORE_ID, C.CITY FROM STORE S JOIN ADDRESS A ON S.ADDRESS_ID = A.ADDRESS_ID JOIN CITY C ON A.CITY_ID = C.CITY_ID;';
$storeRS = mysqli_query($conn, $storeSQL);
$rentingcustRS = null;
$storeCity = null;


?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Movie Rent Page</title>
<?php include '..\/styleInclude.html';?>
</head>
<body>
<div id="wrapper">
<?php include 'navbarInclude.html';?>
<div id="page-wrapper">
<div class="row">
  <div class="col-lg-12">
    <h1 class="page-header">Rent Movie -> Payment</h1>
  </div><!--/.col-lg-12-->
</div><!--/.row-->

</br>
<div class="row">
    <div class="col-lg-10">
		<?php
		echo	"<h4> Customer Name: $custFullName </h4>
				<h4>Customer Store ID: $custStoreID</h4>

				";
		if($msg !== ""){
			echo "<strong>System: " . $msg . " </strong>";
		}
		?>
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="paymentForm">
			<input type='hidden' name='action' value='makePayment'>
			 <div class='input-group col-md-4'>
				<span class='input-group-addon'><i class='glyphicon glyphicon-usd'></i></span>
				<input id='custFinalPayment' type='number' class='form-control' name='custFinalPayment' step="0.01" placeholder='Customer Payment' disabled>
			 </div>
			 <?php echo "<input type='hidden' step='0.01' name='finalPrice' id='finalPrice' value='" . $finalPrice . "'>
						<input type='hidden' name='custFullName' id='custFullName' value='" . $custFullName . "'>
						<input type='hidden' name='custStoreID' id='custStoreID' value='" . $custStoreID . "'>
						<input type='hidden' name='custID' id='custID' value='" . $custID . "'>";
			 ?>
			 <br><input type="submit" class="btn btn-primary" id="sbBUTT" value="Submit" disabled>
		</form>


    </div>
</div><!-- /.row -->

</div><!--/#page-wrapper-->
</div><!--/#wrapper-->
</body>
<script>
$(document).ready(function(){
  $('#CustomersTable').DataTable({
    responsive: true
  });

  if(($("#identifier").attr("value")) == '1'){
	  $("#custFinalPayment").removeAttr("disabled");
		$("#sbBUTT").removeAttr("disabled");
  }

});


</script>
</html>
