<?php
// test_sms.php — à supprimer après le test

require __DIR__.'/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$username = $_ENV['AT_USERNAME'];
$apiKey   = $_ENV['AT_API_KEY'];
$numero   = '+237688007677'; // ← Modifie ici ton numéro

$AT     = new \AfricasTalking\SDK\AfricasTalking($username, $apiKey);
$result = $AT->sms()->send([
    'to'      => $numero,
    'message' => 'Test GestMateriel SMS OK',
]);

print_r($result);