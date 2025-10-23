#!/bin/bash

# Script para corrigir add_travelangels.php no ambiente de desenvolvimento
# Adicionar criação de Oportunidade com leadSource correto

FILE_PATH="/var/www/html/dev/webhooks/add_travelangels.php"
TEMP_FILE="/tmp/add_travelangels_fixed.php"

echo "Corrigindo arquivo: $FILE_PATH"

# Criar arquivo temporário com a correção
cat > "$TEMP_FILE" << 'EOF'
    // Criar lead no CRM
    $response = $client->request('POST', 'Lead', $lead_data);
    
    logDevWebhook('crm_response', [
        'status' => 'success',
        'response' => $response
    ], true);
    
    // Criar oportunidade no FlyingDonkeys (CORREÇÃO ADICIONADA)
    $opportunityPayload = [
        'name' => $data['name'] ?? 'Nome não informado',
        'leadId' => $response['id'] ?? 'unknown',
        'stage' => 'Novo Sem Contato',
        'amount' => 0,
        'probability' => 10,
        'leadSource' => 'Webflow Dev', // ✅ CORRETO para Opportunity
        'description' => 'Oportunidade criada no ambiente de desenvolvimento'
    ];
    
    logDevWebhook('opportunity_data_prepared', $opportunityPayload, true);
    
    $opportunityResponse = $client->request('POST', 'Opportunity', $opportunityPayload);
    
    logDevWebhook('opportunity_response', [
        'status' => 'success',
        'response' => $opportunityResponse
    ], true);
    
    sendDevWebhookResponse(true, 'Lead e Oportunidade criados com sucesso no ambiente de desenvolvimento', [
        'lead_id' => $response['id'] ?? 'unknown',
        'opportunity_id' => $opportunityResponse['id'] ?? 'unknown',
        'lead_response' => $response,
        'opportunity_response' => $opportunityResponse,
        'environment' => 'development'
    ]);
EOF

# Substituir a seção no arquivo original
sed -i '/\/\/ Criar lead no CRM/,/sendDevWebhookResponse.*Lead criado com sucesso.*development.*{/,/});/c\
    // Criar lead no CRM\
    $response = $client->request('\''POST'\'', '\''Lead'\'', $lead_data);\
    \
    logDevWebhook('\''crm_response'\'', [\
        '\''status'\'' => '\''success'\'',\
        '\''response'\'' => $response\
    ], true);\
    \
    // Criar oportunidade no FlyingDonkeys (CORREÇÃO ADICIONADA)\
    $opportunityPayload = [\
        '\''name'\'' => $data['\''name'\''] ?? '\''Nome não informado'\'',\
        '\''leadId'\'' => $response['\''id'\''] ?? '\''unknown'\'',\
        '\''stage'\'' => '\''Novo Sem Contato'\'',\
        '\''amount'\'' => 0,\
        '\''probability'\'' => 10,\
        '\''leadSource'\'' => '\''Webflow Dev'\'', // ✅ CORRETO para Opportunity\
        '\''description'\'' => '\''Oportunidade criada no ambiente de desenvolvimento'\''\
    ];\
    \
    logDevWebhook('\''opportunity_data_prepared'\'', $opportunityPayload, true);\
    \
    $opportunityResponse = $client->request('\''POST'\'', '\''Opportunity'\'', $opportunityPayload);\
    \
    logDevWebhook('\''opportunity_response'\'', [\
        '\''status'\'' => '\''success'\'',\
        '\''response'\'' => $opportunityResponse\
    ], true);\
    \
    sendDevWebhookResponse(true, '\''Lead e Oportunidade criados com sucesso no ambiente de desenvolvimento'\'', [\
        '\''lead_id'\'' => $response['\''id'\''] ?? '\''unknown'\'',\
        '\''opportunity_id'\'' => $opportunityResponse['\''id'\''] ?? '\''unknown'\'',\
        '\''lead_response'\'' => $response,\
        '\''opportunity_response'\'' => $opportunityResponse,\
        '\''environment'\'' => '\''development'\''\
    ]);' "$FILE_PATH"

echo "Correção aplicada com sucesso!"
echo "Verificando se a correção foi aplicada..."

# Verificar se a correção foi aplicada
if grep -q "leadSource.*Webflow Dev" "$FILE_PATH"; then
    echo "✅ Correção aplicada com sucesso!"
    echo "✅ Campo leadSource encontrado para Opportunity"
else
    echo "❌ Erro na aplicação da correção"
    exit 1
fi

# Limpar arquivo temporário
rm -f "$TEMP_FILE"

echo "Arquivo corrigido: $FILE_PATH"


