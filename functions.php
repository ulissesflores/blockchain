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





?>