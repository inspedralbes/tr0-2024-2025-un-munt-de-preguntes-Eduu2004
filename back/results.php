<?php
session_start();
$puntuacio = $_SESSION['puntuacio'];
$totalPreguntas = 10; 

// Reinicia la sessió al reiniciar el questionari
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_destroy();
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultats</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Has acabat el questionari!!</h1>
    <h2>Puntuació: <?php echo $puntuacio; ?>/<?php echo $totalPreguntas; ?></h2>
    <?php if ($_SESSION['puntuacio'] <= 5) {
        echo("Has d'estudiar més per a la pròxima!!");
    } else {
        echo("Felicitats, molt bona puntuació!!");
    }
    ?>
    <br><br>
    <form method="POST">
        <button type="submit">Tornar a jugar</button>
    </form>
    <?php if (isset($_SESSION['comprovarPregunta'])): ?>
            <?php if ($_SESSION['comprovarPregunta']): ?>
                <p><span style="color: green;">Correcte!</span></p>
            <?php else: ?>
                <p><span style="color: red;">Incorrecte!</span></p>
            <?php endif; ?>
    <?php endif; ?>
</body>
</html>
