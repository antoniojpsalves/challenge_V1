<?php

require_once 'src/class/TonyConnection.php';
echo "Iniciando teste <br>";


$conn = new TonyConnection('db_challenge', 'localhost', 'root', '');

echo "<pre>";
print_r($conn->quantidadeCadastros());
echo "<br>";
print_r($conn->mostrarAniverariantes());
echo "</pre>";

