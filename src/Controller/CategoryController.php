<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryForm;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;



class CategoryController extends AbstractController{

    #[Route('/create-category', name:"create-category")]
    public function DisplayCreateCategory(Request $request, EntityManagerInterface $entityManager){
        
        //je crée une nouvelle instance de Category
        $category = new Category();
        
        //créer le formulaire basé sur sur la classe CategoryForm 
        //et le lie à l'instance $category
        $categoryForm = $this->createForm(CategoryForm::class, $category);
        
        //handleRequest, méthode permettant de lier les données du formulaire à $categoryForm
        //si l'user soumet le formulaire, les données seront intégrées dans l'entité $category
        $categoryForm->handleRequest($request);

        //Vérifier si le formulaire est soumis avec la méthode isSubmitted
        if($categoryForm->isSubmitted()){

            //sauvegarde les données en BDD
            $entityManager->persist($category);
            $entityManager->flush();
        }

        //le formulaire créé est passé à la vue avec createView
        //permettant à Twig d'afficher le formulaire dans le template
        return $this->render('create-category.html.twig', ['categoryForm' =>$categoryForm->createView()]);
    }

    #[Route('/list-categories', name: 'categories')]
    public function DisplayCategories (CategoryRepository $CategoryRepository){
        $categories = $CategoryRepository->findAll();
        return $this->render('list-categories.html.twig',['categories'=> $categories]);
    }

    #[Route('/detail-category/{id}', name: 'detail-category')]
    public function DisplayDetailCategory($id, CategoryRepository $CategoryRepository){
        $category = $CategoryRepository->findOneById($id);
        if (!$category) {
            return $this->redirectToRoute('404');
        }
        return $this->render('detail-category.html.twig', ['category' => $category]);
   
    }


}