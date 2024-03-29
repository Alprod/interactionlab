<?php

namespace App\Controller;

use App\Repository\FeedbackRepository;
use App\Repository\UserRepository;
use App\Service\AllFeedbacksSendingOrReceivedByUserCurrentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
	public function __construct(private AllFeedbacksSendingOrReceivedByUserCurrentService $allFeedbacksByUserCurrent) {}

	/**
	 * @throws \Exception
	 */
	#[Route('/profile', name: 'app_profile')]
    public function index(FeedbackRepository $feedRepo, UserRepository $userRepo): Response
    {
		$this->denyAccessUnlessGranted('ROLE_USER');

		$today = new \DateTime('now');
		$days_15 = new \DateTime('-15 days');
		$days_30 = new \DateTime('-30 days');
	    $userCurrent = $this->getUser();

		$feedRepo->setTableName('issue');
		$issueBetweenDate15 = $feedRepo->findIssueFeedbackSendBetweenDate($userCurrent, $days_15,$today );
		$issueBetweenDate30 = $feedRepo->findIssueFeedbackSendBetweenDate($userCurrent, $days_30,$today );
		$AllIssues = $feedRepo->findAll();
		
		$feedRepo->setTableName('received');
		$receivedsBetweenDate15 = $feedRepo->findIssueFeedbackSendBetweenDate($userCurrent, $days_15,$today );
		$receivedsBetweenDate30 = $feedRepo->findIssueFeedbackSendBetweenDate($userCurrent, $days_30,$today );
		$AllReceiveds = $feedRepo->findAll();

		$issues = $feedRepo->findAllFeedBackIssues($userCurrent);

		$receives = $feedRepo->findAllFeedBackRecived($userCurrent);
		$userSubscribe = count($userRepo->findAll());


        return $this->render('profile/index.html.twig', [
			'allIssuesCurrentUser' => $issues,
			'allReceivesCurrentUser' => $receives,
			'issuesBetweenDate15' => $issueBetweenDate15,
			'issuesBetweenDate30' => $issueBetweenDate30,
			'receivedsBetweenDate30' => $receivedsBetweenDate30,
			'receivedsBetweenDate15' => $receivedsBetweenDate15,
			'userSubscribe' => $userSubscribe,
			'allIssues' => $AllIssues,
			'allReceives' => $AllReceiveds
        ]);
    }
}
