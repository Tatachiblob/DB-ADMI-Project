<?php
session_start();
require_once ('../mysqlConnect.php');
if(!isset($_SESSION['isLogin'])){
    header("Location: login.php");
}
$storeSQL = 'SELECT S.STORE_ID, C.CITY FROM STORE S JOIN ADDRESS A ON S.ADDRESS_ID = A.ADDRESS_ID JOIN CITY C ON A.CITY_ID = C.CITY_ID;';
$storeRS = mysqli_query($conn, $storeSQL);
$rentingcustRS = null;
$storeCity = null;

if(isset($_POST['action'])){
  if($_POST['action'] == 'selectStore'){
    $storeID = $_POST['store'];
	$storeCitySQL = "SELECT C.CITY FROM STORE S JOIN ADDRESS A ON S.ADDRESS_ID = A.ADDRESS_ID JOIN CITY C ON A.CITY_ID = C.CITY_ID WHERE STORE_ID = $storeID;";
	$storeCityRS = mysqli_query($conn, $storeCitySQL);
	while($row = mysqli_fetch_array($storeCityRS, MYSQLI_ASSOC)){
		$storeCity = $row['CITY'];
	}
	$tablesql = "SELECT	R.CUSTOMER_ID, CONCAT(C.last_name, ', ', C.first_name) AS Thunder, COUNT(R.customer_id) AS NumberRented
				 FROM 	rental R
				 JOIN	customer C
				 ON		R.customer_id = C.customer_id
				 WHERE 	R.return_date IS NULL AND C.ACTIVE = 1 AND C.STORE_ID = $storeID
				 GROUP BY R.customer_id;";
	$rentingcustRS = mysqli_query($conn, $tablesql);
  }
  if($_POST['action'] == 'rent'){

  }
}

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
    <h1 class="page-header">Rent Movie</h1>
  </div><!--/.col-lg-12-->
</div><!--/.row-->

</br>
<div class="row">
    <div class="col-lg-10">
		<div class="content" id="wrapper">
		<form name="rentForm" id="rentForm" action="rentMovResults.php" method="post">
		<input type="hidden" class="form-control" name="numEntries" id="numEntries">
		<div class="input-group">
			<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
			<input id="custID" type="number" class="form-control" name="custID" placeholder="Customer ID">

		</div>
			<br>
		<div class="input-group">
			<span class="input-group-addon"><i class="glyphicon glyphicon glyphicon-film"></i></span>
			<input id="inventoryID_0" type="number" class="form-control" name="inventoryID_0" placeholder="Inventory (Film) ID">
		</div>

			<br>


		</form>
		</div>
		<button type="button" class="btn btn-success" id="addmovbutton" onclick="nameFunction()"><span class ="glyphicon glyphicon-plus"></span> Add another movie</button>
		<button type="button" class="btn btn-primary" onclick="submitFunction()">Submit</button>


    </div>
</div>
                <!-- /.row -->

</div><!--/#page-wrapper-->
</div><!--/#wrapper-->
</body>
<script>
$(document).ready(function(){
  $('#CustomersTable').DataTable({
    responsive: true
  });

});


function nameFunction(){
var numOfMovInputs = (rentForm.getElementsByTagName('input').length) - 2;


if(numOfMovInputs < 3){

	$("#addmovbutton").removeAttr("disabled");
	var thediv = document.createElement('div');
	//<div class="input-group">
	thediv.setAttribute("class","input-group");
	thediv.innerHTML = "<span class='input-group-addon'><i class='glyphicon glyphicon glyphicon-film'></i></span><input id='inventoryID_" + numOfMovInputs + "'type='text' class='form-control' name='inventoryID_" + numOfMovInputs + "' placeholder='Inventory (Film) ID'><span class='input-group-btn' id='" + numOfMovInputs + "'><button type='button' class='btn btn-danger' id='removeEntryButt'><span class='glyphicon glyphicon-trash'></span> Delete</button></span>";

	var thebreak = document.createElement('br');
	thebreak.id = "br_" + numOfMovInputs;

	document.getElementById("rentForm").appendChild(thediv);
	document.getElementById("rentForm").appendChild(thebreak);

	numOfMovInputs = (rentForm.getElementsByTagName('input').length) - 2;
	if(numOfMovInputs == 3){
		$("#addmovbutton").attr("disabled", "disabled");
		}

	}

}

$("#rentForm").on('click', "#removeEntryButt", function(e) {
	var idnum = $(this).parent().attr('id');
	var numOfMovInputs = (rentForm.getElementsByTagName('input').length) - 2;
    $(this).parent().parent().remove();
	$("#br_" + idnum).remove();
	$("#addmovbutton").removeAttr("disabled");
	if(idnum == 1 && numOfMovInputs == 3){
		$("#inventoryID_2").attr('id','inventoryID_1');
		$("#inventoryID_1").attr('name','inventoryID_1');
	}
});

function submitFunction(){
	var numOfMovInputs = (rentForm.getElementsByTagName('input').length) - 2;
    document.getElementById("numEntries").value = numOfMovInputs;
	document.getElementById("rentForm").submit();

}
</script>
</html>
