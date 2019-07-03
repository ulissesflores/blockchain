<?php
/**Returns the value of the nonce number.
 * Based on data and difficulty. 
 *@param data string <p>
 *the data that will be used to calculate the nonce. 
 * </p>
 * @param difficulty int<p>
 * a value between 1 and 5 to find the nonce.
 * </p>
 * @return int, the value of nonce.
 */
function calcNonce($data, $difficulty){
    //if the variable is null, the value 1 is defined for the variable, 
    //if it is greater than 5, we will maintain 5.
    //the difficulty will be kept below level five to ensure acceptable mining time.
    
        if      (   is_null($difficulty) )  {   $difficulty = 1;
        
        }elseif (   $difficulty >=6 )       {   $difficulty = 5;
            
        }
        
    //creates a variable with the amount of zeros required to validate a hash with 
    //the difficulty defined in the variable $ difficulty...
    
        $zeros = $strZero = ""; 
        
        for ($i = 1; $i <= $difficulty; $i++) {
            $zeros = $zeros . 1;
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
        return $nonce;
}


/**Returns the pseudo-address of a bitcoin wallet.
 * @return string, the value of address.
 */
function bitcoinAdress() {
    //https://en.bitcoin.it/wiki/List_of_address_prefixes
    
    //#1:
    //version hex = 80,
    // https://en.bitcoin.it/wiki/List_of_address_prefixes
    
    $version = 80;
    
    //#2:
    //Random 32 bytes variable.
    //with version.
    //Convert to hex.
    
    for($i = 0; $i < 32; ++$i) {
        
        $byteArray[] = rand(0,255);
        
    }
    
    $hexa = $version;
    
    foreach ($byteArray as $byte){
        
        $hexa .= sprintf("%02X", $byte);
        
    }
    
    //#3:
    //checksum - 4 byte.
    //Checksum
    //4 bytes da sha256(sha256(version+privateKey))
    
    $firstSha = hash('sha256', $hexa);
    $secondSha = hash('sha256', $firstSha);
    $checksum = strtoupper(substr($secondSha, 0,8));
    $KeyChecksum = $hexa . $checksum;
    
    //#4:
    //WIF - Wallet Import Format.
    //Convert to base58
    
    $base58 = new Base58;
    $privateKeyWIF  = $base58->encode($KeyChecksum);
    $publicKey  = hash('ripemd160', $privateKeyWIF);
    
    //#5:
    //Addresses
    //A bitcoin address is in fact the hash of a public key, computed this way:
    //Version = 1 byte of 0 (zero); on the test network, this is 1 byte of 111
    //Key hash = Version concatenated with RIPEMD-160(SHA-256(public key))
    //Checksum = 1st 4 bytes of SHA-256(SHA-256(Key hash))
    //Bitcoin Address = Base58Encode(Key hash concatenated with Checksum)
    //https://en.bitcoin.it/wiki/List_of_address_prefixes
    
    $address = "1";
    $address .= hash('ripemd160', hash('sha256', $publicKey));
    $checksumAddress = substr(hash('sha256', hash('sha256', $address)), 0,7);
    $address .= $checksumAddress;
    $BitcoinAddress = "1". $base58->encode($address);
    
    //Return Bitcoin Address
    return $BitcoinAddress;
}




?>