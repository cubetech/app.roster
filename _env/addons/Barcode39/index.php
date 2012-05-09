<?php

phpinfo();
// include Barcode39 class 
include "Barcode39.php"; 

// set Barcode39 object 
$bc = new Barcode39("Shay Anderson"); 
$bc->barcode_bar_thick = 4;
$bc->barcode_bar_thin = 2;
// display new barcode 
$bc->draw();

?>