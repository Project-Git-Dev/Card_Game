<?php
session_start();
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Uitloggen</title>
    <script>
        // Verwijder lokale data na uitloggen
        localStorage.clear();
        // Optioneel: wacht 0.5 sec en stuur door naar login pagina
        setTimeout(() => {
            window.location.href = 'login.html';
        }, 500);
    </script>
</head>
<body>
<p>Je wordt uitgelogd...</p>
</body>
</html>
