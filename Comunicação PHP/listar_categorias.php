<?php
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "minestock";

$conn = new mysqli($host, $usuario, $senha, $banco);
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

$sql = "SELECT id, nome FROM categoria";
$resultado = $conn->query($sql);

$categorias = [];
while ($linha = $resultado->fetch_assoc()) {
    $categorias[] = $linha;
}

echo json_encode($categorias);
$conn->close();
