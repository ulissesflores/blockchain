<?php


$data = $_GET['data'];

$nonce['NONCE'] = calcNonce($data);



echo json_encode($nonce);

function calcNonce($data){
    //the difficulty will be kept below level six to ensure acceptable mining time.
    
     $difficulty = 2;
    
    
    
    //creates a variable with the amount of zeros d to validate a hash with
    //the difficulty defined in the variable $ difficulty...
    
    $zeros = $strZero = "";
    
    for ($i = 1; $i <= $difficulty; $i++) {
        $zeros = $zeros . 0;
        $strZero = $strZero . "0";
    }
    
    //compares the first few characters of the hash and checks
    //the condition set by the difficulty.
    
    $nonce = 1;
    do {
        $try = $data . $nonce;
        $hash = hash("sha256", $try);
        $dific = substr($hash, 0, $difficulty);
        $nonce++;
        
    } while($dific <> $zeros);
    
    //Return of nonce.
    $nonceArray['NUMBER'] = $nonce -1;
    $nonceArray['HASH'] = $hash;
    
    return $nonceArray;
}


?>
