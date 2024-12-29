<?php
session_start();
include 'config.php';


$usersPerPage = 20;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $usersPerPage;
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

// dodatno proveriti sqli za search (dole)
$sql = "SELECT * FROM banned WHERE user_name LIKE :search LIMIT :offset, :limit";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':search', "%$searchQuery%", PDO::PARAM_STR);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':limit', $usersPerPage, PDO::PARAM_INT);
$stmt->execute();
$bannedUsers = $stmt->fetchAll();

$sqlTotal = "SELECT COUNT(*) FROM banned WHERE user_name LIKE :search";
$stmtTotal = $pdo->prepare($sqlTotal);
$stmtTotal->bindValue(':search', "%$searchQuery%", PDO::PARAM_STR);
$stmtTotal->execute();
$totalUsers = $stmtTotal->fetchColumn();
$totalPages = ceil($totalUsers / $usersPerPage);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/banlist.css">
    <title>Ban Lista</title>
</head>
<body>
    <div class="main-container">
        <div class="content-wrapper">
            <h1>Ban Lista</h1>

            <div class="search-form">  <!-- proveriti sqli --> 
                <form method="GET">
                    <input type="text" name="search" placeholder="Pretraga banovanih korisnika" value="<?= htmlspecialchars($searchQuery); ?>">
                    <button type="submit">Pretraži</button>
                </form>
            </div>

            <div class="ban-list-container">
                <table>
                    <thead>
                        <tr>
                            <th>Ime igraca</th>
                            <th>Razlog bana</th>
                            <th>Banovan od strane</th>
                            <th>Datum bana</th>
                            <th>Tip bana</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bannedUsers as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['user_name']); ?></td>
                            <td><?= htmlspecialchars($user['ban_reason']); ?></td>
                            <td><?= htmlspecialchars($user['ban_admin']); ?></td>
                            <td><?= htmlspecialchars($user['ban_date']); ?></td>
                            <td>
                                <?php
                                    if ($user['ban_time'] == NULL) { // "privremen" a ne tacan datum zbog loseg formatiranja u bazi, update 4.0 bice reseno
                                        echo "Trajan";
                                    } else {
                                        echo "Privremen";
                                    }
                                ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- paginacija (/) -->
            <div class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?= $i; ?>&search=<?= htmlspecialchars($searchQuery); ?>" class="page-link"><?= $i; ?></a>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</body>
</html>
