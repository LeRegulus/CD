<?php

namespace App\Controller;

use App\Form\EditprofilType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProfilController extends AbstractController
{
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/profil', name: 'app_profil')]
    public function index(): Response
    {
        return $this->render('profil/index.html.twig', [
            'controller_name' => 'ProfilController',
        ]);
    }

    #[Route('/profil/edit', name: 'app_profil_edit')]
    public function edit(Request $request){
        $user = $this->getUser();
        $form = $this->createForm(EditprofilType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $photo = $form->get('profilPhoto')->getData();
            if($photo){
                $photoFilename = md5(uniqid()).'.'.$photo->guessExtension();
                $photo->move(
                        $this->getParameter('profilPhoto_directory'),
                        $photoFilename
                );
                $user->setProfilPhoto($photoFilename);
            }
            $this->em->persist($user);
            $this->em->flush();
            $this->addFlash('message', 'Profil mis à jour');
            $this->redirectToRoute('app_profil'); 
        }
        return $this->render('profil/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/profil/password/edit', name: 'app_profil_password_edit')]
    public function mdpedit(Request $request, UserPasswordHasherInterface $passwordEncoder){
        if($request->isMethod('POST')){
            $user = $this->getUser();
            if ($request->request->get('pass') == $request->request->get('password')){
                // encode the plain password
                $user->setPassword($passwordEncoder->hashPassword($user, $request->request->get('pass')));
                $this->em->flush();
                $this->addFlash('success', 'Mot de passe modifié avec succé!');
                return $this->redirectToRoute('app_profil');
            }else{
                $this->addFlash('error', 'Les deux mots de passe ne sont pas identiques!');
            }
        }
        return $this->render('profil/edit_password.html.twig');
    }
}
