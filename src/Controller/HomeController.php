<?php

namespace App\Controller;

use App\Repository\FeedbackRepository;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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

	#[Route('/show', name: 'app_interactions')]
	#[IsGranted('ROLE_USER')]
	public function showAllInteraction(
		UserRepository $userRepo,
		FeedbackRepository $feedbackRepo,
		Request $request ): Response
	{
		$user = $this->getUser();
		$interactions = $userRepo->findAll();
		$today = new \DateTime('today', new \DateTimeZone('Europe/Paris'));
		$key = array_search($user, $interactions, true);
		if ($key !== false){
			unset($interactions[$key]);
		}

		return $this->render('home/interactionPage.html.twig',[
			'user' => $user,
			'interactions' => $interactions
		]);
	}
}
