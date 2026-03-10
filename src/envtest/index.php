<?php

require_once __DIR__ . '/env.php';

echo "<h1>Environment Variables from .env:</h1>";
echo "<ul>";
echo "<li>ENV1: " . ($config['ENV1'] ?: 'Not Set') . "</li>";
echo "<li>ENV2: " . ($config['ENV2'] ?: 'Not Set') . "</li>";
echo "</ul>";

?>
