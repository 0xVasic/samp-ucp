<?php

// project: Fusion Gaming - online gaming community - USER CONTROL PANEL
// start-date: 2022-09-09

session_start();
include 'config.php';

$stmt = $pdo->query("SELECT * FROM _stats LIMIT 1");
$stats = $stmt->fetch();

if ($stats) {
    $registrovaniKorisnici = $stats['RegistrovanihKorisnika'];
    $banovaniKorisnici = $stats['BanovanihKorisnika'];
    $brojPoseta = $stats['BrojPosetaServeru'];
    $rekordServera = $stats['RekordServera'];
    $reactTime = $stats['ReactTime'];
    $reactName = $stats['ReactName'];
    $registracija = $stats['Registracija'] == 1 ? 'Uključena' : 'Isključena';
} else {
    $registrovaniKorisnici = $banovaniKorisnici = $brojPoseta = $rekordServera = $reactTime = $reactName = 'N/A';
    $registracija = 'N/A';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/index.css">
    <title>Fusion Gaming - Home Page</title>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h1>Dobrodošli na Fusion Gaming!</h1>
            <p class="intro-text">Želiš da vidiš svoju statistiku? Prijavi se na svoj UCP.</p>
            <a href="login.php" class="btn ucp-btn">Prijava na UCP</a>
            <a href="banlist.php" class="btn ucp-btn">Ban lista</a>
            <div class="statistics">
                <h2>Server Stats</h2>
                <table>
                    <tr>
                        <th>Trenutno registrovanih korisnika</th>
                        <td><?= htmlspecialchars($registrovaniKorisnici); ?> korisnika</td>
                    </tr>
                    <tr>
                        <th>Trenutno banovanih korisnika</th>
                        <td><?= htmlspecialchars($banovaniKorisnici); ?> korisnika</td>
                    </tr>
                    <tr>
                        <th>Broj poseta serveru</th>
                        <td><?= htmlspecialchars($brojPoseta); ?></td>
                    </tr>
                    <tr>
                        <th>Rekord servera</th>
                        <td><?= htmlspecialchars($rekordServera); ?> igrača</td>
                    </tr>
                    <tr>
                        <th>Najbrža reakcija</th>
                        <td><?= htmlspecialchars($reactTime); ?> ms</td>
                    </tr>
                    <tr>
                        <th>Najbrži igrač</th>
                        <td><?= htmlspecialchars($reactName); ?></td>
                    </tr>
                    <tr>
                        <th>Registracija</th>
                        <td><?= htmlspecialchars($registracija); ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('current-year').textContent = new Date().getFullYear();
    </script>
</body>
</html>
