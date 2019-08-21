<?php
include 'functions.php';

$data = $_GET['data'];

$nonce['NONCE'] = calcNonce($data);



echo json_encode($nonce);



?>