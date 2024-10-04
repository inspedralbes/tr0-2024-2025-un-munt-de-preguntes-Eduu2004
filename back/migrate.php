<?php
include 'conexio.php';

// Crear taula preguntes
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
    echo "Table 'preguntes' created successfully.<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Llegir fitxer JSON
$jsonData = file_get_contents('preguntes.json');

if ($jsonData === false) {
    die("Error reading JSON file.<br>");
}

$preguntesArray = json_decode($jsonData, true);

$stmt = $conn->prepare("INSERT INTO preguntes (pregunta, resposta_correcta, resposta_incorrecta1, resposta_incorrecta2, resposta_incorrecta3, imatge) VALUES (?, ?, ?, ?, ?, ?)");

if ($stmt === false) {
    die("Error preparing statement: " . $conn->error . "<br>");
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
        echo "Error inserint pregunta: " . $stmt->error . "<br>";
    }
}

$stmt->close();
$conn->close();
?>
