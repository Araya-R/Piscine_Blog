<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController{

    #[Route("/create-article", name:"create-article")]
    public function displayCreateArticle(Request $request, EntityManagerInterface $entityManager){
       
        if ($request->isMethod("POST")){
            $title=$request->request->get('title');
            $description=$request->request->get('description');
            $content=$request->request->get('content');
            $image=$request->request->get('image');

            //Méthode 1 : création de l'objet Article (instance de l'entité Article)
            // j'affecte les valeurs récupéres via les méthodes "setters"
            // $article = new Article();
            // $article->setTitle($title);
            // $article->setDescription($description);
            // $article->setContent($content);
            // $article->setImage($image);
            // $article->setCreatedAt(new \DateTimeImmutable());
            // $article->setIsPublished(true);

            //Méthode 2 : Creer l'article via le constructeur
            $article = new Article($title,$description,$content,$image);

            //enregistrement de l'article en BD
            $entityManager ->persist($article);
            $entityManager ->flush();
        }
        return $this->render('create-article.html.twig');
    }
}