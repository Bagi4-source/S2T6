<?php
header('Content-Type: text/html; charset=UTF-8');

session_start();
session_destroy();

if (isset($_SERVER['HTTP_REFERER'])) {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
} else {
    header('Location: ../');
}
