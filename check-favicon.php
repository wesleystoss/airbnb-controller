<?php
// Script para verificar favicons
echo "=== Verificação de Favicons ===\n\n";

$files = [
    'public/favicon.svg',
    'public/favicon.ico', 
    'public/favicon.png'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        $size = filesize($file);
        echo "✅ $file - $size bytes\n";
    } else {
        echo "❌ $file - Não encontrado\n";
    }
}

echo "\n=== URLs de Teste ===\n";
echo "SVG: /favicon.svg\n";
echo "ICO: /favicon.ico\n";
echo "PNG: /favicon.png\n";

echo "\n=== Verificação de Headers ===\n";
$headers = [
    'Content-Type: image/svg+xml',
    'Content-Type: image/x-icon', 
    'Content-Type: image/png'
];

foreach ($headers as $header) {
    echo "Header esperado: $header\n";
}

echo "\n=== Soluções para Hostinger ===\n";
echo "1. Verifique se os arquivos foram enviados para o servidor\n";
echo "2. Teste acessando diretamente: https://seudominio.com/favicon.svg\n";
echo "3. Use ferramentas online para converter o SVG para ICO/PNG\n";
echo "4. Verifique as permissões dos arquivos no servidor\n";
?> 