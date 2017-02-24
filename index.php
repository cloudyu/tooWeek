<?php
/**
public static String getSeed(String xh, String date) {
String[] dateStr = new String[]{date.substring(0, 4), date.substring(4, 6), date.substring(6, 8),
 * date.substring(8, 10), date.substring(10, 12), date.substring(12, 14)};
String retStr = "";
int i = 0;
for (int m = (Integer.parseInt(xh) % 63) + 1; m > 0; m >>= 1) {
if (m % 2 != 0) {
retStr = dateStr[i] + retStr;
}
i++;
}
return retStr;
}

 */
var_dump(getToken('031603133', '20170201134154'));
function getToken($xh, $dateStr){
    $token =  $xh . '_' .$dateStr . '_' . rw_getSeed($xh, $dateStr);
    $token = rw_encryptDES($token);
    return $token;
}
function rw_encryptDES($encryptString){
    $encryptKey = 'n&1P)J^A';//密钥
    //PKCS5Padding填充开始
    $block = mcrypt_get_block_size(MCRYPT_DES, MCRYPT_MODE_CBC);
    $padding = $block - (strlen($encryptString) % $block);
    $encryptString .= str_repeat(chr($padding),$padding);
    //PKCS5Padding填充结束
    return base64_encode(mcrypt_encrypt(MCRYPT_DES, $encryptKey, $encryptString, MCRYPT_MODE_CBC, $encryptKey));
}
function rw_getSeed($xh, $date){
    $dataStr = array(
        substr($date, 0, 4),
        substr($date, 4, 2),
        substr($date, 6, 2),
        substr($date, 8, 2),
        substr($date, 10, 2),
        substr($date, 12, 2));
    $retStr = "";
    $i = 0;
    $m = (intval($xh) % 63) + 1;
    for(; $m > 0; $m >>= 1){
        if($m % 2 != 0){
            $retStr = $dataStr[$i] . $retStr;
        }
        $i++;
    }
    return $retStr;
}


?>