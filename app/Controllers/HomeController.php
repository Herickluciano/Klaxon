<?php

class HomeController {
    public function index() {
        require __DIR__ . 'app/View/home/index.php';
    }

    public function about() {
        require __DIR__ . 'app/View/home/about.php';
    }

     public function contact() {
        require __DIR__ . 'app/View/home/contact.php';
    }

     public function login() {
        require __DIR__ . 'public/login.php';
    }
     public function index_visiteur() {
        require __DIR__ . 'app/View/home/index_visiteur.php';
    }
    public function index_utilisateur() {
        require __DIR__ . 'app/View/home/index_utilisateur.php';
    }
    public function index_agence() {
        require __DIR__ . 'app/View/home/index_agence.php';
    }
}
