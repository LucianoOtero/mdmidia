# TESTE SIMPLES DO ENDPOINT add_collect_chat.php NO MDMIDIA
# PowerShell Script para teste rápido

Write-Host "=== TESTE DO ENDPOINT add_collect_chat.php ===" -ForegroundColor Green
Write-Host "Servidor: mdmidia.com.br" -ForegroundColor Yellow
Write-Host "Data/Hora: $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')" -ForegroundColor Yellow
Write-Host "==============================================" -ForegroundColor Green
Write-Host ""

# Dados fictícios para teste
$testData = @{
    NAME = "TESTE POWERSHELL - $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')"
    NUMBER = "11999887766"
    CPF = "12345678901"
    CEP = "01234567"
    PLACA = "ABC1234"
    EMAIL = "teste@exemplo.com"
    gclid = "test_gclid_$(Get-Date -Format 'yyyyMMddHHmmss')"
}

Write-Host "📋 DADOS DE TESTE:" -ForegroundColor Cyan
foreach ($key in $testData.Keys) {
    Write-Host "   $key`: $($testData[$key])" -ForegroundColor White
}
Write-Host ""

# Configuração do endpoint
$endpoint = "https://mdmidia.com.br/add_collect_chat.php"

Write-Host "🌐 ENDPOINT: $endpoint" -ForegroundColor Yellow
Write-Host "📤 Enviando dados..." -ForegroundColor Yellow
Write-Host ""

try {
    # Fazer a requisição
    $startTime = Get-Date
    
    $response = Invoke-RestMethod -Uri $endpoint -Method POST -Body ($testData | ConvertTo-Json) -ContentType "application/json" -TimeoutSec 30
    
    $endTime = Get-Date
    $executionTime = ($endTime - $startTime).TotalMilliseconds
    
    Write-Host "✅ STATUS: SUCESSO" -ForegroundColor Green
    Write-Host "⏱️  Tempo de Execução: $([math]::Round($executionTime, 2))ms" -ForegroundColor Yellow
    Write-Host ""
    
    Write-Host "📥 RESPOSTA DO SERVIDOR:" -ForegroundColor Cyan
    Write-Host "========================" -ForegroundColor Cyan
    
    # Exibir resposta formatada
    $response | ConvertTo-Json -Depth 10 | Write-Host -ForegroundColor White
    
    Write-Host ""
    Write-Host "🎯 ANÁLISE DA RESPOSTA:" -ForegroundColor Cyan
    if ($response.status) {
        Write-Host "   - Status: $($response.status)" -ForegroundColor White
    }
    if ($response.message) {
        Write-Host "   - Mensagem: $($response.message)" -ForegroundColor White
    }
    if ($response.leadIdTravelAngels) {
        Write-Host "   - Lead TravelAngels: $($response.leadIdTravelAngels)" -ForegroundColor Green
    }
    if ($response.leadIdFlyingDonkeys) {
        Write-Host "   - Lead FlyingDonkeys: $($response.leadIdFlyingDonkeys)" -ForegroundColor Green
    }
    
} catch {
    Write-Host "❌ STATUS: FALHA" -ForegroundColor Red
    Write-Host "🚨 Erro: $($_.Exception.Message)" -ForegroundColor Red
    
    if ($_.Exception.Response) {
        Write-Host "📡 HTTP Code: $($_.Exception.Response.StatusCode)" -ForegroundColor Red
    }
}

Write-Host ""
Write-Host "🔍 PRÓXIMOS PASSOS:" -ForegroundColor Cyan
Write-Host "===================" -ForegroundColor Cyan
Write-Host "1. Verificar logs no servidor mdmidia:" -ForegroundColor White
Write-Host "   - Arquivo: collect_chat_logs.txt" -ForegroundColor Gray
Write-Host "   - Buscar por: '$($testData.NAME)'" -ForegroundColor Gray
Write-Host ""
Write-Host "2. Verificar criação no TravelAngels:" -ForegroundColor White
Write-Host "   - URL: https://travelangels.com.br" -ForegroundColor Gray
Write-Host "   - Buscar por email: $($testData.EMAIL)" -ForegroundColor Gray
Write-Host ""
Write-Host "3. Verificar criação no FlyingDonkeys:" -ForegroundColor White
Write-Host "   - URL: https://flyingdonkeys.com.br" -ForegroundColor Gray
Write-Host "   - Buscar por email: $($testData.EMAIL)" -ForegroundColor Gray
Write-Host ""
Write-Host "4. Verificar oportunidade criada:" -ForegroundColor White
Write-Host "   - Verificar se Lead foi convertido em Oportunidade" -ForegroundColor Gray
Write-Host "   - Verificar campos de origem (source/leadSource)" -ForegroundColor Gray
Write-Host ""

Write-Host "=== FIM DO TESTE ===" -ForegroundColor Green
Write-Host "Data/Hora: $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')" -ForegroundColor Yellow
