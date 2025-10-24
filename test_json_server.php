<?php

// Teste do JSON exato que está sendo enviado pelo Webflow
$webflow_json = '{"triggerType":"form_submission","payload": "{\"name\":\"Home\"\",\"siteId\":\"68f77ea29d6b098f6bcad795\",\"data\": \"{\"NOME\":\"LUCIANO RODRIGUES OTERO\"\",\"DDD-CELULAR\":\"11\",\"CELULAR\":\"97668-7668\",\"Email\":\"LROTERO@GMAIL.COM\",\"CEP\":\"03317-000\",\"CPF\":\"085.546.078-48\",\"PLACA\":\"FPG-8D63\",\"ANO\":\"2016\",\"MARCA\":\"NISSAN \/ MARCH 16SV\",\"GCLID_FLD\":\"\",\"SEQUENCIA_FLD\":\"\"},\"submittedAt\":\"2025-10-23T20:35:21.558Z\",\"id\":\"68fa918a821543fed64c6a05\",\"formId\":\"68f788bd5dc3f2ca4483eee0\",\"formElementId\":\"97e5c20e-4fe9-8fcf-d941-485bbc20f783\",\"pageId\":\"68f77ea29d6b098f6bcad76f\",\"publishedPath\":\"\/\",\"pageUrl\":\"https: \"\/\/segurosimediato-8119bf26e77bf4ff336a58e.webflow.io\/\"\",\"schema\": \"[{\"fieldName\":\"NOME\"\",\"fieldType\":\"FormTextInput\",\"fieldElementId\":\"97e5c20e-4fe9-8fcf-d941-485bbc20f784\"},{\"fieldName\":\"DDD-CELULAR\",\"fieldType\":\"FormTextInput\",\"fieldElementId\":\"97e5c20e-4fe9-8fcf-d941-485bbc20f788\"},{\"fieldName\":\"CELULAR\",\"fieldType\":\"FormTextInput\",\"fieldElementId\":\"97e5c20e-4fe9-8fcf-d941-485bbc20f789\"},{\"fieldName\":\"Email\",\"fieldType\":\"FormTextInput\",\"fieldElementId\":\"97e5c20e-4fe9-8fcf-d941-485bbc20f78c\"},{\"fieldName\":\"CEP\",\"fieldType\":\"FormTextInput\",\"fieldElementId\":\"97e5c20e-4fe9-8fcf-d941-485bbc20f78d\"},{\"fieldName\":\"CPF\",\"fieldType\":\"FormTextInput\",\"fieldElementId\":\"97e5c20e-4fe9-8fcf-d941-485bbc20f78e\"},{\"fieldName\":\"PLACA\",\"fieldType\":\"FormTextInput\",\"fieldElementId\":\"97e5c20e-4fe9-8fcf-d941-485bbc20f78f\"},{\"fieldName\":\"ANO\",\"fieldType\":\"FormTextInput\",\"fieldElementId\":\"97e5c20e-4fe9-8fcf-d941-485bbc20f793\"},{\"fieldName\":\"MARCA\",\"fieldType\":\"FormTextInput\",\"fieldElementId\":\"ef8b88ef-5af9-ad83-b8f8-6d1162990897\"},{\"fieldName\":\"TIPO DE VEICULO\",\"fieldType\":\"FormTextInput\",\"fieldElementId\":\"3e947067-8663-3df0-12dc-782341d616f4\"},{\"fieldName\":\"SEXO\",\"fieldType\":\"FormTextInput\",\"fieldElementId\":\"f904a0c9-0bb9-b3d3-a659-c3375f8089b1\"},{\"fieldName\":\"DATA DE NASCIMENTO\",\"fieldType\":\"FormTextInput\",\"fieldElementId\":\"eac9690f-4906-3385-0a99-b81fd097eec1\"},{\"fieldName\":\"ESTADO CIVIL\",\"fieldType\":\"FormTextInput\",\"fieldElementId\":\"c0f22d46-d387-e8c6-5fb1-a2608e609be2\"}]}}"}';

echo "Testando JSON no servidor:\n";
echo "==========================\n";

$data = json_decode($webflow_json, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo "❌ ERRO: " . json_last_error_msg() . "\n";
    echo "Código do erro: " . json_last_error() . "\n";
} else {
    echo "✅ JSON válido!\n";
    echo "Dados decodificados:\n";
    print_r($data);
}
