<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class PageController extends AbstractController{

    #[Route("/", name:"home")]
    public function displayHome(){
       
        return $this->render('home.html.twig');
    }

    #[Route("/404", name:"404")]
    public function display404(){

        //renderView génère uniquement le contenu html sans retouner de reponse
        $html =$this->renderView('404.html.twig');

        //on crée un objet Response 
        // le contenu est le html et le code HTTP est défini 404
        return new Response($html, 404);
    }
}