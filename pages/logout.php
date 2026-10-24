<?php
require_once __DIR__ . '/../includes/config.php';

(new Auth())->logout();
redirect(url('pages/login.php'));
