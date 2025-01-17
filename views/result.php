<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultat de l'itinéraire</title>
</head>
<body>
<h1>Résultat de l'itinéraire</h1>
<?php if ($result): ?>
    <p>Durée : <?= round($result['duration'] / 60, 1) ?> minutes</p>
    <p>Distance : <?= round($result['distance'] / 1000, 2) ?> km</p>
    <h2>Étapes :</h2>
    <ol>
        <?php foreach ($result['steps'] as $step): ?>
            <li><?= htmlspecialchars($step['streetName']) ?> (<?= round($step['distance']) ?> m)</li>
        <?php endforeach; ?>
    </ol>
<?php else: ?>
    <p>Aucun itinéraire trouvé.</p>
<?php endif; ?>
<a href="/">Retour au formulaire</a>
</body>
</html>
