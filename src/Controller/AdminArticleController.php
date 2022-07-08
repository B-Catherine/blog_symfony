<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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
                                          ]
                            );
    }

    #[Route('/admin/articles', name:"adminArticles")]

    public function articles(ArticleRepository $repository){

        return $this->render("admin/articles.html.twig", [
                                                                'articles' => $repository->findAll()
                                                              ]
                            );

    }

    #[Route('admin/article/{id}', name:"adminArticle")]

    public function article($id, ArticleRepository $repository){

        return $this->render("admin/article.html.twig", [
                                                                'article' => $repository->find($id)
                                                             ]
                            );

    }

    #[Route('/admin/insertArticle', name: "adminInsertArticle")]

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
            $this->addFlash('success', 'article ajouté avec pour titre : '.$title.', a publié ? '.$isPublished.' pour auteur : '.$author.' pour contenu : '.$content.' pour image : '.$image);;
            return $this->redirectToRoute('adminArticles');
        }
        else {
            return $this->render("admin/insertArticle.html.twig");
        }
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

    #[Route('/admin/updateTitleArticle/{id}', name: 'adminUpdateTitleArticle')]

    public function updateTitleArticle($id, ArticleRepository $repository, EntityManagerInterface $entityManager) {
        $article = $repository->find($id);
        $article->setTitle("Une hirondelle a fait le printemps");
        $entityManager->persist($article);
        $entityManager->flush();
        $this->addFlash('success','titre de l\'article modifié en : '.$article->getTitle().'"');
        return $this->redirectToRoute('adminArticles');
    }
}