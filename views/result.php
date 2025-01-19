<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultat de l'itinéraire</title>
</head>
<body>
<h1>Résultat de l'itinéraire</h1>
<?php if ($tripPatterns): ?>
    <?php foreach ($tripPatterns as $pattern): ?>
        <h2>Trajet :</h2>
        <p>Durée : <?= round($pattern['duration'] / 60, 1) ?> minutes</p>
        <p>Distance : <?= round($pattern['distance'] / 1000, 2) ?> km</p>
        <h3>Étapes :</h3>
        <ul>
            <?php foreach ($pattern['legs'] as $leg): ?>
                <li>
                    <?= htmlspecialchars($leg['fromPlace']['name']) ?> →
                    <?= htmlspecialchars($leg['toPlace']['name']) ?> :
                    <?= htmlspecialchars($leg['mode']) ?>
                    (<?= round($leg['distance'] / 1000, 2) ?> km)
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endforeach; ?>
<?php else: ?>
    <p>Aucun itinéraire trouvé.</p>
<?php endif; ?>
<a href="/">Retour au formulaire</a>
</body>
</html>
