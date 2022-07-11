<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

    public function insertCategory(Request $request, EntityManagerInterface $entityManager){
        $category= new Category();
        $form=$this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();
            $this->addFlash('success', 'Catégorie ajoutée');
        }
        return $this->render("admin/insertCategory.html.twig", [
            'form' => $form->createView()
        ]);
    }

    #[Route('/admin/deleteCategory/{id}', name: 'adminDeleteCategory')]

    public function deleteCategory($id, CategoryRepository $repository, EntityManagerInterface $entityManager) {
        $category = $repository->find($id);
        if (!is_null($category)) {
            $entityManager->remove($category);
            $entityManager->flush();
            $this->addFlash('success', 'catégorie "'.$category->getTitle().'" supprimée');
            return $this->redirectToRoute('adminCategories');
        } else {
            $this->addFlash('error', 'la catégorie n\'existe pas');
            return $this->redirectToRoute('adminCategories');
        }
    }

    #[Route('/admin/updateCategory/{id}', name: 'adminUpdateCategory')]

    public function updateCategory($id, Request $request, CategoryRepository $repository, EntityManagerInterface $entityManager)
    {
        $category = $repository->find($id);
        $form=$this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();
            $this->addFlash('success', 'Catégorie mise à jour');
        }
        return $this->render("admin/updateCategory.html.twig", [
            'form' => $form->createView()
        ]);
    }
}