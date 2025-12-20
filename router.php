<?php
// router.php — custom router for PHP built-in server.
// Routes requests for /images/ProductImages/* to Laravel so CORS headers
// added by your Laravel route will be present. Other existing static
// files are still served directly.

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$publicPath = __DIR__ . '/public' . $uri;

// If requesting product images, route to Laravel front controller so
// the route in routes/web.php that sets Access-Control-Allow-Origin runs.
if (strpos($uri, '/images/ProductImages/') === 0) {
    require_once __DIR__ . '/public/index.php';
    return;
}

// If request maps to an existing public file, let the built-in server serve it.
if ($uri !== '/' && file_exists($publicPath)) {
    return false;
}

// Otherwise route to Laravel front controller.
require_once __DIR__ . '/public/index.php';
