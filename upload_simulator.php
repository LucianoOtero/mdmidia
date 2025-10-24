<?php

/**
 * UPLOAD AUTOMÁTICO DO SIMULADOR OCTADESK
 * Script para fazer upload dos arquivos via web
 */

// Verificar se é uma requisição POST com arquivos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['simulator_files'])) {
    
    echo "<h2>🚀 UPLOAD DO SIMULADOR OCTADESK</h2>";
    
    $uploadDir = __DIR__ . '/dev/octadesk-simulator/';
    
    // Criar diretório se não existir
    if (!is_dir($uploadDir)) {
        if (mkdir($uploadDir, 0755, true)) {
            echo "<p>✅ Diretório criado: {$uploadDir}</p>";
        } else {
            echo "<p>❌ Erro ao criar diretório: {$uploadDir}</p>";
            exit;
        }
    }
    
    // Processar cada arquivo
    $files = $_FILES['simulator_files'];
    $fileCount = count($files['name']);
    
    for ($i = 0; $i < $fileCount; $i++) {
        $fileName = $files['name'][$i];
        $fileTmp = $files['tmp_name'][$i];
        $fileSize = $files['size'][$i];
        
        if ($files['error'][$i] === UPLOAD_ERR_OK) {
            $targetPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($fileTmp, $targetPath)) {
                echo "<p>✅ Arquivo enviado: {$fileName} ({$fileSize} bytes)</p>";
            } else {
                echo "<p>❌ Erro ao enviar: {$fileName}</p>";
            }
        } else {
            echo "<p>❌ Erro no upload: {$fileName}</p>";
        }
    }
    
    // Criar diretórios necessários
    $dirs = [
        $uploadDir . 'data',
        dirname($uploadDir) . '/logs'
    ];
    
    foreach ($dirs as $dir) {
        if (!is_dir($dir)) {
            if (mkdir($dir, 0755, true)) {
                echo "<p>✅ Diretório criado: {$dir}</p>";
            } else {
                echo "<p>❌ Erro ao criar diretório: {$dir}</p>";
            }
        } else {
            echo "<p>✅ Diretório já existe: {$dir}</p>";
        }
    }
    
    echo "<h3>🎉 UPLOAD CONCLUÍDO!</h3>";
    echo "<p><a href='/dev/octadesk-simulator/monitor.html' target='_blank'>🔗 Acessar Interface de Monitoramento</a></p>";
    echo "<p><a href='/dev/octadesk-simulator/api/v1/health' target='_blank'>🔗 Testar Health Check</a></p>";
    
} else {
    // Mostrar formulário de upload
    ?>
    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Upload Simulador OctaDesk</title>
        <style>
            body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
            .upload-form { background: #f5f5f5; padding: 30px; border-radius: 10px; }
            .file-input { margin: 10px 0; }
            .btn { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; }
            .btn:hover { background: #0056b3; }
            .instructions { background: #e9ecef; padding: 20px; border-radius: 5px; margin: 20px 0; }
        </style>
    </head>
    <body>
        <h1>🚀 Upload do Simulador OctaDesk</h1>
        
        <div class="instructions">
            <h3>📋 Instruções:</h3>
            <ol>
                <li>Selecione os arquivos do simulador (index.php, monitor.html, .htaccess)</li>
                <li>Clique em "Fazer Upload"</li>
                <li>Os arquivos serão enviados para /dev/octadesk-simulator/</li>
                <li>Os diretórios necessários serão criados automaticamente</li>
            </ol>
        </div>
        
        <form method="POST" enctype="multipart/form-data" class="upload-form">
            <h3>📁 Selecionar Arquivos:</h3>
            
            <div class="file-input">
                <label for="index_php">index.php (Simulador Principal):</label><br>
                <input type="file" name="simulator_files[]" id="index_php" required>
            </div>
            
            <div class="file-input">
                <label for="monitor_html">monitor.html (Interface de Monitoramento):</label><br>
                <input type="file" name="simulator_files[]" id="monitor_html" required>
            </div>
            
            <div class="file-input">
                <label for="htaccess">.htaccess (Configuração Apache):</label><br>
                <input type="file" name="simulator_files[]" id="htaccess" required>
            </div>
            
            <button type="submit" class="btn">🚀 Fazer Upload</button>
        </form>
        
        <div class="instructions">
            <h3>🔧 Após o Upload:</h3>
            <ul>
                <li><strong>Interface:</strong> <a href="/dev/octadesk-simulator/monitor.html" target="_blank">/dev/octadesk-simulator/monitor.html</a></li>
                <li><strong>API Health:</strong> <a href="/dev/octadesk-simulator/api/v1/health" target="_blank">/dev/octadesk-simulator/api/v1/health</a></li>
                <li><strong>API Info:</strong> <a href="/dev/octadesk-simulator/api/v1/info" target="_blank">/dev/octadesk-simulator/api/v1/info</a></li>
            </ul>
        </div>
    </body>
    </html>
    <?php
}

?>
