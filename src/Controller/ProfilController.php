<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Trajet;
use App\Form\TrajetType;
use App\Form\EditprofilType;
use App\Repository\ReservationRepository;
use App\Repository\TrajetRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProfilController extends AbstractController
{
    public function __construct(EntityManagerInterface $em, TrajetRepository $repo, ReservationRepository $repo1)
    {
        $this->em = $em;
        $this->repo = $repo;
        $this->repo1 = $repo1;
    }

    #[Route('/profil', name: 'app_profil')]
    public function index(): Response
    {
        $id = $this->getUser('id');
        $trajets = $this->repo->findUserTrajets($id);
        $reservations = $this->repo1->UserReserves($id);
        return $this->render('profil/index.html.twig', [
            'trajets' => $trajets,
            'reservations' => $reservations
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
            return $this->redirectToRoute('app_profil'); 
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

    #[Route('/profil/trajet/new', name: 'profil_trajet_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $trajet = new Trajet();
        $form = $this->createForm(TrajetType::class, $trajet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trajet->setUser($this->getUser());
            $this->em->persist($trajet);
            $this->em->flush();

            return $this->redirectToRoute('trajet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('trajet/new.html.twig', [
            'trajet' => $trajet,
            'form' => $form,
        ]);
    }

    #[Route('/profil/trajet/{id}', name: 'profil_trajet_delete', methods: ['POST'])]
    public function delete(Request $request, Trajet $trajet): Response
    {
        if ($this->isCsrfTokenValid('delete'.$trajet->getId(), $request->request->get('_token'))) {
            $this->em->remove($trajet);
            $this->em->flush();
        }

        return $this->redirectToRoute('trajet_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/profil/trajet/{id}/edit', name: 'profil_trajet_edit', methods: ['GET', 'POST'])]
    public function trajetEdit(Request $request, Trajet $trajet): Response
    {
        $form = $this->createForm(TrajetType::class, $trajet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            return $this->redirectToRoute('trajet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('trajet/edit.html.twig', [
            'trajet' => $trajet,
            'form' => $form,
        ]);
    }

    #[Route('/profil/trajet/{id}/reserve', name: 'profil_trajet_reserver')]
    public function reserve(Trajet $trajet){
        //dd($trajet);
        $reservation = new Reservation();
        if($trajet->getPlaces() > 0){
            $reservation->setTrajet($trajet);
            $reservation->setUser($this->getUser());
            $reservation->setCreatedAt(new DateTime('now'));
            $this->em->persist($reservation);
            $place = ($trajet->getPlaces() - 1);
            $trajet->setPlaces($place);
            $this->em->persist($trajet);
            $this->em->flush();
            $this->addFlash('success', 'Votre réservation est bien envoyé au conducteur!');
            return $this->redirectToRoute('trajet_show', array('id'=> $trajet->getId()));
        }

    }

    #[Route('/profil/trajets', name: 'profil_admin_index')]
    public function admin(): Response
    {
        $id = $this->getUser('id');
        $trajets = $this->repo->findUserTrajets($id);
        return $this->render('profil/admin/index.html.twig', [
            'trajets' => $trajets
        ]);
    }

    #[Route('/profil/reservation', name: 'profil_reservations_index')]
    public function reservation(): Response
    {
        $id = $this->getUser('id');
        $reservations = $this->repo1->UserReserves($id);
        return $this->render('profil/reservation/index.html.twig', [
            'reservations' => $reservations
        ]);
    }
}
