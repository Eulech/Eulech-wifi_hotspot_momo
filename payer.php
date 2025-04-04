<?php

require __DIR__ . '/vendor/autoload.php';
// require 'C:\wamp\www\wifi_hotspot_momo\vendor\autoload.php';
require 'C:\wamp\www\wifi_hotspot\vendor\routeros_api.class.php';

use FedaPay\FedaPay;

FedaPay::setApiKey('sk_live_pMFS_WnV-bQHUQ-eHAAijwFC');

// Vérifier la présence des paramètres
if (!isset($_GET['montant']) || !isset($_GET['nom']) || !isset($_GET['forfait'])) {
    die('Montant, offre ou forfait non spécifié.');
}

$montant = $_GET['montant'];
$nomOffre = urldecode($_GET['nom']);
$forfait_choisi = $_GET['forfait'];

// Tableau des profils MikroTik
$profils = [
    'forfait_2h' => '2h_profile',
    'forfait_12h' => '12h_profile',
    'forfait_24h' => '24h_profile',
    'forfait_72h' => '72h_profile',
    'forfait_7j' => '1SEM_profile',
];

if (!isset($profils[$forfait_choisi])) {
    die('Forfait non valide.');
}

// Redirection vers FedaPay
$url_fedaPay = "https://me.fedapay.com/paiement-wifi";
header("Location: $url_fedaPay");
exit;
