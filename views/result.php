<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultat de l'itinéraire</title>
    <!-- Material Design -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined">
    <script type="module" src="https://unpkg.com/@material/web@latest/dist/mdc.min.js"></script>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f5f5f5;
        }
        .result-container {
            padding: 2rem;
            border-radius: 8px;
            background-color: #ffffff;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }
        .result-title {
            margin-bottom: 1rem;
        }
        .result-section {
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
<div class="result-container">
    <h1 class="result-title">Résultat de l'itinéraire</h1>
    <?php if ($tripPatterns): ?>
        <?php foreach ($tripPatterns as $pattern): ?>
            <div class="result-section">
                <h2>Trajet :</h2>
                <p>Durée : <?= $pattern['duration'] ?> minutes</p>
                <p>Distance : <?= $pattern['distance'] ?> m</p>
                <h3>Étapes :</h3>
                <ul>
                    <?php foreach ($pattern['legs'] as $leg): ?>
                        <li>
                            <?= htmlspecialchars($leg['fromPlace']['name']) ?> →
                            <?= htmlspecialchars($leg['toPlace']['name']) ?> :
                            <?= htmlspecialchars($leg['mode']) ?>
                            (<?= $leg['distance'] ?> m)
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucun itinéraire trouvé.</p>
    <?php endif; ?>
    <md-filled-button>
        <a href="/" slot="button">Retour au formulaire</a>
    </md-filled-button>
</div>
</body>
</html>
