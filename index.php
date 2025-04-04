<?php
$offres = [
    ['nom' => 'Pass 2H', 'prix' => 100],
    ['nom' => 'Pass 12H', 'prix' => 200],
    ['nom' => 'Pass 24H', 'prix' => 300],
    ['nom' => 'Pass 72H', 'prix' => 500],
    ['nom' => 'Pass 7J', 'prix' => 1000],
];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offres WiFi</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; margin: 50px; }
        h2 { margin-bottom: 20px; }
        table { width: 60%; margin: 0 auto; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 15px; text-align: center; }
        th { background-color: #007bff; color: white; }
        button { background-color: #28a745; color: white; padding: 10px; border: none; cursor: pointer; }
        button:hover { background-color: #218838; }
    </style>
</head>
<body>

    <h2>Choisissez votre forfait en cliquant sur "Payer"</h2>

    <table>
        <tr>
            <th>Forfait</th>
            <th>Prix (FCFA)</th>
            <th>Action</th>
        </tr>
        <?php foreach ($offres as $offre) : ?>
        <tr>
            <td><?= htmlspecialchars($offre['nom']) ?></td>
            <td><?= number_format($offre['prix'], 0, ',', ' ') ?> FCFA</td>
            <td>
                <form action="payer.php" method="GET">
                    <!-- Envoie les paramètres via GET -->
                    <input type="hidden" name="montant" value="<?= $offre['prix'] ?>">
                    <input type="hidden" name="nom" value="<?= urlencode($offre['nom']) ?>">
                    <button type="submit">Payer</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

</body>
</html>
