<?php

namespace App\Controller;

use App\Repository\TrajetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function __construct(TrajetRepository $repo, EntityManagerInterface $em)
    {
        $this->repo = $repo;
        $this->em = $em;
    }
    
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {   
        $trajets = $this->repo->findAll();
        return $this->render('home/index.html.twig', [
            'trajets' => $trajets,
        ]);
    }
}
