<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $card = $_POST["card"] ?? '';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://www.maxipuan.com.tr/CheckCardPoints");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['card' => $card]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded',
    ]);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo "Curl Hatası: " . curl_error($ch);
    } else {
        echo $response;
    }

    curl_close($ch);
} else {
    echo "Bu sayfaya doğrudan erişim yasak.";
}
