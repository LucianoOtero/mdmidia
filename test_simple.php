<?php
// Versão simplificada para teste
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Teste básico funcionando!<br>";
echo "Data/Hora: " . date('Y-m-d H:i:s') . "<br>";

// Teste de inclusão do class.php
if (file_exists('class.php')) {
    echo "✅ class.php encontrado<br>";
    try {
        require_once('class.php');
        echo "✅ class.php incluído<br>";
        
        if (class_exists('EspoApiClient')) {
            echo "✅ Classe EspoApiClient encontrada<br>";
        } else {
            echo "❌ Classe EspoApiClient não encontrada<br>";
        }
    } catch (Exception $e) {
        echo "❌ Erro: " . $e->getMessage() . "<br>";
    }
} else {
    echo "❌ class.php não encontrado<br>";
}

echo "<br>Teste concluído!";
?>

