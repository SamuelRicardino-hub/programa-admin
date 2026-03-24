<?php
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../config/auth.php';

auth();
canAny(['admin', 'atendente']);

$pdo->exec("INSERT INTO casos () VALUES ()");

$caso_id = $pdo->lastInsertId();

header("Location: caso_detalhes.php?id=" . $caso_id);
exit;
