#!/bin/bash

URL="https://mdmidia.com.br/add_collect_chat.php"

echo "=== Teste 1: Enviando JSON ==="
curl -X POST $URL \
  -H "Content-Type: application/json" \
  -d '{"NAME":"Luciano Otero Teste - Não Ligar","NUMBER":"11999999999","gclid":"CLjTtYbH9_kCFYyJtwodmrQJvA"}'
echo -e "\n"

echo "=== Teste 2: Enviando Form-Data ==="
curl -X POST $URL \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "NAME=Luciano+Otero+Teste+Nao+Ligar&NUMBER=11999999999&gclid=CLjTtYbH9_kCFYyJtwodmrQJvA"
echo -e "\n"

echo "=== Teste 3: gclid como URL ==="
curl -X POST $URL \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "NAME=Luciano&NUMBER=11999999999&gclid=https://www.google.com/?gclid=XYZ123"
echo -e "\n"

echo "=== Testes Finalizados ==="
