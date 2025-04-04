<?php
// Activer les erreurs pour le debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'C:\wamp\www\wifi_hotspot_momo\vendor\autoload.php';

// Lire les données JSON
$input = file_get_contents("php://input");
$data = json_decode($input, true);

$status = $data['data']['status'] ?? 'inconnu';

if ($status === 'approved') {
    echo "<h1>✅ Paiement validé !</h1>";
    echo "<p>Votre accès WiFi a été activé.</p>";
} else {
    echo "<h1>❌ Paiement refusé</h1>";
    echo "<p>Votre paiement n'a pas été accepté.</p>";
}

// Activer l'affichage des erreurs pour le debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Vérifier si le fichier des tickets existe
if (!file_exists('tickets.json')) {
    die("<h1>❌ Erreur</h1><p>Ticket introuvable.</p>");
}

// Récupérer les identifiants depuis le fichier JSON
$ticket_data = json_decode(file_get_contents('tickets.json'), true);
if (!$ticket_data) {
    die("<h1>❌ Erreur</h1><p>Impossible de récupérer les identifiants.</p>");
}

$login = $ticket_data['login'];
$password = $ticket_data['password'];

// ✅ URL de redirection vers la page de login MikroTik
$mikrotik_login_url = "http://192.168.99.254/login?username=" . urlencode($login) . "&password=" . urlencode($password);

// ✅ Redirection automatique
header("Location: $mikrotik_login_url");
exit();

?>

?>
