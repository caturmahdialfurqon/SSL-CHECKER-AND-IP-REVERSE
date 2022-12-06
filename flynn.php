<?php

error_reporting(E_ERROR | E_PARSE);
system('clear');
$lb= "\033[1;36m"; $pt= "\033[0;37m"; $r = "\033[1;31m"; $gr = "\033[1;32m"; $y = "\33[1;33m";
function menu(){
$lb= "\033[1;36m"; $pt= "\033[0;37m"; $r = "\033[1;31m"; $gr = "\033[1;32m"; $y = "\33[1;33m";
echo "
$y ------------------------------------------------------------------
$pt Credit Autor  : $y CaturMahdiAlFurqon
$pt Github        : $lb https://github.com/caturmahdialfurqon/
$pt Tools Version : $gr V.1.0
$y ------------------------------------------------------------------
$gr                   Menu Of This Tools :
$y ------------------------------------------------------------------
$r [+] $pt 1. $lb SSL CHECKER 
$r [+] $pt 2. $gr IP REVERSE
$y ------------------------------------------------------------------
\n";
}
menu();
$pil = readline("$pt ENTER YOUR CHOICE $y=> $lb");

if ($pil == 1){
	system('clear');
	$lb= "\033[1;36m"; $pt= "\033[0;37m"; $r = "\033[1;31m"; $gr = "\033[1;32m"; $y = "\33[1;33m";
    echo "
$y ------------------------------------------------------------------
$pt Credit Autor  : $y CaturMahdiAlFurqon
$pt Github        : $lb https://github.com/caturmahdialfurqon/
$pt Tools Version : $gr V.1.0
$y ------------------------------------------------------------------ 
$gr Example $pt -> 
$pt Single Target   : $lb www.furqonflynn.com
$pt Multiple Target : $gr www.google.com $y space.byu.id $lb api.midtrans.com
$r [for multiple targets separate them with spaces]
$y ------------------------------------------------------------------ 
\n";
	$domain = readline("$pt Enter Target Domain Here $y => $lb ");
	$ex = "php sslchecker.php {$domain}";
	system("$ex");
}

if ($pil == 2){
	system('clear');
	$lb= "\033[1;36m"; $pt= "\033[0;37m"; $r = "\033[1;31m"; $gr = "\033[1;32m"; $y = "\33[1;33m";
function own($url, $ua, $data = null) {
    while (True){
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_FOLLOWLOCATION => 1,));
        if ($data) {
            curl_setopt_array($ch, array(
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => $data,));
        }
        curl_setopt_array($ch, array(
            CURLOPT_HTTPHEADER => $ua,
            CURLOPT_SSL_VERIFYPEER => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_COOKIEJAR => 'cookie.txt',
            CURLOPT_COOKIEFILE => 'cookie.txt',));
        $run = curl_exec($ch);
        curl_close($ch);
        if ($run) {
            return $run;
        } else {
            echo "\33[1;33mCheck Your Connection!\n";
            sleep(2);
            continue;
        }
    }
}
//===================================START====================================//
echo "
$y ------------------------------------------------------------------
$pt Credit Autor  : $y CaturMahdiAlFurqon
$pt Github        : $lb https://github.com/caturmahdialfurqon/
$pt Tools Version : $gr V.1.0
$y ------------------------------------------------------------------ \n";
$ip = readline("$r [+] $pt Target Domain name Or Ip $y => $lb ");
$lk = "https://domains.yougetsignal.com/domains.php";
$ui = array(
"Host: domains.yougetsignal.com","User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36","Content-type: application/x-www-form-urlencoded; charset=UTF-8","Accept: text/javascript, text/html, application/xml, text/xml, */*","X-Requested-With: XMLHttpRequest","Referer: https://www.yougetsignal.com/","Accept-Language: id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7,ta;q=0.6",
);
$d = "remoteAddress={$ip}&key=&_=";
$go = own($lk,$ui,$d);
$rsl = json_decode($go);
//print_r($rsl);
$status = $rsl->status;
$resultsMethod = $rsl->resultsMethod;
$lastScrape = $rsl->lastScrape;
$domainCount = $rsl->domainCount;
$remoteAddress = $rsl->remoteAddress;
$remoteIpAddress = $rsl->remoteIpAddress;
$domainArray = $rsl->domainArray;
$message = $rsl->message;
if ($status == Success){
echo "
$lb ------------------------------------------------------------------
$pt [$gr+$pt] $gr Status          : $y $status
$lb ------------------------------------------------------------------
$pt [$gr+$pt] $gr ResultsMethod   : $y $resultsMethod
$lb ------------------------------------------------------------------
$pt [$gr+$pt] $gr LastScrape      : $y $lastScrape
$lb ------------------------------------------------------------------
$pt [$gr+$pt] $gr DomainCount     : $y $domainCount
$lb ------------------------------------------------------------------
$pt [$gr+$pt] $gr RemoteAddress   : $y $remoteAddress
$lb ------------------------------------------------------------------
$pt [$gr+$pt] $gr RemoteIpAddress : $y $remoteIpAddress
$lb ------------------------------------------------------------------
\n";
} else if ($status == Fail){
    echo "$r !!! $pt $message\n";
    echo "$y TIPS : $lb Change Your Ip, and try again! \n";
}
$ars = count($domainArray);
for ($a=0;$a<$ars;$a++){
    echo "$pt [$gr+$pt] "."$pt". $domainArray[$a][0].PHP_EOL;
    echo "\n";
}

//===================================ends======================================//
}
