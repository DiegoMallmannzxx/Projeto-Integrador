<?php
header("Content-Type: application/json");

$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "minestock";

$conn = new mysqli($host, $usuario, $senha, $banco);
if ($conn->connect_error) {
    die(json_encode([]));
}

$sql = "SELECT id, nome, telefone FROM Fornecedor";
$result = $conn->query($sql);

$fornecedores = [];
while ($row = $result->fetch_assoc()) {
    $fornecedores[] = $row;
}

echo json_encode($fornecedores);

$conn->close();
?>
