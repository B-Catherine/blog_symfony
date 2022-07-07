<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class PagesController extends AbstractController
{
    #[Route('/', name: "home")]

    public function home(Request $request, ArticleRepository $repository){

        return $this->render("home.html.twig", [
                'articles' => $repository->findAll()
            ]
        );
    }

    #[Route('articles', name:"articles")]

    public function articles(ArticleRepository $repository){

        return $this->render("articles.html.twig", [
                'articles' => $repository->findAll()
            ]
        );

    }

    #[Route('article/{id}', name:"article")]

    public function article($id, ArticleRepository $repository){

        return $this->render("article.html.twig", [
                                                            'article' => $repository->find($id)
                                                        ]
                            );

    }
    #[Route('/insertArticle', name: "insertArticle")]

    public function insert(Request $request, EntityManagerInterface $entityManager){
        if ($request->query->has('title')) {
            $title = $request->query->get('title');
            $isPublished = $request->query->get('isPublished');
            $author = $request->query->get('author');
            $content = $request->query->get('content');
            $image = $request->query->get('image');
            $article = new Article($title, $isPublished, $author, $content, $image);
            $entityManager->persist($article);
            $entityManager->flush($article);
            return new Response('article ajouté avec ->.<br>pour titre : '.$title.'<br>a publié ? '.$isPublished.'<br>pour auteur : '.$author.'<br>pour contenu : '.$content.'<br>pour image : '.$image);
        }
        else {
            return $this->render("insertArticle.html.twig");
        }
    }
}