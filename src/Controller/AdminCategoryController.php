<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AdminCategoryController extends AbstractController
{
    #[Route('admin/categories', name:"adminCategories")]

    public function categories(CategoryRepository $repository){

        return $this->render("admin/categories.html.twig", [
                'categories' => $repository->findAll()
            ]
        );

    }

    #[Route('admin/category/{id}', name:"adminCategory")]

    public function category($id, CategoryRepository $repository){

        return $this->render("admin/category.html.twig", [
                'category' => $repository->find($id)
            ]
        );

    }


    #[Route('/admin/insertCategory', name: "adminInsertCategory")]

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
            return $this->render("admin/insertCategory.html.twig");
        }
    }

    #[Route('/admin/deleteCategory/{id}', name: 'adminDeleteCategory')]

    public function deleteArticle($id, CategoryRepository $repository, EntityManagerInterface $entityManager) {
        $category = $repository->find($id);
        if (!is_null($category)) {
            $entityManager->remove($category);
            $entityManager->flush();
            return $this->redirectToRoute('adminCategories');
        } else {
            return new Response('la catégorie n\'existe pas');
        }
    }

    #[Route('/admin/updateTitleCategory/{id}', name: 'adminUpdateTitleCategory')]

    public function updateTitleCategory($id, CategoryRepository $repository, EntityManagerInterface $entityManager)
    {
        $category = $repository->find($id);
        $category->setTitle("Les mammifères");
        $entityManager->persist($category);
        $entityManager->flush();
        return new Response('titre modifié en : ' . $category->getTitle());
    }
}