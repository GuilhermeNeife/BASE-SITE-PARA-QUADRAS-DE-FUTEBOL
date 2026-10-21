<?php
/**
 * Sistema de Autenticação
 * Gerencia login, logout e verificação de sessão
 */

session_start();

require_once 'config.php';

/**
 * Verificar se o usuário está logado
 */
function is_logged_in() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

/**
 * Fazer login
 */
function login($username, $password) {
    // Validar credenciais (em produção, usar hash de senha)
    if ($username === ADMIN_USERNAME && $password === ADMIN_PASSWORD) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        $_SESSION['login_time'] = time();
        return true;
    }
    return false;
}

/**
 * Fazer logout
 */
function logout() {
    session_destroy();
    return true;
}

/**
 * Verificar timeout de sessão
 */
function check_session_timeout() {
    if (is_logged_in()) {
        $current_time = time();
        $login_time = $_SESSION['login_time'] ?? 0;
        
        if (($current_time - $login_time) > SESSION_TIMEOUT) {
            logout();
            return false;
        }
        
        // Atualizar tempo de login para manter sessão ativa
        $_SESSION['login_time'] = $current_time;
        return true;
    }
    return false;
}

/**
 * Redirecionar para login se não autenticado
 */
function require_login() {
    if (!is_logged_in() || !check_session_timeout()) {
        header('Location: login.php');
        exit;
    }
}

/**
 * Processar login via POST
 */
function handle_login() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (login($username, $password)) {
            header('Location: admin/index.php');
            exit;
        } else {
            return 'Usuário ou senha incorretos';
        }
    }
    return null;
}
?>
