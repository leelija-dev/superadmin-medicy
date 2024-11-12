<?php
define('USER_PASS', 'klfiojret435904359043590kfdjis89esr8*#&^#^#*(#KJSHS^&#@()@KSJS^&#@*()()_#KDSJ#&89jgdfkgidfgdfg84w3889kf6');
define('EMP_PASS', 'eiwetr43905435k43504359959465032ij43289di(##$*&^&#JHDNHS^T%@*(@((@)KLRKMdasfu73878478934834dsfjfdsdsfjkfff');
define('ADMIN_PASS','8324583245i3b5858h89*(*$($@*$ BHVG@$*6767478843758bnb8r5438943854353845jjfuwerwerklERERUIRRUHUIRUUIRUIRU^a'); 

function get_rnd_iv($iv_len)
{
   $iv = '';
   while ($iv_len-- > 0) {
       $iv .= chr(mt_rand() & 0xff);
   }
   return $iv;
}

function pass_enc($plain_text, $passkey, $iv_len = 16){

   $plain_text .= "\x13";
   $n = strlen($plain_text);
   if ($n % 16) $plain_text .= str_repeat("\0", 16 - ($n % 16));
   $i = 0;
   $enc_text = get_rnd_iv($iv_len);
   $iv = substr($passkey ^ $enc_text, 0, 512);
   while ($i < $n) {
       $block = substr($plain_text, $i, 16) ^ pack('H*', md5($iv));
       $enc_text .= $block;
       $iv = substr($block . $iv, 0, 512) ^ $passkey;
       $i += 16;
   }
   return base64_encode($enc_text);
}



// $p = "JGFYGwJnzK2y7mpcwUIVVNxfC9Cn5/9hqLxHb8TR+yg=";
// $pass  = USER_PASS;
// echo pass_enc($p, $pass);

function pass_dec($enc_text, $passkey, $iv_len = 16)
{
   $enc_text = base64_decode($enc_text);
   $n = strlen($enc_text);
   $i = $iv_len;
   $plain_text = '';
   $iv = substr($passkey ^ substr($enc_text, 0, $iv_len), 0, 512);
   while ($i < $n) {
       $block = substr($enc_text, $i, 16);
       $plain_text .= $block ^ pack('H*', md5($iv));
       $iv = substr($block . $iv, 0, 512) ^ $passkey;
       $i += 16;
   }
   return preg_replace('/\\x13\\x00*$/', '', $plain_text);
}

function url_enc($url){
    $url = base64_encode($url);
    $url = rawurlencode($url);
    return $url;
}

function url_dec($url){
    $url = rawurldecode($url);
    $url = base64_decode($url);
    return $url;
}



function otpGgenerator(){

    $characters = '0123456789';
    $randomString = '';
    for ($i = 0; $i < 6; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }

    // $randomString = $randomString;

    return $randomString;
}



function isStrongPassword($password) {
    try {
        // Check if password length is at least 8 characters
        if (strlen($password) < 8) {
            throw new Exception("Password should be at least 8 characters long.");
        }

        // Check if password contains at least one uppercase letter
        if (!preg_match('/[A-Z]/', $password)) {
            throw new Exception("Password should contain at least one uppercase letter.");
        }

        // Check if password contains at least one lowercase letter
        if (!preg_match('/[a-z]/', $password)) {
            throw new Exception("Password should contain at least one lowercase letter.");
        }

        // Check if password contains at least one number
        if (!preg_match('/[0-9]/', $password)) {
            throw new Exception("Password should contain at least one number.");
        }

        // Check if password contains at least one special character
        if (!preg_match('/[^A-Za-z0-9]/', $password)) {
            throw new Exception("Password should contain at least one special character.");
        }

        // If all conditions are met, return true
        return true;
    } catch (Exception $e) {
        // If any condition is not satisfied, catch the exception and return false with the corresponding error message
        return $e->getMessage();
    }
}

?>