<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AdminArticleController extends AbstractController
{
    #[Route('/', name: "home")]

    public function home(Request $request, ArticleRepository $repository){

        return $this->render("home.html.twig", [
            'articles' => $repository->findBy([], [
                'id' => 'DESC'
            ], 3)
        ]);
    }

    #[Route('/admin/articles', name:"adminArticles")]

    public function articles(ArticleRepository $repository){

        return $this->render("admin/articles.html.twig", [
            'articles' => $repository->findAll()
        ]);
    }

    #[Route('admin/article/{id}', name:"adminArticle")]

    public function article($id, ArticleRepository $repository){

        return $this->render("admin/article.html.twig", [
            'article' => $repository->find($id)
        ]);
    }

    #[Route('/admin/insertArticle', name: "adminInsertArticle")]

    public function insertArticle(Request $request, EntityManagerInterface $entityManager){
        $article= new Article();
        $form=$this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($article);
            $entityManager->flush();
            $this->addFlash('success', 'Article ajouté');
        }
        return $this->render("admin/insertArticle.html.twig", [
            'form' => $form->createView()
        ]);
    }

    #[Route('/admin/deleteArticle/{id}', name: 'adminDeleteArticle')]

    public function deleteArticle($id, ArticleRepository $repository, EntityManagerInterface $entityManager) {
        $article = $repository->find($id);
        if (!is_null($article)) {
            $entityManager->remove($article);
            $entityManager->flush();
            $this->addFlash('success', 'article "'.$article->getTitle().'" supprimé');
            return $this->redirectToRoute('adminArticles');
        } else {
            $this->addFlash('error', 'l\'article "'.$article->getTitle().'" n\'existe pas');
            return $this->redirectToRoute('adminArticles');
        }
    }

    #[Route('/admin/updateArticle/{id}', name: 'adminUpdateArticle')]

    public function updateArticle($id, Request $request, ArticleRepository $repository, EntityManagerInterface $entityManager) {
        $article = $repository->find($id);
        $form=$this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($article);
            $entityManager->flush();
            $this->addFlash('success', 'Article mis à jour');
        }
        return $this->render("admin/updateArticle.html.twig", [
            'form' => $form->createView()
        ]);
    }
}