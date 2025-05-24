<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://apisandbox.culqi.com');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    echo "Error: " . $error;
} else {
    echo "Conexión exitosa";
}
?>