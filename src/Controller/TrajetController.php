<?php

namespace App\Controller;

use App\Entity\Trajet;
use App\Form\TrajetType;
use App\Repository\TrajetRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    public function index(TrajetRepository $trajetRepository): Response
    {   
        $trajets = $this->repo->findAll();
        return $this->render('trajet/index.html.twig', [
            'trajets' => $trajets,
        ]);
    }

    

    #[Route('/{id}', name: 'trajet_show', methods: ['GET'])]
    public function show(Trajet $trajet): Response
    {
        return $this->render('trajet/show.html.twig', [
            'trajet' => $trajet,
        ]);
    }
 
}
