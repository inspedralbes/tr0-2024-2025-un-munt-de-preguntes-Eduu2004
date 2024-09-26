<?php
header('Content-Type: application/json');

// Obtenim les preguntes
$preguntes = json_decode(file_get_contents('preguntes.json'), true);

// Obtenim el nombre de preguntes aleatòries que hem de retornar
$nombrePreguntes = isset($_GET['num']) ? (int)$_GET['num'] : 5;

$preguntesSeleccionades = array_slice($preguntes['preguntes'], 0, $nombrePreguntes);

// Retornem les preguntes sense modificar la resposta correcta
echo json_encode($preguntesSeleccionades);
?>
