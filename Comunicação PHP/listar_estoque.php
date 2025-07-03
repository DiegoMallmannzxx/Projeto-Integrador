<?php
header('Content-Type: application/json');

$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "minestock";

// Conexão
$conn = new mysqli($host, $usuario, $senha, $banco);
if ($conn->connect_error) {
    die(json_encode(["erro" => "Falha na conexão com o banco."]));
}

// Campo opcional de busca
$codigo = $_GET['codigo'] ?? '';

$sql = "
  SELECT 
    p.nome,
    p.codigo,
    COALESCE(SUM(e.quantidade), 0) - COALESCE(SUM(s.quantidade), 0) AS quantidade_disponivel
  FROM produto p
  LEFT JOIN entradaestoque e ON p.id = e.produto_id
  LEFT JOIN saidaestoque s ON p.id = s.produto_id
";

// Filtrar por código se necessário
if (!empty($codigo)) {
    $sql .= " WHERE p.codigo = ?";
}

$sql .= " GROUP BY p.id, p.nome, p.codigo";

$stmt = $conn->prepare($sql);
if (!empty($codigo)) {
    $stmt->bind_param("s", $codigo);
}
$stmt->execute();
$result = $stmt->get_result();

$produtos = [];
while ($row = $result->fetch_assoc()) {
    $row['quantidade_disponivel'] = (int)$row['quantidade_disponivel'];
    $produtos[] = $row;
}

echo json_encode($produtos);
$stmt->close();
$conn->close();
