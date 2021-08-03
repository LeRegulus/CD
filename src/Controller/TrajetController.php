<?php

namespace App\Controller;

use DateTime;
use App\Entity\Trajet;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\TrajetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Id;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/trajet')]
class TrajetController extends AbstractController
{
    public function __construct(TrajetRepository $repo, EntityManagerInterface $em)
    {
        $this->repo = $repo;
        $this->em = $em;
    }

    #[Route('/', name: 'trajet_index', methods: ['GET'])]
    public function index(): Response
    {   
        $trajets = $this->repo->findAll();
        return $this->render('trajet/index.html.twig', [
            'trajets' => $trajets,
        ]);
    }

    

    #[Route('/{id}', name: 'trajet_show')]
    public function show(Trajet $trajet, Request $requst): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($requst);
        if ($form->isSubmitted() && $form->isValid()){
            $comment->setTrajet($trajet->getId());
            $this->em->persist($comment);
            $this->em->flush();
            $this->addFlash(type:'success', message:'Commentaire posté avec succés!');
            return $this->redirectToRoute('trajet_show', array('id'=> $trajet->getId()));
        }
        return $this->render('trajet/show.html.twig', [
            'commentform' => $form->createView(),
            'trajet' => $trajet,
        ]);
    }
 
}
