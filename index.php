<?php
$weather = null;
$error = null;

if (isset($_POST['city']) && !empty(trim($_POST['city']))) {
    $city = urlencode(trim($_POST['city']));
    $apiKey = 'da1b00e22836cae01f84895b968bc2c1';
    $url = "https://api.openweathermap.org/data/2.5/weather?q={$city}&units=metric&lang=fr&appid={$apiKey}";

    $response = @file_get_contents($url);
    if ($response !== false) {
        $data = json_decode($response, true);
        if (isset($data['main'])) {
            $weather = [
                'ville' => $data['name'],
                'temperature' => round($data['main']['temp']),
                'humidity' => $data['main']['humidity'],
                'description' => ucfirst($data['weather'][0]['description']),
                'icon' => $data['weather'][0]['icon']
            ];
        } else {
            $error = "Ville introuvable.";
        }
    } else {
        $error = "Erreur lors de la connexion à l'API.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>🌦️ Météo Mobile</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <div class="container">
    <h1>🌤️ Météo </h1>

    <form method="POST" action="">
      <input type="text" name="city" placeholder="Entrez une ville..." required />
      <button type="submit">Voir</button>
    </form>

    <?php if ($weather): ?>
      <div class="result">
        <h2><?= htmlspecialchars($weather['ville']) ?></h2>
        <img
          src="https://openweathermap.org/img/wn/<?= $weather['icon'] ?>@2x.png"
          alt="Météo"
          class="weather-icon"
        />
        <p>🌡 Température : <?= $weather['temperature'] ?>°C</p>
        <p>💧 Humidité : <?= $weather['humidity'] ?>%</p>
        <p>📋 Conditions : <?= htmlspecialchars($weather['description']) ?></p>
      </div>
    <?php elseif ($error): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
  </div>
</body>
</html>
