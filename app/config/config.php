<?php

define('DB_HOST', $_ENV['DB_HOST'] ?? 'db');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'isla_transfers');
define('DB_USER', $_ENV['DB_USER'] ?? 'user');
define('DB_PASS', $_ENV['DB_PASS'] ?? 'pass');

define('APP_URL', $_ENV['APP_URL'] ?? 'http://localhost:8080');

define('APP_ROOT', dirname(dirname(__FILE__)));
