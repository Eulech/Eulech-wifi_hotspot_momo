<?php

require __DIR__ . '/vendor/autoload.php';
use FedaPay\FedaPay;
use FedaPay\Transaction;

FedaPay::setApiKey('sk_live_pMFS_WnV-bQHUQ-eHAAijwFC');

// Vérifier les paramètres
if (!isset($_GET['montant']) || !isset($_GET['nom']) || !isset($_GET['forfait'])) {
    die('Montant, offre ou forfait non spécifié.');
}

$montant = $_GET['montant'];
$nomOffre = urldecode($_GET['nom']);
$forfait_choisi = $_GET['forfait'];

// Vérifier le forfait
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

try {
    // Créer la transaction FedaPay
    $transaction = Transaction::create([
        "description" => "Achat forfait $nomOffre",
        "amount" => $montant,
        "currency" => ["iso" => "XOF"],
        "callback_url" => "https://ton-domaine.com/webhook.php", // Facultatif
        "customer" => [
            "firstname" => "Client",
            "lastname" => "WiFi",
            "email" => "client@example.com"
        ],
        "metadata" => [
            "forfait" => $forfait_choisi
        ]
    ]);

    $token = $transaction->generateToken();
    $url_fedaPay = $token->url;

    // Rediriger vers la page de paiement FedaPay
    header("Location: $url_fedaPay");
    exit;

} catch (Exception $e) {
    echo "Erreur lors de la création du paiement : " . $e->getMessage();
}
