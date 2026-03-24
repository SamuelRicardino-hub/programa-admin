<?php
function registrarLog($pdo, $tipo, $entidade, $entidade_id, $acao)
{

    $usuario_id = $_SESSION['usuario']['id'] ?? null;

    $sql = $pdo->prepare("
        INSERT INTO logs (usuario_id, tipo, entidade, entidade_id, acao)
        VALUES (?, ?, ?, ?, ?)
    ");

    if (!function_exists('corTipo')) { {
            switch ($tipo) {
                case 'DELETE':
                    return 'danger';
                case 'CREATE':
                    return 'success';
                case 'UPDATE':
                    return 'warning';
                case 'APROVACAO':
                    return 'primary';
                case 'REJEICAO':
                    return 'secondary';
                case 'LOGIN':
                    return 'info';
                default:
                    return 'dark';
            }
        }
    }


    $sql->execute([
        $usuario_id,
        $tipo,
        $entidade,
        $entidade_id,
        $acao
    ]);
}
