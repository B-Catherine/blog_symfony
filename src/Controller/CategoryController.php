<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends AbstractController
{
    #[Route('categories', name:"categories")]

    public function categories(CategoryRepository $repository){

        return $this->render("categories.html.twig", [
                'categories' => $repository->findAll()
            ]
        );

    }

    #[Route('category/{id}', name:"category")]

    public function category($id, CategoryRepository $repository){

        return $this->render("category.html.twig", [
                'category' => $repository->find($id)
            ]
        );

    }


    #[Route('/insertCategory', name: "insertCategory")]

    public function insert(Request $request, EntityManagerInterface $entityManager){
        if ($request->query->has('title')) {
            $title = $request->query->get('title');
            $color = $request->query->get('color');
            $description = $request->query->get('description');
            $isPublished = $request->query->get('isPublished');
            $category = new Category($title, $color, $description, $isPublished);
            $entityManager->persist($category);
            $entityManager->flush($category);
            return new Response('catégorie ajoutée avec ->.<br>pour nom : '.$title.'<br>pour couleur : '.$color.'<br>pour description : '.$description.'<br>à publier ? '.$isPublished);
        }
        else {
            return $this->render("insertCategory.html.twig");
        }
    }

    #[Route('/deleteCategory/{id}', name: 'deleteCategory')]

    public function deleteArticle($id, CategoryRepository $repository, EntityManagerInterface $entityManager) {
        $category = $repository->find($id);
        if (!is_null($article)) {
            $entityManager->remove($category);
            $entityManager->flush();
            return new Response('catégorie supprimée');
        } else {
            return new Response('la catégorie n\'existe pas');
        }
    }

}