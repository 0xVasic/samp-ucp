<?php
session_start();
include 'config.php';

$p_name = $_SESSION['p_name'];

$stmt = $pdo->prepare("SELECT * FROM users WHERE p_name = ?");
$stmt->execute([$p_name]);
$user = $stmt->fetch();

if (!$user) {
    die("Greska prilikom ucitavanja podataka. Pokusajte ponovo.");
}

$organization = null;
if ($user['clan'] != 0) {
    $orgStmt = $pdo->prepare("SELECT * FROM organizations WHERE org_id = ?");
    $orgStmt->execute([$user['clan']]);
    $organization = $orgStmt->fetch();
}

$jobs = [
    0 => "Bez posla",
    1 => "Mehanicar",
    2 => "Proizvodjac Municije",
    3 => "Pilot",
    4 => "Rudar",
    5 => "Prevoznik Novca",
    6 => "Uber",
    7 => "Proizvodjac Namjestaja"
];

$jobName = $jobs[$user['job_id']] ?? "Nepoznat posao";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/dashboard.css">
    <title>Fusion Gaming - Dashboard</title>
    <style>

    .organization {
        color: <?= htmlspecialchars($organization['color'] ?? '#ccc'); ?>;
    }

    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>Dobrodošao, <?= htmlspecialchars($user['p_name']); ?>!</h1>
        <table class="info-table">
            <tr>
                <th>Poslednji put viđen na serveru</th>
                <td><?= htmlspecialchars($user['last_login']); ?></td>
            </tr>
            <tr>
                <th>Datum registracije</th>
                <td><?= htmlspecialchars($user['reg_date']); ?></td>
            </tr>
            <tr>
                <th>Vencan/a sa</th>
                <td><?= htmlspecialchars($user['married_to'] ?: 'Niko'); ?></td>
            </tr>
            <tr>
                <th>Sati igre</th>
                <td><?= htmlspecialchars($user['xOnlineSati']); ?></td>
            </tr>
            <tr>
                <th>Level</th>
                <td><?= htmlspecialchars($user['level']); ?></td>
            </tr>
            <tr>
                <th>Respekti</th>
                <td><?= htmlspecialchars($user['exp']); ?></td>
            </tr>
            <tr>
                <th>Pol</th>
                <td><?= htmlspecialchars($user['sex']); ?></td>
            </tr>
            <tr>
                <th>Novac u džepu</th>
                <td>$<?= number_format($user['money']); ?></td>
            </tr>
            <tr>
                <th>Novac u banci</th>
                <td>$<?= number_format($user['xBRacun']); ?></td>
            </tr>
            <tr>
                <th>Zlato</th>
                <td><?= number_format($user['xZlato']); ?> grama</td>
            </tr>
            <tr>
                <th>Posao</th>
                <td><?= htmlspecialchars($jobName); ?></td>
            </tr>
        </tr>
        <tr>
        <th>Organizacija</th>
        <td>
            <?php if ($user['clan'] != 0): ?>
                <?php 
                    $stmt = $pdo->prepare("SELECT name, prefix, color, org_id, rank_1, rank_2, rank_3, rank_4, rank_5, rank_6 FROM organizations WHERE org_id = ?");
                    $stmt->execute([$user['clan']]);
                    $organization = $stmt->fetch();

                    if ($organization): 
                ?>
                    <span style="color: <?= htmlspecialchars($organization['color']); ?>;">
                        [<?= htmlspecialchars($organization['prefix']); ?>] <?= htmlspecialchars($organization['name']); ?>
                    </span>
                    <?php if ($user['lider'] == $organization['org_id']): ?>
                        <span> - DA - <?= htmlspecialchars($organization['name']); ?></span>
                    <?php endif; ?>
                <?php else: ?>
                    Nema organizaciju
                <?php endif; ?>
            <?php else: ?>
                Nema organizaciju
            <?php endif; ?>
        </td>
    </tr>
    <?php if ($user['clan'] != 0 && isset($organization)): ?>
        <tr>
            <th>Rank u organizaciji</th>
            <td>
                <?php 
             
                    $userRank = $user['rank']; 
                    $rank_column = 'rank_' . $userRank; 
                    
                    if (!empty($organization[$rank_column])) {
                        echo 'Rank ' . $userRank . ' - ' . htmlspecialchars($organization[$rank_column]);
                    } else {
                        echo 'Nema ranka u organizaciji';
                    }
                ?>
            </td>
        </tr>
        <?php endif; ?>
        </table>
        <a href="logout.php" class="logout-btn">Odjava</a>
        <div class="footer">
            © <span id="current-year"></span> Fusion Gaming. Made by <a href="https://www.vasic.dev">Vasic</a>.
        </div>
    </div>

    <script>
        document.getElementById('current-year').textContent = new Date().getFullYear();
    </script>
</body>
</html>