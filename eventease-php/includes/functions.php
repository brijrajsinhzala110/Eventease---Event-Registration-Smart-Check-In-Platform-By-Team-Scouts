<?php
function h($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

function generate_ticket_code() {
    return 'TKT-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
}

function redirect($url) {
    header("Location: $url");
    exit;
}

function format_date($datetime) {
    return date('M d, Y h:i A', strtotime($datetime));
}
