<?php

error_reporting(E_ERROR | E_PARSE);
system('clear');

$lb= "\033[1;36m"; $pt= "\033[0;37m"; $r = "\033[1;31m"; $gr = "\033[1;32m"; $y = "\33[1;33m";

function getIps($domain) {
    $ips = [];
    $dnsRecords = dns_get_record($domain, DNS_A + DNS_AAAA);
    foreach ($dnsRecords as $record) {
        if (isset($record['ip'])) {
            $ips[] = $record['ip'];
        }
        if (isset($record['ipv6'])) {
            $ips[] = '[' . $record['ipv6'] . ']'; // bindto of 'stream_context_create' uses this format of ipv6
        }
    }
    return $ips;
}

function getCert($ip, $domain) {
    $g = stream_context_create(["ssl" => ["capture_peer_cert" => true], 'socket' => ['bindto' => $ip]]);
    $r = stream_socket_client("ssl://{$domain}:443", $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $g);
    $cont = stream_context_get_params($r);
    return openssl_x509_parse($cont["options"]["ssl"]["peer_certificate"]);
}

function getOutputColor($daysLeft) {
    if ($daysLeft > 30) return "\e[32m";
    if ($daysLeft > 15) return "\e[33m";
    return "\e[31m";
}

$domains = array_slice($argv, 1);
$domainCount = count($domains);

if ($domainCount == 0){
    echo "
    $lb ------------------------------------------------------------------
    $pt                     Domain SSL Checker
    $lb ------------------------------------------------------------------
    $pt Usage        : $y php ssl.php $pt [$gr Domain $pt]
    $lb ------------------------------------------------------------------
    $pt example      : $y php ssl.php $gr www.furqonflynn.com 
    $lb ------------------------------------------------------------------
    $pt for multiple : $y php ssl.php $gr www.furqonflynn.com $r www.google.com
    $lb ------------------------------------------------------------------
    \n";
}

$now = new DateTime('now', new DateTimeZone('UTC'));
$expiringSoon = [];
$errors = [];
$certCount = 0;

echo "$lb ------------------------------------------------------------------ $pt \n";
echo '             Domain SSL Report for ' . $now->format('jS M Y') . "\n";
echo "$lb ------------------------------------------------------------------ \n";


foreach ($domains as $domain) {
    $ips = getIps($domain);

    if (count($ips) === 0) {
        $errors[] = $domain . " :: FAILED TO FIND SERVER IP\n";
    }

    foreach ($ips as $ip) {
        $certCount++;
        $cert = getCert($ip, $domain);

        if (!$cert) {
            $errors[] =  $domain . '@' . $ip . " :: FAILED TO GET CERTIFICATE INFORMATION\n";
            continue;
        }

        $validFrom = new DateTime("@" . $cert['validFrom_time_t']);
        $validTo = new DateTime("@" . $cert['validTo_time_t']);
        $diff = $now->diff($validTo);
        $daysLeft = $diff->invert ? 0 : $diff->days;
        if ($daysLeft <= 15) $expiringSoon[] = $domain;
        echo getOutputColor($daysLeft);
        echo $domain . (count($ips) > 1 ? " ($ip)" : "") . "\n";
        echo "\tValid From:\t " . $validFrom->format('jS M Y') . ' (' . $validFrom->format('Y-m-d H:i:s') . ")\n";
        echo "\tValid To:\t " . $validTo->format('jS M Y') . ' (' . $validTo->format('Y-m-d H:i:s') . ")\n";
        echo "\tDays Left:\t " . $daysLeft . "\n";
        echo "\e[0m\n";  
    }

}

$expiringCount = count($expiringSoon);
echo "$lb ------------------------------------------------------------ $y \n";
echo "$expiringCount of $certCount certificate" . ($certCount > 1 ? 's':'') 
    ." across $domainCount domain".($domainCount > 1 ? 's':'')." expired or expiring soon\n";
echo "$lb ------------------------------------------------------------\n";


if (count($errors) > 0) {
    echo "$lb------------------------------------------------------------$r\n";
    echo "$r !!!$pt Errors:\n\n" . "$y" . implode("\n", $errors);
    echo "$lb------------------------------------------------------------\n";
}
