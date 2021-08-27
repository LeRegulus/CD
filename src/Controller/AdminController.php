<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Trajet;
use App\Entity\Comment;
use App\Entity\Reservation;
use App\Repository\UserRepository;
use App\Repository\TrajetRepository;
use App\Repository\CommentRepository;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    public function __construct(TrajetRepository $repo, ReservationRepository $repo1, UserRepository $repo2, CommentRepository $repo3)
    {
        $this->repo = $repo;
        $this->repo1 = $repo1;
        $this->repo2 = $repo2;
        $this->repo3 = $repo3;
    }

    #[Route('/admin', name: 'app_admin_index')]
    public function users(): Response
    {
        $users = $this->repo2->findAll();
        return $this->render('admin/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/admin/user/{id}', name: 'admin_user_delete')]
    public function deleteuser(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $this->em->remove($user);
            $this->em->flush();
        }
        return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/admin/trajets', name: 'app_admin_trajets')]
    public function trajets(): Response
    {
        $trajets = $this->repo->findAll();
        return $this->render('admin/trajets.html.twig', [
            'trajets' => $trajets,
        ]);
    }

    #[Route('/admin/trajet/{id}', name: 'admin_trajet_delete')]
    public function deletetrajet(Request $request, Trajet $trajet): Response
    {
        if ($this->isCsrfTokenValid('delete'.$trajet->getId(), $request->request->get('_token'))) {
            $this->em->remove($trajet);
            $this->em->flush();
        }
        return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/admin/reserves', name: 'app_admin_reserves')]
    public function reserves(): Response
    {
        $reserves = $this->repo1->findAll();
        return $this->render('admin/reserves.html.twig', [
            'reservations' => $reserves,
        ]);
    }

    #[Route('/admin/reserve/{id}', name: 'admin_reserve_delete')]
    public function deletereserve(Request $request, Reservation $reserve): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reserve->getId(), $request->request->get('_token'))) {
            $this->em->remove($reserve);
            $this->em->flush();
        }
        return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/admin/comments', name: 'app_admin_comments')]
    public function comments(): Response
    {
        $comments = $this->repo3->findAll();
        return $this->render('admin/comments.html.twig', [
            'comments' => $comments,
        ]);
    }

    #[Route('/admin/comment/{id}', name: 'admin_comment_delete')]
    public function deletecomment(Request $request, Comment $comment): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $this->em->remove($comment);
            $this->em->flush();
        }
        return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
    }
}
