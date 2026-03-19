<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Victi\GameLoggd\Controllers\AuthController;
use Victi\GameLoggd\Controllers\GameController;


$action = $_GET['action'] ?? 'home';


switch ($action) {
    // --- ROTAS DE AUTENTICAÇÃO ---
    case 'login':
        $controller = new AuthController();
        $controller->login();
        break;
    case 'register':
        $controller = new AuthController();
        $controller->register();
        break;
    case 'logout':
        $controller = new AuthController();
        $controller->logout();
        break;
    // --- ROTAS DE JOGOS (Ações) ---
    case 'add_game':
        $controller = new GameController();
        $controller->add();
        break;
    case 'delete_game':
        $controller = new GameController();
        $controller->delete();
        break;
    case 'change_status':
        $controller = new GameController();
        $controller->changeStatus();
        break;

    // --- ROTAS DE JOGOS (Páginas visuais) ---
    case 'search':
        $controller = new GameController();
        $controller->search();
        break;
    case 'ajax_search':
        $controller = new GameController();
        $controller->ajaxSearch();
        break;
    case 'details':
        $controller = new GameController();
        $controller->details();
        break;
    case 'save_review':
        $controller = new GameController();
        $controller->saveReview(); 
        break;

    // --- PÁGINA INICIAL ---
    case 'home':
    default:
        $controller = new GameController();
        $controller->index();
        break;
}