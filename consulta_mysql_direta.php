<?php
/**
 * CONSULTA DIRETA NO BANCO DE DADOS MYSQL
 * Conecta diretamente no MySQL para verificar os dados nas tabelas
 */

echo "=== CONSULTA DIRETA NO BANCO DE DADOS MYSQL ===\n\n";

// Configurações do banco de dados
$host = '46.62.231.90'; // IP do servidor dev.flyingdonkeys.com.br
$username = 'root';
$password = 'root'; // Senha padrão, pode precisar ser ajustada
$database = 'espocrm'; // Nome padrão do banco EspoCRM

echo "Conectando no banco de dados...\n";
echo "Host: $host\n";
echo "Database: $database\n\n";

try {
    // Conectar no MySQL
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Conexão com MySQL estabelecida!\n\n";
    
    // 1. Verificar tabelas disponíveis
    echo "1. TABELAS DISPONÍVEIS:\n";
    $tables_query = "SHOW TABLES";
    $tables_stmt = $pdo->query($tables_query);
    $tables = $tables_stmt->fetchAll(PDO::FETCH_COLUMN);
    
    foreach ($tables as $table) {
        echo "  - $table\n";
    }
    echo "\n";
    
    // 2. Verificar estrutura da tabela lead
    if (in_array('lead', $tables)) {
        echo "2. ESTRUTURA DA TABELA LEAD:\n";
        $structure_query = "DESCRIBE lead";
        $structure_stmt = $pdo->query($structure_query);
        $structure = $structure_stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($structure as $column) {
            echo "  - " . $column['Field'] . " (" . $column['Type'] . ")\n";
        }
        echo "\n";
        
        // 3. Consultar leads mais recentes
        echo "3. LEADS MAIS RECENTES (últimos 10):\n";
        $leads_query = "SELECT id, first_name, last_name, name, email_address, phone_number, source, status, description, created_at, created_by_id FROM lead ORDER BY created_at DESC LIMIT 10";
        $leads_stmt = $pdo->query($leads_query);
        $leads = $leads_stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($leads) > 0) {
            foreach ($leads as $index => $lead) {
                echo "Lead " . ($index + 1) . ":\n";
                echo "  ID: " . ($lead['id'] ?? 'N/A') . "\n";
                echo "  Name: " . ($lead['name'] ?? 'N/A') . "\n";
                echo "  First Name: " . ($lead['first_name'] ?? 'N/A') . "\n";
                echo "  Last Name: " . ($lead['last_name'] ?? 'N/A') . "\n";
                echo "  Email: " . ($lead['email_address'] ?? 'N/A') . "\n";
                echo "  Phone: " . ($lead['phone_number'] ?? 'N/A') . "\n";
                echo "  Source: " . ($lead['source'] ?? 'N/A') . "\n";
                echo "  Status: " . ($lead['status'] ?? 'N/A') . "\n";
                echo "  Description: " . ($lead['description'] ?? 'N/A') . "\n";
                echo "  Created: " . ($lead['created_at'] ?? 'N/A') . "\n";
                echo "  Created By: " . ($lead['created_by_id'] ?? 'N/A') . "\n";
                echo "\n";
            }
        } else {
            echo "  Nenhum lead encontrado.\n\n";
        }
    } else {
        echo "❌ Tabela 'lead' não encontrada.\n\n";
    }
    
    // 4. Verificar estrutura da tabela opportunity
    if (in_array('opportunity', $tables)) {
        echo "4. ESTRUTURA DA TABELA OPPORTUNITY:\n";
        $opp_structure_query = "DESCRIBE opportunity";
        $opp_structure_stmt = $pdo->query($opp_structure_query);
        $opp_structure = $opp_structure_stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($opp_structure as $column) {
            echo "  - " . $column['Field'] . " (" . $column['Type'] . ")\n";
        }
        echo "\n";
        
        // 5. Consultar oportunidades mais recentes
        echo "5. OPORTUNIDADES MAIS RECENTES (últimas 10):\n";
        $opp_query = "SELECT id, name, lead_id, stage, amount, probability, lead_source, description, created_at, created_by_id FROM opportunity ORDER BY created_at DESC LIMIT 10";
        $opp_stmt = $pdo->query($opp_query);
        $opportunities = $opp_stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($opportunities) > 0) {
            foreach ($opportunities as $index => $opp) {
                echo "Oportunidade " . ($index + 1) . ":\n";
                echo "  ID: " . ($opp['id'] ?? 'N/A') . "\n";
                echo "  Name: " . ($opp['name'] ?? 'N/A') . "\n";
                echo "  Lead ID: " . ($opp['lead_id'] ?? 'N/A') . "\n";
                echo "  Stage: " . ($opp['stage'] ?? 'N/A') . "\n";
                echo "  Amount: " . ($opp['amount'] ?? 'N/A') . "\n";
                echo "  Probability: " . ($opp['probability'] ?? 'N/A') . "\n";
                echo "  Lead Source: " . ($opp['lead_source'] ?? 'N/A') . "\n";
                echo "  Description: " . ($opp['description'] ?? 'N/A') . "\n";
                echo "  Created: " . ($opp['created_at'] ?? 'N/A') . "\n";
                echo "  Created By: " . ($opp['created_by_id'] ?? 'N/A') . "\n";
                echo "\n";
            }
        } else {
            echo "  Nenhuma oportunidade encontrada.\n\n";
        }
    } else {
        echo "❌ Tabela 'opportunity' não encontrada.\n\n";
    }
    
    // 6. Verificar se há registros com nomes vazios
    echo "6. VERIFICAÇÃO DE REGISTROS COM NOMES VAZIOS:\n";
    
    if (in_array('lead', $tables)) {
        $empty_names_query = "SELECT COUNT(*) as total FROM lead WHERE (first_name IS NULL OR first_name = '') AND (name IS NULL OR name = '')";
        $empty_names_stmt = $pdo->query($empty_names_query);
        $empty_names_result = $empty_names_stmt->fetch(PDO::FETCH_ASSOC);
        echo "  Leads sem nome: " . $empty_names_result['total'] . "\n";
    }
    
    if (in_array('opportunity', $tables)) {
        $empty_opp_names_query = "SELECT COUNT(*) as total FROM opportunity WHERE (name IS NULL OR name = '')";
        $empty_opp_names_stmt = $pdo->query($empty_opp_names_query);
        $empty_opp_names_result = $empty_opp_names_stmt->fetch(PDO::FETCH_ASSOC);
        echo "  Oportunidades sem nome: " . $empty_opp_names_result['total'] . "\n";
    }
    
    echo "\n=== CONSULTA CONCLUÍDA ===\n";
    
} catch (PDOException $e) {
    echo "❌ ERRO DE CONEXÃO COM MYSQL:\n";
    echo "Erro: " . $e->getMessage() . "\n";
    echo "\nPossíveis soluções:\n";
    echo "1. Verificar se o MySQL está rodando no servidor\n";
    echo "2. Verificar credenciais de acesso (usuário/senha)\n";
    echo "3. Verificar se o banco 'espocrm' existe\n";
    echo "4. Verificar se o IP está correto\n";
    echo "5. Verificar se a porta 3306 está aberta\n";
}
?>
