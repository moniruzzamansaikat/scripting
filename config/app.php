<?php

$baseUrl = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');  // This will return /testphp if in a subfolder

return [
    'base_url' => $baseUrl,
];
