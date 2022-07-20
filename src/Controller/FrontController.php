<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    #[Route('/', name: "home")]

    public function home(Request $request, ArticleRepository $repository){

        return $this->render("home.html.twig", [
            'articles' => $repository->findBy([], [
                'id' => 'DESC'
            ], 3)
        ]);
    }

    #[Route('/articles', name:"Articles")]

    public function articles(ArticleRepository $repository){

        return $this->render("front/articles.html.twig", [
            'articles' => $repository->findAll()
        ]);
    }

    #[Route('/article/{id}', name:"Article")]

    public function article($id, ArticleRepository $repository){

        return $this->render("front/article.html.twig", [
            'article' => $repository->find($id)
        ]);
    }

    #[Route('/categories', name:"Categories")]

    public function categories(CategoryRepository $repository){

        return $this->render("front/categories.html.twig", [
            'categories' => $repository->findAll()
        ]);
    }

    #[Route('/category/{id}', name:"Category")]

    public function category($id, CategoryRepository $repository){

        return $this->render("front/category.html.twig", [
            'category' => $repository->find($id)
        ]);
    }
}