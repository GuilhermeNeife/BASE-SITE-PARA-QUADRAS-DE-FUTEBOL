<?php
/**
 * Configuração de Banco de Dados - Hostinger
 * Compatível com hospedagem Hostinger
 */

// ============================================
// CONFIGURAÇÃO DO BANCO DE DADOS
// ============================================
// Para Hostinger, atualize com suas credenciais:
define('DB_HOST', 'localhost');
define('DB_USER', 'u719349953_teste1');
define('DB_PASS', '2=wC+1RdRS?H');
define('DB_NAME', 'u719349953_teste');

// ============================================
// CONFIGURAÇÃO DE UPLOADS
// ============================================
define('UPLOAD_DIR', __DIR__ . '/uploads/');
define('UPLOAD_URL', '/uploads/');
define('MAX_FILE_SIZE', 10 * 1024 * 1024); // 10MB
define('ALLOWED_TYPES', ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);

// ============================================
// CONFIGURAÇÃO DE SEGURANÇA
// ============================================
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'admin123'); // MUDE ISSO EM PRODUÇÃO!
define('SESSION_TIMEOUT', 3600); // 1 hora

// ============================================
// CRIAR CONEXÃO COM BANCO DE DADOS
// ============================================
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Verificar conexão
if ($conn->connect_error) {
    die("Erro de conexão com banco de dados: " . $conn->connect_error);
}

// Definir charset para UTF-8
$conn->set_charset("utf8mb4");

// ============================================
// CRIAR DIRETÓRIO DE UPLOADS SE NÃO EXISTIR
// ============================================
if (!is_dir(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0755, true);
}

// ============================================
// FUNÇÕES AUXILIARES
// ============================================

/**
 * Escapar dados para proteção contra SQL injection
 */
function escape($data) {
    global $conn;
    if (is_array($data)) {
        return array_map('escape', $data);
    }
    return $conn->real_escape_string($data);
}

/**
 * Executar query
 */
function query($sql) {
    global $conn;
    return $conn->query($sql);
}

/**
 * Obter resultado como array associativo
 */
function fetch_assoc($result) {
    if ($result === false) return null;
    return $result->fetch_assoc();
}

/**
 * Obter todos os resultados
 */
function fetch_all($result) {
    if ($result === false) return [];
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
}

/**
 * Fazer upload de arquivo
 */
function upload_file($file_input_name) {
    if (!isset($_FILES[$file_input_name])) {
        return ['success' => false, 'message' => 'Nenhum arquivo enviado'];
    }
    
    $file = $_FILES[$file_input_name];
    
    // Validar arquivo
    if (!in_array($file['type'], ALLOWED_TYPES)) {
        return ['success' => false, 'message' => 'Tipo de arquivo não permitido. Use: JPG, PNG, GIF ou WebP'];
    }
    
    if ($file['size'] > MAX_FILE_SIZE) {
        return ['success' => false, 'message' => 'Arquivo muito grande (máximo 10MB)'];
    }
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'Erro ao enviar arquivo'];
    }
    
    // Gerar nome único
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = time() . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
    $filepath = UPLOAD_DIR . $filename;
    
    // Mover arquivo
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return [
            'success' => true,
            'filename' => $filename,
            'url' => UPLOAD_URL . $filename,
            'message' => 'Arquivo enviado com sucesso'
        ];
    }
    
    return ['success' => false, 'message' => 'Erro ao enviar arquivo'];
}

/**
 * Deletar arquivo
 */
function delete_file($filename) {
    $filepath = UPLOAD_DIR . basename($filename);
    if (file_exists($filepath)) {
        return unlink($filepath);
    }
    return false;
}

/**
 * Sanitizar entrada de texto
 */
function sanitize_text($text) {
    return htmlspecialchars(trim($text), ENT_QUOTES, 'UTF-8');
}

/**
 * Validar email
 */
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Redirecionar com mensagem
 */
function redirect_with_message($url, $message, $type = 'success') {
    $_SESSION['message'] = $message;
    $_SESSION['message_type'] = $type;
    header("Location: $url");
    exit;
}
?>
