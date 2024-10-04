<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'conexio.php';
header('Content-Type: application/json');

$sql = "SELECT pregunta, resposta_correcta, resposta_incorrecta1, resposta_incorrecta2, resposta_incorrecta3, imatge FROM preguntes ORDER BY RAND()";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['error' => 'Error en la preparación de la consulta: ' . $conn->error]);
    exit;
}

$stmt->execute();
$result = $stmt->get_result();

$preguntes = [];
while ($row = $result->fetch_assoc()) {
    $preguntes[] = $row;
}

echo json_encode($preguntes);
?>
