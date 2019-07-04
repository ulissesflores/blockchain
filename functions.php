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

/**Returns the pseudo-transaction blockchain address.
 * @return string, the value of transaction.
 */
function transactionAddress() {
    $transaction = str_shuffle("abcdefghijklmnopqrstuvyxwz012345678901234567890123456789abcdeftu");
    return $transaction;
}

/**Returns the pseudo-amount value of a transaction.
 * @return float, the value of amount.
 */
function generateAmount() {
    
    $luck = rand(0,10);
    
    switch ($luck) {
        
        case 10:
            
            //MAximum: 2.99 BTC
            $number = rand(1000000, 299999999)/100000000;
            
            break;
            
        case 9:
            
            //MAximum: 0.99 BTC
            $number = rand(100000, 99999999)/100000000;
            
            break;
            
        case 8:
            
            //MAximum: 0.049 BTC
            $number = rand(10000, 4999999)/100000000;
            
            break;
            
        default:
            
            //MAximum: 0.00050000 BTC
            $number = rand(1000, 50000)/100000000;
            
            break;
            
    }
    
    $amount = number_format($number, 8);
    
    return floatval($amount);
    
}

/**Returns json with transactions.
 * Based on Maximum of Wallets, Maximum of Transactions.
 * And Luck to get change.
 *@param MaxWallets int <p>
 *The Maxymum number of wallets on input/output transactions.
 *ex.: 50</p>
 *@param maxTransactions int<p>
 *The Maxymum number of transactions on array.
 *ex.: 40</p>
 *@param luckChange int<p>
 *The chance of a transaction return change.
 *ex.: 80</p>
 *@return array, with all Transactions.
 */
function generateTransactions($MaxWallets,$maxTransactions, $luckChange) {
    
    //TRANSFER OF PARAMETERS
    
    $RandMaxWallets         =   $MaxWallets;
    $transactionsAmountMax  =   $maxTransactions;
    $LuckWinChange          =   $luckChange;
    
    //GLOBAL VARIABLES
    
    $transactions_hash = "";
    $transactions_hash_global = "";
    
    
    //NUMBER OF TRANSACTIONS
        
        $numberOfTransactions = rand(1,$transactionsAmountMax);
    
    for($itransactions=0;$itransactions<$numberOfTransactions;$itransactions++){
        
        //IF THE OUTPUT AMOUNT IS LARGER THAN THE INPUT,
        //RUN THE CODE UNTIL IT GETS HIGHER INPUT THAN THE OUTPUT
        a:
        
        
        //GET ADDRESS OF TRANSACTION
        
        $transactionAddress = transactionAddress();
        
        
        //INPUT
        
        $randInput = rand(1,$RandMaxWallets);
        $TotAmountInput =0;
        
        for($i=0; $i<$randInput;$i++){
            
            $btcAddInput = bitcoinAdress();
            
            do{
                
                $amountInput =  generateAmount();
                $isSciInput = $amountInput;
                
            }while(strstr($isSciInput,"-"));
            
            $transactions_hash .= $transaction[$transactionAddress]['INPUT']['INPUT_ACTIVITY'][$btcAddInput] = $amountInput;
            $TotAmountInput += $amountInput;
            
        }
        
        //OUTPUT
        
        $randOutput = rand(1,$RandMaxWallets);
        $TotAmountOutput =0;
        
        for($i=0; $i<$randOutput;$i++){
            
            $btcAddOutput = bitcoinAdress();
            
            do{
                
                $amountOutput =  generateAmount();
                $isSciOutput = $amountOutput;
                
            }while(strstr($isSciOutput,"-"));
            
            $transactions_hash .= $transaction[$transactionAddress]['OUTPUT']['OUTPUT_ACTIVITY'][$btcAddOutput] = $amountOutput;
            $TotAmountOutput += $amountOutput;
            
        }
        
        //FEE
        
        //GLOBAL INPUT
        $InputQtd  		= 	$randInput;
        $inputAmount 	= 	$TotAmountInput;
        
        //GLOBAL OUTPUT
        $OutputQtd 		= 	$randOutput;
        $outputAmount 	= 	$TotAmountOutput;
        
        //FEE CALCULUS
        $TransQtdTot = $InputQtd + $OutputQtd;
        $TransBytes = ($TransQtdTot) * 92;
        $TranFee = ($TransBytes * 62) /100000000;
        
        $transactions_hash .= $transaction[$transactionAddress]['FEE'] = $TranFee;
        
        //CHANGE
        
        $balance = ($inputAmount + $TranFee) - $outputAmount;
        
        if($balance > 0){
            
            $luckLostChange = rand(1,100);
            
            if($luckLostChange < $LuckWinChange){
                
                //CREATE CHANGE
                
                $amountChange = $balance;
                $btcAddChange = bitcoinAdress();
                
                $transactions_hash .= $transaction[$transactionAddress]['INPUT']['INPUT_ACTIVITY'][$btcAddChange] = 0;
                $transactions_hash .= $transaction[$transactionAddress]['OUTPUT']['OUTPUT_ACTIVITY'][$btcAddChange] = $amountChange;
                $transactions_hash .= $transaction[$transactionAddress]['CHANGE'] = $amountChange;
                $transactions_hash .= $transaction[$transactionAddress]['LOST'] = 0;
                $transactions_hash .= $transaction[$transactionAddress]['MINER'] = $TranFee;
                
            }
            else{
                
                //LOST CHANGE
                
                $amountChange = $balance;
                $lost = $amountChange;
                $miner = $TranFee + $lost;
                
                $transactions_hash .= $transaction[$transactionAddress]['CHANGE'] = 0;
                $transactions_hash .= $transaction[$transactionAddress]['LOST'] = $lost;
                $transactions_hash .= $transaction[$transactionAddress]['MINER'] = $miner;
                
            }
            
            
            
            
        }else{
            
            //IF THE OUTPUT AMOUNT IS LARGER THAN THE INPUT,
            //RUN THE CODE UNTIL IT GETS HIGHER INPUT THAN THE OUTPUT.
            //RETURN TO POINT A. AND UNSET VARIABLE TRANSACTION.
            
            unset($transaction);
            goto a;
            
        }
        
        //UPTDATE GLOBAL INPUT
        
        $transactions_hash .= $transaction[$transactionAddress]['INPUT']['INPUT_TOTAL:']['INPUT_QTD'] = $InputQtd;
        $transactions_hash .= $transaction[$transactionAddress]['INPUT']['INPUT_TOTAL:']['INPUT_AMOUNT'] = $inputAmount;
        
        //UPDATE GLOBAL OUTPUT
        
        $transactions_hash .= $transaction[$transactionAddress]['OUTPUT']['OUTPUT_TOTAL:']['OUTPUT_QTD'] = $OutputQtd;
        $transactions_hash .= $transaction[$transactionAddress]['OUTPUT']['OUTPUT_TOTAL:']['OUTPUT_AMOUNT'] = $outputAmount + $amountChange;
        
        //HASH OF TRANSACTIONS
        
        $transactionHash = hash('SHA256', $transactions_hash);
        $transaction[$transactionAddress]['HASH'] = $transactionHash;
        
        //TRANSACTIONS - GLOBAL
        
        $transactions_hash_global .= $transactions_hash;
        
        
        //SET NEW VARIABLE TRANSACTIONS WITH ARRAY OF VARIABLE TRANSACTION.
        
        $transactions['TRANSACTIONS'][$itransactions] = $transaction;
        
        //RELEASE VARIABLE TRANSACTION AND RESET VARIABLE TRANSACTIONS_HASH
        
        unset($transaction);
        $transactions_hash="";
        
    }
    
    //HASH OF TRANSACTIONS - GLOBAL
    
    $transactionHashGlobal = hash('SHA256', $transactions_hash_global);
    $transactions['HASH_ALL_TRANSACTIONS'] = $transactionHashGlobal;
    
    return json_encode($transactions);
    
}




?>