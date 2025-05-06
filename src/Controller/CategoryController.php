<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;



class CategoryController extends AbstractController{

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