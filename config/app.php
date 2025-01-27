<?php

// Detect the subfolder dynamically (if the project is in a subfolder like /testphp)
$baseUrl = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');  // This will return /testphp if in a subfolder

return [
    'base_url' => $baseUrl,
];
