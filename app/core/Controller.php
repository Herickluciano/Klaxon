<?php
require_once '../app/models/User.php';

$user = new User();
$data = $user->getAll();
