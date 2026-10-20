<?php
$file = 'd:/xamp/htdocs/launchshop_dev/resources/views/front/step.blade.php';
$content = file_get_contents($file);

// Remove the JS lines that auto-fill the OTP
$content = preg_replace("/\/\/\s*Show OTP prominently and auto-fill input\s*if\s*\([^)]+\)\s*\{\s*\\$\('#otp-display-code'\)\.text\([^)]+\);\s*\\$\('#otp-display-banner'\)\.removeClass\([^)]+\);\s*\\$\('#otp_code'\)\.val\([^)]+\);\s*\}/s", "", $content);

// And to be safe, just remove any remaining val(response.otp)
$content = preg_replace("/\\$\('#otp_code'\)\.val\(response\.otp\);/", "", $content);

file_put_contents($file, $content);
echo "Fixed step.blade.php JS\n";
