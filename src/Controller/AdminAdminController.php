<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdminAdminController extends AbstractController
{
    #[Route('/admin/users', name:"adminAdmins")]

    public function listUsers(UserRepository $repository){

        return $this->render("admin/users.html.twig", [
            'users' => $repository->findAll()
        ]);
    }

    #[Route('/admin/insertAdmin', name: "adminInsertAdmin")]

    public function insertAdmin(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher){
        $user= new User();
        $user->setRoles(["ROLE_ADMIN"]);
        $form=$this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->get('password')->getData();
            $passwordHashed = $userPasswordHasher->hashPassword($user, $password);
            $user->setPassword($passwordHashed);
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Admin ajouté');
            return $this->redirectToRoute("adminAdmins");
        }
        return $this->render("admin/insertAdmin.html.twig", [
            'form' => $form->createView()
        ]);
    }

    #[Route('/admin/deleteAdmin/{id}', name: 'adminDeleteAdmin')]

    public function deleteAdmin($id, UserRepository $repository, EntityManagerInterface $entityManager) {
        $user = $repository->find($id);
        if (!is_null($user)) {
            $entityManager->remove($user);
            $entityManager->flush();
            $this->addFlash('success', 'admin "'.$user->getEmail().'" supprimé');
            return $this->redirectToRoute('adminAdmins');
        } else {
            $this->addFlash('error', 'l\'admin sélectionné n\'existe pas');
            return $this->redirectToRoute('adminAdmins');
        }
    }

    #[Route('/admin/updateAdmin/{id}', name: 'adminUpdateAdmin')]

    public function updateAdmin($id, Request $request, UserRepository $repository, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher) {
        $user = $repository->find($id);
        $form=$this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->get('password')->getData();
            $passwordHashed = $userPasswordHasher->hashPassword($user, $password);
            $user->setPassword($passwordHashed);
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Admin mis à jour');
        }
        return $this->render("admin/updateAdmin.html.twig", [
            'form' => $form->createView()
        ]);
    }
}