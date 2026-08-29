<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tickets = \App\Models\Ticket::all();
foreach ($tickets as $t) {
    echo "Ticket #{$t->ticket_number} (ID: {$t->id}): attachment='{$t->attachment}'\n";
}

$replies = \App\Models\TicketReply::all();
foreach ($replies as $r) {
    echo "Reply (ID: {$r->id}): attachment='{$r->attachment}'\n";
}
