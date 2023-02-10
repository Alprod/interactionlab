<?php

namespace App\Controller;

use App\Repository\FeedbackRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(FeedbackRepository $feedRepo): Response
    {
		$today = new \DateTime('now');
	    $userCurrent = $this->getUser();
		$issues = $feedRepo->findAllFeedBackIssues($userCurrent);
		$receives = $feedRepo->findAllFeedBackRecived($userCurrent);
        return $this->render('profile/index.html.twig', [
			'issues' => $issues,
			'receives' => $receives
        ]);
    }
}
