<?php
require '../includes/config.php';

session_destroy();
redirect('login.php');
