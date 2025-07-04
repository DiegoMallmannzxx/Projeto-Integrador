<?php
header('Content-Type: application/json');
include 'db.php';

$sql = "SELECT p.id, p.nome, p.quantidade, c.nome AS categoria, f.nome AS fornecedor 
        FROM Produto p 
        LEFT JOIN Categoria c ON p.categoria_id = c.id
        LEFT JOIN Fornecedor f ON p.fornecedor_id = f.id";

$resultado = $conn->query($sql);

$produtos = [];

while ($row = $resultado->fetch_assoc()) {
    $produtos[] = $row;
}

echo json_encode($produtos);
?>