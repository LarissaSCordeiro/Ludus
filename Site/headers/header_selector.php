<?php
// header_selector.php
if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['tipo_usuario'])) {
    // Usuário não logado (convidado)
    include __DIR__ . '/header_guest.php';
} else {
    // Usuário logado → verifica o tipo
    switch ($_SESSION['tipo_usuario']) {
        case 'jogador':
            include __DIR__ . '/header_user.php';
            break;

        case 'desenvolvedor':
            include __DIR__ . '/header_dev.php';
            break;

        case 'administrador':
            include __DIR__ . '/header_admin.php';
            break;

        default:
            // fallback se der algum problema → trata como convidado
            include __DIR__ . '/header_guest.php';
            break;
    }
}
?>

