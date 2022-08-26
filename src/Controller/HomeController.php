<?php

namespace App\Controller;

use App\Repository\FeedbackRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', []);
    }

	public function showAllInteraction(
		UserRepository $userRepo,
		FeedbackRepository $feedbackRepo,
		Request $request )
	{
		return $this->render('',[]);
	}
}
