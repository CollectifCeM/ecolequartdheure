<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planificateur d'itinéraire</title>
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
        .form-container {
            padding: 2rem;
            border-radius: 8px;
            background-color: #ffffff;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        .form-title {
            margin-bottom: 1rem;
        }
        .form-field {
            margin-bottom: 1.5rem;
        }
        .form-button {
            margin-top: 1rem;
        }
    </style>
</head>
<body>
<div class="form-container">
    <h1 class="form-title">Planifiez votre itinéraire</h1>
    <form method="POST" action="/calculate">
        <div class="form-field">
            <md-outlined-text-field label="Latitude de départ" required>
                <input type="text" name="from_lat">
            </md-outlined-text-field>
        </div>
        <div class="form-field">
            <md-outlined-text-field label="Longitude de départ" required>
                <input type="text" name="from_lng">
            </md-outlined-text-field>
        </div>
        <div class="form-field">
            <md-outlined-text-field label="Latitude d'arrivée" required>
                <input type="text" name="to_lat">
            </md-outlined-text-field>
        </div>
        <div class="form-field">
            <md-outlined-text-field label="Longitude d'arrivée" required>
                <input type="text" name="to_lng">
            </md-outlined-text-field>
        </div>
        <div class="form-button">
            <md-filled-button>
                <button type="submit" slot="button">Calculer l'itinéraire</button>
            </md-filled-button>
        </div>
    </form>
</div>
</body>
</html>
