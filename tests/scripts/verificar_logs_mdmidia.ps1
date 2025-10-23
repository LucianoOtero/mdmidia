# Script para verificar logs do mdmidia.com.br
# Como não temos acesso SSH direto, vamos tentar acessar via HTTP

Write-Host "=== VERIFICANDO LOGS MDMIDIA.COM.BR ===" -ForegroundColor Yellow

# Tentar acessar possíveis arquivos de log
$possibleLogFiles = @(
    "logs_collect_chat.txt",
    "collect_chat_logs.txt", 
    "logs_travelangels.txt",
    "octa_webflow_webhook.log"
)

foreach ($logFile in $possibleLogFiles) {
    Write-Host "Tentando acessar: https://mdmidia.com.br/$logFile" -ForegroundColor Cyan
    
    try {
        $response = Invoke-WebRequest -Uri "https://mdmidia.com.br/$logFile" -TimeoutSec 10 -ErrorAction Stop
        
        if ($response.StatusCode -eq 200) {
            Write-Host "✅ Arquivo encontrado: $logFile" -ForegroundColor Green
            Write-Host "Tamanho: $($response.Content.Length) bytes" -ForegroundColor Green
            
            # Buscar pelo nosso teste
            $testName = "TESTE MDMIDIA - 2025-10-22 11:41:16"
            if ($response.Content -match $testName) {
                Write-Host "✅ Teste encontrado no log!" -ForegroundColor Green
                
                # Extrair as últimas linhas que contêm nosso teste
                $lines = $response.Content -split "`n"
                $relevantLines = $lines | Where-Object { $_ -match $testName -or $_ -match "2025-10-22 11:41" }
                
                Write-Host "`n--- LOGS RELEVANTES ---" -ForegroundColor Yellow
                foreach ($line in $relevantLines) {
                    Write-Host $line -ForegroundColor White
                }
            } else {
                Write-Host "❌ Teste não encontrado no log" -ForegroundColor Red
                Write-Host "Últimas 5 linhas do arquivo:" -ForegroundColor Yellow
                $lines = $response.Content -split "`n"
                $lastLines = $lines | Select-Object -Last 5
                foreach ($line in $lastLines) {
                    Write-Host $line -ForegroundColor White
                }
            }
            break
        }
    }
    catch {
        Write-Host "❌ Erro ao acessar $logFile : $($_.Exception.Message)" -ForegroundColor Red
    }
}

Write-Host "`n=== VERIFICAÇÃO CONCLUÍDA ===" -ForegroundColor Yellow
