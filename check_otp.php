<?php
$c = file_get_contents('d:/xamp/htdocs/launchshop_dev/resources/views/front/step.blade.php');
if (strpos($c, '#otp-display-code') !== false) {
    echo "STILL THERE\n";
} else {
    echo "REMOVED\n";
}
