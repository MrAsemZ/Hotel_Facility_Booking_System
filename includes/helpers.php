<?php
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function url(string $path = ''): string
{
    return BASE . '/' . ltrim($path, '/');
}

function asset(string $path): string
{
    return BASE . '/assets/' . ltrim($path, '/');
}

function redirect(string $url): void
{
    header("Location: $url");
    exit;
}

function flash(string $message, string $type = 'success'): void
{
    $_SESSION['flash'] = [$message, $type];
}

// Only allow redirecting back to pages inside this project
function safeNext(?string $next): string
{
    $next = (string) $next;
    if (strpos($next, BASE . '/') === 0 && strpos($next, '//') === false && !preg_match('/[\r\n]/', $next)) {
        return $next;
    }
    return '';
}
