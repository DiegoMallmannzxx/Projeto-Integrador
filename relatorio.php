<?php
header('Content-Type: application/json');

$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "minestock";

$conn = new mysqli($host, $usuario, $senha, $banco);
if ($conn->connect_error) {
    die(json_encode(["erro" => "Erro de conexão: " . $conn->connect_error]));
}

// Verifica se foram enviados filtros
$data_inicio = $_GET['data_inicio'] ?? null;
$data_fim    = $_GET['data_fim'] ?? null;
$tipo        = $_GET['tipo'] ?? null;

$entradas = [];
$saidas = [];

// Consulta de entradas
if ($tipo == '' || $tipo == 'entrada') {
    $sql = "SELECT 
                p.nome AS produto,
                f.nome AS fornecedor,
                e.quantidade,
                'entrada' AS tipo,
                e.data
            FROM entradaestoque e
            JOIN produto p ON e.produto_id = p.id
            JOIN fornecedor f ON p.fornecedor_id = f.id
            WHERE 1=1";

    if ($data_inicio && $data_fim) {
        $sql .= " AND DATE(e.data) BETWEEN ? AND ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $data_inicio, $data_fim);
    } else {
        $sql .= " ORDER BY e.data DESC LIMIT 10";
        $stmt = $conn->prepare($sql);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $entradas[] = $row;
    }
}

// Consulta de saídas
if ($tipo == '' || $tipo == 'saida') {
    $sql = "SELECT 
                p.nome AS produto,
                f.nome AS fornecedor,
                s.quantidade,
                'saida' AS tipo,
                s.data
            FROM saidaestoque s
            JOIN produto p ON s.produto_id = p.id
            JOIN fornecedor f ON p.fornecedor_id = f.id
            WHERE 1=1";

    if ($data_inicio && $data_fim) {
        $sql .= " AND DATE(s.data) BETWEEN ? AND ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $data_inicio, $data_fim);
    } else {
        $sql .= " ORDER BY s.data DESC LIMIT 10";
        $stmt = $conn->prepare($sql);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $saidas[] = $row;
    }
}

// Junta tudo
$movimentacoes = array_merge($entradas, $saidas);

// Ordena por data (decrescente)
usort($movimentacoes, function ($a, $b) {
    return strtotime($b['data']) - strtotime($a['data']);
});

// Se não houve filtro, limita a 10 itens
if (!$data_inicio && !$data_fim) {
    $movimentacoes = array_slice($movimentacoes, 0, 10);
}

echo json_encode($movimentacoes);
