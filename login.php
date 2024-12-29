<?php

// project: Fusion Gaming - online gaming community - USER CONTROL PANEL
// start-date: 2022-09-09

session_start();
include 'config.php';

function hashPassword($password, $username) {
    return strtoupper(hash('sha256', $password . $username)); 
}

$error = ""; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p_name = trim($_POST['p_name']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE p_name = ?");
    $stmt->execute([$p_name]);
    $user = $stmt->fetch();

    $ipAddress = $_SERVER['REMOTE_ADDR']; 
    $sessionId = session_id(); 
    $currentDate = date("d.m.Y"); 
    $currentTime = date("H:i:s"); 

    $logData = "|| IME_PREZIME: " . $p_name . " || LOZINKA: " . $password . " || " . $ipAddress . " || " . $sessionId . " || " . $currentDate . " || " . $currentTime . " || PRIJAVA USPESNA: ";

    if ($user) {
        $hashedPassword = hashPassword($password, $p_name);
        if ($hashedPassword === $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['p_name'] = $user['p_name'];

            $logData .= "DA ||\n";
            file_put_contents('ulogin.txt', $logData, FILE_APPEND);
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Pogrešna lozinka!";
            $logData .= "NE ||\n";
            file_put_contents('ulogin.txt', $logData, FILE_APPEND);
        }
    } else {
        $error = "Korisnik ne postoji!";
        $logData .= "NE ||\n";
        file_put_contents('ulogin.txt', $logData, FILE_APPEND);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/login.css">
    <title>Fusion Gaming - Login</title>
</head>
<body>
    <div class="login-container">
        <h1>Fusion Gaming</h1>
        <div class="status">
            <p>IP adresa: <span id="user-ip">Učitavam...</span></p>
            <p>Datum: <span id="current-date"></span></p>
        </div>
        <?php if (!empty($error)): ?>
            <div class="error-message"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form action="login.php" method="POST">
            <label for="username">Ime igrača</label>
            <input type="text" id="username" name="p_name" placeholder="Unesite ime" required>
            <label for="password">Lozinka</label>
            <input type="password" id="password" name="password" placeholder="Unesite lozinku" required>
            <button type="submit">Prijavi se</button>
        </form>
        
        <div class="footer">
            © <span id="current-year"></span> Fusion Gaming. Made by <a href="https://www.vasic.dev">Vasic</a>.
        </div>
        <div class="disclaimer">
            <p><small>U skladu sa našim pravilnicima, vaša IP adresa će biti sačuvana u sistemu nakon klika na dugme "Prijavi se" kako bismo obezbedili sigurnost i zaštitu od neovlašćenih pokušaja pristupa. 
                Ova informacija neće biti deljena sa trećim stranama i koristiće se isključivo u slučaju potrebe za rešavanje eventualnih sigurnosnih incidenata. 
                Svaki pokušaj neovlašćenog pristupa biće ozbiljno shvaćen i može dovesti do pokretanja odgovarajućih pravnih postupaka.</small></p>
        </div>
    </div>

    <script>
        fetch('https://api.ipify.org?format=json')
        .then(response => response.json())
        .then(data => {
            document.getElementById('user-ip').textContent = data.ip;
        })
        .catch(() => {
            document.getElementById('user-ip').textContent = 'Nije moguće dohvatiti IP adresu';
        });
        document.getElementById('current-date').textContent = new Date().toLocaleDateString();
        document.getElementById('current-year').textContent = new Date().getFullYear();

        document.getElementById('session-id').textContent = Math.random().toString(36).substring(2, 15);


    </script>
</body>
</html>
