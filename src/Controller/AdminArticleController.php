<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;

class AdminArticleController extends AbstractController
{

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

    public function insertArticle(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger){
        $article= new Article();
        $form=$this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                $article->setImage($newFilename);
            }
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

    public function updateArticle($id, Request $request, ArticleRepository $repository, EntityManagerInterface $entityManager, SluggerInterface $slugger) {
        $article = $repository->find($id);
        $form=$this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );
                $article->setImage($newFilename);
            }
            $entityManager->persist($article);
            $entityManager->flush();
            $this->addFlash('success', 'Article mis à jour');
        }
        return $this->render("admin/updateArticle.html.twig", [
            'form' => $form->createView(),
            'article' =>$article
        ]);
    }

    #[Route('/admin/searchArticles', name: 'adminSearchArticles')]

    public function searchArticles(Request $request, ArticleRepository $repository) {
        $search = $request->query->get('search');
        $articles = $repository->searchArticlesByTitle($search);
        return $this->render('admin/searchArticles.html.twig', [
            'articles' => $articles
        ]);
    }
}