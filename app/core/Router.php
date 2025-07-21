<?php

class Router {
    public function dispatch() {
    $url = $_GET['url'] ?? 'home/index';
    $parts = explode('/', trim($url, '/'));

    // Contrôleur = première partie (HomeController)
    $controllerName = ucfirst($parts[0]) . 'Controller';

    // Méthode = deuxième partie ou 'index' par défaut
    $method = $parts[1] ?? '';

    // Paramètres = tout ce qui vient après
    $params = array_slice($parts, 2);

    $controllerFile = "../app/controllers/$controllerName.php";

    if (file_exists($controllerFile)) {
        require_once $controllerFile;
        $controller = new $controllerName();

        if (method_exists($controller, $method)) {
            call_user_func_array([$controller, $method], $params);
        } else {
            echo "Méthode $method introuvable.";
        }
    } else {
        echo "Contrôleur $controllerName introuvable.";
    }
}

}