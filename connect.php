<?php
$servername="localhost";
$db_username="maram";
$db_pwd="ekhdemstp777";
$db_name="dba_pfa";
$conn=new PDO("mysql:host=$servername;db_name="dba_pfa",$db_username,$db_pwd");
 $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 echo "Connected successfully";
catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
?>