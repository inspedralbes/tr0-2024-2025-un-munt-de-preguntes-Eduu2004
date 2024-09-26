<?php
header('Content-Type: application/json');

// Les respostes correctes de l'usuari (les que s'envien des del frontend)
$respostesUsuari = json_decode(file_get_contents('php://input'), true);

// Aquí es carregarien les preguntes correctes (simulació)
$preguntes = json_decode(file_get_contents('preguntes.json'), true);
$preguntesCorrectes = array_column($preguntes['preguntes'], 'resposta_correcta');

// Comprovem quantes respostes ha encertat l'usuari
$correctes = 0;
$total = count($respostesUsuari);

for ($i = 0; $i < $total; $i++) {
    if (isset($respostesUsuari[$i]) && $respostesUsuari[$i] === $preguntesCorrectes[$i]) {
        $correctes++;
    }
}

// Retornem la puntuació
echo json_encode([
    'correctes' => $correctes,
    'total' => $total
]);
?>
