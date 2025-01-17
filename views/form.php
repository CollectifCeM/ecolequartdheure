<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calcul d'itinéraire</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<h1>Calcul d'un itinéraire à pied</h1>
<form method="POST" action="/calculate">
    <label>Latitude de départ :</label>
    <input type="text" name="from_lat" required>

    <label>Longitude de départ :</label>
    <input type="text" name="from_lng" required>

    <label>Latitude d'arrivée :</label>
    <input type="text" name="to_lat" required>

    <label>Longitude d'arrivée :</label>
    <input type="text" name="to_lng" required>

    <button type="submit">Calculer l'itinéraire</button>
</form>
</body>
</html>
