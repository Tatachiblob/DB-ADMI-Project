<?php
require_once ('../mysqlConnect.php');
if(isset($_POST['address1']) && isset($_POST['district']) && isset($_POST['postalCode']) && isset($_POST['phoneNumber'])){
  $insertAddress = $conn->prepare("INSERT INTO ADDRESS (ADDRESS, ADDRESS2, DISTRICT, CITY_ID, POSTAL_CODE, PHONE) VALUES (?, ?, ?, ?, ?, ?)");
  $insertAddress->bind_param("sssiss", $address, $address2, $district, $cityId, $postalCode, $phone);
  $address = $_POST['address1'];
  $address2 = $_POST['address2'];
  $district = $_POST['district'];
  $cityId = $_POST['city'];
  $postalCode = $_POST['postalCode'];
  $phone = $_POST['phoneNumber'];
  $insertAddress->execute();
  $addressIdRs = $conn->query("SELECT MAX(ADDRESS_ID) AS A FROM ADDRESS");
  while($row = $addressIdRs->fetch_assoc()){
    $addressId = $row['A'];
  }
  $insertCustomer = $conn->prepare("INSERT INTO CUSTOMER (STORE_ID, FIRST_NAME, LAST_NAME, EMAIL, ADDRESS_ID) VALUES (?, ?, ?, ?, ?)");
  $insertCustomer->bind_param("isssi", $storeId, $firstName, $lastName, $email, $address);
  $storeId = 1;
  $firstName = $_POST['firstName'];
  $lastName = $_POST['lastName'];
  $email = $_POST['email'];
  $address = $addressId;
  echo "Email: " . $email;
  $insertCustomer->execute();
  header("Location: customerRegistration.php?msg=success");
}
?>
