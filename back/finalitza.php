<?php
header('Content-Type: application/json');
include 'conexio.php';

$respostesUsuari = json_decode(file_get_contents('php://input'), true);
$correctes = 0;
$total = count($respostesUsuari);

$sql = "SELECT resposta_correcta FROM preguntes LIMIT ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $total);
$stmt->execute();
$result = $stmt->get_result();

$preguntesCorrectes = [];
while ($row = $result->fetch_assoc()) {
    $preguntesCorrectes[] = $row['resposta_correcta'];
}

// Comprovem respostes
for ($i = 0; $i < $total; $i++) {
    if (isset($respostesUsuari[$i]) && $respostesUsuari[$i] === $preguntesCorrectes[$i]) {
        $correctes++;
    }
}

echo json_encode([
    'correctes' => $correctes,
    'total' => $total
]);

$stmt->close();
$conn->close();
?>
