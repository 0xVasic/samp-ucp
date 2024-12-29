<?php

// error.php > redirekt kada se desi greska u bazi (ne brisati)

$errorMessage = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : 'Nepoznata greška.';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fusion Gaming - Greška baze</title>
    <link rel="stylesheet" href="styles/style.css">
    <style>
        /*prebaciti u styles sve*/
        body {
            background: linear-gradient(to right, #2c3e50, #4ca1af);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: 'Arial', sans-serif;
        }

        .error-container {
            background: rgba(0, 0, 0, 0.85);
            color: #fff;
            padding: 30px;
            border-radius: 15px;
            width: 90%;
            max-width: 500px;
            text-align: center;
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.5);
        }

        h1 {
            font-size: 2rem;
            margin-bottom: 20px;
            color: #e74c3c;
        }

        .error-message {
            background: #ffdddd;
            color: #d8000c;
            padding: 10px;
            border: 1px solid #d8000c;
            border-radius: 5px;
            margin-top: 10px;
        }

        button {
            background: #4ca1af;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
        }

        button:hover {
            background: #3a6186;
        }

        .footer {
            margin-top: 20px;
            font-size: 0.9rem;
            color: #bbb;
        }

        .footer a {
            color: #4ca1af;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>Greška u povezivanju</h1>
        <div class="error-message">
            <p><?= $errorMessage; ?></p>
        </div>
        <button onclick="window.location.href='login.php'">Pokušajte ponovo</button>
        <div class="footer">
            © <span id="current-year"></span> Fusion Gaming. Developed by <a href="https://www.vasic.dev">Vasic</a>.
        </div>
    </div>

    <script>
        document.getElementById('current-year').textContent = new Date().getFullYear();
    </script>
</body>
</html>
