<?php

$migrationsDir = __DIR__ . '/database/migrations';
$files = glob($migrationsDir . '/*.php');

foreach ($files as $file) {
    $content = file_get_contents($file);

    // Check if withinTransaction is already present
    if (strpos($content, 'withinTransaction') !== false) {
        continue;
    }

    // Add the withinTransaction property
    $pattern = '/(return new class extends Migration\s*\n\s*\{\n)/';
    $replacement = "$1    /**\n     * Indicates if the migration should run in a transaction.\n     *\n     * @var bool\n     */\n    public \$withinTransaction = false;\n\n";

    $newContent = preg_replace($pattern, $replacement, $content);

    if ($newContent !== $content) {
        file_put_contents($file, $newContent);
        echo "Fixed: " . basename($file) . "\n";
    }
}

echo "Done!\n";
