<?php
function mostrarMensagem($tipo, $texto) {
    $classes = [
        'success' => 'alert alert-success',
        'error' => 'alert alert-error',
        'info' => 'alert alert-info'
    ];
    return '<div class="' . ($classes[$tipo] ?? 'alert alert-info') . '">' . $texto . '</div>';
}

function redirect($url) {
    header("Location: $url");
    exit;
}

function debug($data) {
    echo '<pre>' . print_r($data, true) . '</pre>';
}

function getStatusBadge($status) {
    $classes = [
        'Aberta' => 'status-aberta',
        'Em Andamento' => 'status-andamento',
        'Concluída' => 'status-concluida',
        'Cancelada' => 'status-cancelada'
    ];
    
    $class = $classes[$status] ?? 'status-aberta';
    return '<span class="status-badge ' . $class . '">' . $status . '</span>';
}
?>