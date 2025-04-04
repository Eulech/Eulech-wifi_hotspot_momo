<?php

// Activer l'affichage des erreurs pour le debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'C:\wamp\www\wifi_hotspot_momo\vendor\autoload.php';
use FedaPay\FedaPay;

// Journalisation pour debug
file_put_contents('fedapay_log.txt', "Webhook appelé\n", FILE_APPEND);

// Lire les données JSON envoyées par FedaPay
$input = file_get_contents("php://input");

// Vérifier si les données sont valides
if (!$input) {
    file_put_contents('fedapay_log.txt', "Erreur : Aucune donnée reçue\n", FILE_APPEND);
    http_response_code(400);
    exit("Erreur : Aucune donnée reçue.");
}

$data = json_decode($input, true);

// Vérifier si JSON est bien décodé
if (json_last_error() !== JSON_ERROR_NONE) {
    file_put_contents('fedapay_log.txt', "Erreur JSON : " . json_last_error_msg() . "\n", FILE_APPEND);
    http_response_code(400);
    exit("Erreur JSON : Données invalides.");
}

// Enregistrer les données pour debug
file_put_contents('fedapay_log.txt', "Webhook reçu : " . print_r($data, true) . "\n", FILE_APPEND);

// Vérifier si le paiement est validé
if (isset($data['event']) && $data['event'] == 'transaction.updated') {
    $status = $data['data']['status'] ?? 'inconnu';

    if ($status === 'approved') {
        file_put_contents('fedapay_log.txt', "✅ Paiement validé\n", FILE_APPEND);

        // ✅ Récupérer les informations de la transaction
        $transaction_id = $data['data']['id'] ?? 'N/A';
        $montant = $data['data']['amount'] ?? 0;

        // ✅ Définir les profils MikroTik en fonction du montant payé
        $profils = [
            100 => '2h_profile',
            200 => '12h_profile',
            300 => '24h_profile',
            500 => '72h_profile',
            1000 => '7j_profile',
        ];

        $forfait_choisi = $profils[$montant] ?? null;
        if (!$forfait_choisi) {
            file_put_contents('fedapay_log.txt', "Forfait non valide : $montant\n", FILE_APPEND);
            exit("Forfait non valide.");
        }

        // ✅ Générer un login et un mot de passe aléatoires
        $login = "userH" . rand(1000, 9999);
        $password = rand(100000, 999999);

        // ✅ Connexion à MikroTik
        require 'C:\wamp\www\wifi_hotspot\vendor\routeros_api.class.php';

        $API = new RouterosAPI();
        if ($API->connect('192.168.99.254', 'admin', 'Geulech@1234', '8728')) {
            
            // Ajouter l'utilisateur dans MikroTik
            $API->comm("/ip/hotspot/user/add", [
                "name" => $login,
                "password" => $password,
                "profile" => $forfait_choisi
            ]);

            $API->disconnect();

            file_put_contents('fedapay_log.txt', "Utilisateur ajouté : $login, Offre: $forfait_choisi\n", FILE_APPEND);

            // Sauvegarde du ticket WiFi dans un fichier JSON
                
            $ticket_data = [
            'login' => $login,
            'password' => $password
];

file_put_contents('tickets.json', json_encode($ticket_data));

file_put_contents('fedapay_log.txt', "Ticket sauvegardé pour $login\n", FILE_APPEND);

            
            // ✅ TODO : Envoyer les identifiants par SMS ou WhatsApp

            echo "✅ Ticket WiFi généré avec succès.";
        } else {
            file_put_contents('fedapay_log.txt', "❌ Erreur de connexion à MikroTik\n", FILE_APPEND);
            exit("Erreur de connexion à MikroTik.");
        }
    } else {
        file_put_contents('fedapay_log.txt', "❌ Paiement refusé : $status\n", FILE_APPEND);
    }
}

// Répondre 200 pour indiquer que le webhook est bien traité
http_response_code(200);
exit("OK");

?>
