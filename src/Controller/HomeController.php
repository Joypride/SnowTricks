<?php 

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class HomeController 
{
    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    public function index() : Response
    {
        return new Response($this->twig->render('index.html'));
    }
}

