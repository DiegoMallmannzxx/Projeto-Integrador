<?php
header("Content-Type: application/json");

$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "minestock";

// Conexão
$conn = new mysqli($host, $usuario, $senha, $banco);
if ($conn->connect_error) {
    echo json_encode(["erro" => "Erro na conexão com o banco"]);
    exit;
}

// Pega o código enviado pela URL
$codigo = isset($_GET['codigo']) ? $_GET['codigo'] : '';

if (!empty($codigo)) {
    $sql = "SELECT Produto.nome AS produto_nome, Fornecedor.nome AS fornecedor_nome
            FROM Produto 
            LEFT JOIN Fornecedor ON Produto.fornecedor_id = Fornecedor.id
            WHERE Produto.codigo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $codigo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($dados = $result->fetch_assoc()) {
        echo json_encode([
            "nome" => $dados["produto_nome"],
            "fornecedor" => $dados["fornecedor_nome"]
        ]);
    } else {
        echo json_encode([]);
    }
} else {
    echo json_encode([]);
}

$conn->close();
?>
