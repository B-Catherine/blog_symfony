<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends AbstractController
{
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
}