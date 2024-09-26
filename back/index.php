<?php
session_start();

if (!isset($_SESSION['preguntas'])) {
    $json = file_get_contents('preguntas.json');
    $data = json_decode($json, true);
    shuffle($data['preguntas']);

    $_SESSION['preguntas'] = array_slice($data['preguntas'], 0, 10);
    $_SESSION['preguntaActual'] = 0;
    $_SESSION['puntuacio'] = 0;
    $_SESSION['comprovarPregunta'] = null; //Variable per comprobar si la resposta es correcta o no
}
    
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userAnswer = intval($_POST['resposta']);
    $currentQuestionIndex = $_SESSION['preguntaActual'];
    $preguntas = $_SESSION['preguntas'];
    
    if ($preguntas[$currentQuestionIndex]['correctIndex'] == $userAnswer) {
        $_SESSION['puntuacio']++;
        $_SESSION['comprovarPregunta'] = true;
    } else {
        $_SESSION['comprovarPregunta'] = false;
    }

    $_SESSION['preguntaActual']++;

    if ($_SESSION['preguntaActual'] >= 10) {
        header('Location: results.php');
        exit();
    }
}

$currentQuestionIndex = $_SESSION['preguntaActual'];
$question = $_SESSION['preguntas'][$currentQuestionIndex];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activitat 2</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Pregunta <?php echo $currentQuestionIndex + 1; ?>:</h1>
    <p><strong><?php echo $question['question']; ?></strong></p>
    
    <form method="POST">
        <?php foreach ($question['answers'] as $index => $resposta):?>
            <label>
                <input type="radio" name="resposta" value="<?php echo $index; ?>" required>
                <?php echo $resposta; ?>
            </label><br>
        <?php endforeach; ?>
        <br><button type="submit">Enviar</button>
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