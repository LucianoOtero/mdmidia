<?php
// octa_test_hc.php — Healthcheck + CORS + Test OK sempre

// CORS ultra permissivo p/ teste
$origin = $_SERVER['HTTP_ORIGIN'] ?? '*';
header("Access-Control-Allow-Origin: $origin");
header("Vary: Origin");
header("Access-Control-Allow-Credentials: false");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Max-Age: 86400");

// Preflight
if (strcasecmp($_SERVER['REQUEST_METHOD'] ?? 'GET', 'OPTIONS') === 0) {
  http_response_code(204);
  exit;
}

// GET ou POST vazio = Test Signal
$raw = file_get_contents('php://input') ?: '';
if ($_SERVER['REQUEST_METHOD'] === 'GET' || trim($raw) === '') {
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode(['status'=>'success','note'=>'test endpoint ok']);
  exit;
}

// Se vier payload (lead real), só espelha p/ debug
header('Content-Type: application/json; charset=utf-8');
echo json_encode([
  'status' => 'success',
  'note'   => 'payload received',
  'length' => strlen($raw),
  'ct'     => $_SERVER['CONTENT_TYPE'] ?? '',
  'raw'    => $raw,
], JSON_UNESCAPED_SLASHES);
