<?php
include 'conexio.php';


$sql = "CREATE TABLE IF NOT EXISTS preguntes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pregunta VARCHAR(255) NOT NULL,
    resposta_correcta VARCHAR(255) NOT NULL,
    resposta_incorrecta1 VARCHAR(255) NOT NULL,
    resposta_incorrecta2 VARCHAR(255) NOT NULL,
    resposta_incorrecta3 VARCHAR(255) NOT NULL,
    imatge VARCHAR(255) NOT NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "La taula 'preguntes' ha estat creada correctament.<br>";
} else {
    die("Error creant la taula: " . $conn->error . "<br>");
}

$jsonData = file_get_contents('preguntes.json');
$preguntesArray = json_decode($jsonData, true);

if (!$preguntesArray || !isset($preguntesArray['preguntes'])) {
    die("Error llegint el fitxer JSON o estructura incorrecta.<br>");
}

$stmt = $conn->prepare("INSERT INTO preguntes (pregunta, resposta_correcta, resposta_incorrecta1, resposta_incorrecta2, resposta_incorrecta3, imatge) VALUES (?, ?, ?, ?, ?, ?)");

if ($stmt === false) {
    die("Error preparant la consulta: " . $conn->error . "<br>");
}

foreach ($preguntesArray['preguntes'] as $pregunta) {

    $stmt->bind_param("ssssss", 
        $pregunta['pregunta'], 
        $pregunta['resposta_correcta'], 
        $pregunta['respostes_incorrectes'][0], 
        $pregunta['respostes_incorrectes'][1], 
        $pregunta['respostes_incorrectes'][2], 
        $pregunta['imatge']
    );

    if ($stmt->execute()) {
        echo "Pregunta '" . $pregunta['pregunta'] . "' inserida correctament.<br>";
    } else {
        echo "Error inserint la pregunta: " . $stmt->error . "<br>";
    }
}

$stmt->close();
$conn->close();
?>
