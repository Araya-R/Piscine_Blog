<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class ArticleController extends AbstractController
{

    #[Route("/create-article", name: "create-article")]

    //On instancie la classe EntityManager dans la méthode
    public function displayCreateArticle(Request $request, EntityManagerInterface $entityManager)
    {

        if ($request->isMethod("POST")) {
            $title = $request->request->get('title');
            $description = $request->request->get('description');
            $content = $request->request->get('content');
            $image = $request->request->get('image');

            //Méthode 1 : création de l'objet Article (instance de l'entité Article)
            // j'affecte les valeurs récupérées via les méthodes "setters"
            // $article = new Article();
            // $article->setTitle($title);
            // $article->setDescription($description);
            // $article->setContent($content);
            // $article->setImage($image);
            // $article->setCreatedAt(new \DateTimeImmutable());
            // $article->setIsPublished(true);
            //$article ->setCreatedA(new \DateTime());

            //Méthode 2 : Creer l'article via le constructeur
            $article = new Article($title, $description, $content, $image);

            //enregistrement de l'article en BD
            //cela marche que si on a défini le mapping 
            $entityManager->persist($article);
            $entityManager->flush();
        }
        return $this->render('create-article.html.twig');
    }

    #[Route('/list-articles', name: "list-articles")]

    //Symfony va automatiquement injecter une instance de ArticleRepository (autowiring)
    //ArticleRepository est une classe générée automatiquement par Symfony/Doctrine
    public function DisplayArticles(ArticleRepository $ArticleRepository)
    {
        $articles = $ArticleRepository->findAll();
        //on peut maintenant utiliser $ArticleRepository pour accéder aux données
        //il récupère tous les articles dans la BDD
        //findAll() = méthode fournie par la class ServiceEntityRepository (extends)

        return $this->render('list-articles.html.twig', ['articles' => $articles]);
    }

    #[Route('/detail-article/{id}', name: "detail-article")]

    //ArticleRepository est injecté par Symfony (autowiring)
    //$id = {id} dans l'URL 
    public function DisplayDetailArticle($id, ArticleRepository $ArticleRepository)
    {

        //on utilise la méthode findOneById générée automatiquement par Doctrine
        //qui cherche un seul article en BDD avec id = $id 
        $article = $ArticleRepository->findOneById($id);

        //si $article existe alors cç redirige vers la page 404
        if (!$article) {
            return $this->redirectToRoute('404');
        }
        return $this->render('detail-article.html.twig', ['article' => $article]);
    }

    #[Route("/delete-article/{id}", name:"delete-article")]
    public function deleteArticle ($id, ArticleRepository $ArticleRepository, EntityManagerInterface $entityManager){
        //récupérer l'article dans ArticleRepository à supprimer 
        $article = $ArticleRepository->findOneById($id);

        //supprimer l'article de la BDD
        $entityManager->remove($article);
        $entityManager->flush();

        //on ajout un message flash
        $this->addFlash('success', 'Article supprimé');

        //rediriger vers la page list-articles
        return $this->redirectToRoute('list-articles');
    }

    #[Route("/update-article/{id}", name:"update-article")]
    public function displayUpdateArticle($id, Request $request, ArticleRepository $ArticleRepository, EntityManagerInterface $entityManager){
        $article = $ArticleRepository->findOneById($id);

        //verification de la méthode de la requête POST
        if ($request->isMethod("POST")) {

            //on récupère les données envoyées par le formulaire
            $title = $request->request->get('title');
            $description = $request->request->get('description');
            $content = $request->request->get('content');
            $image = $request->request->get('image');

            //mise à jour des l'article avec les nouvelles données 
            // via la méthode update créée dans l'entity
            $article->update($title, $description, $content, $image);

            //sauvegarde l'article
            $entityManager->persist($article);
            $entityManager->flush();
        }

        return $this->render('update-article.html.twig', ['article' => $article]);

    }

}
