<?php

namespace App\Controller;

use App\Entity\Feedback;
use App\Form\FeedbackType;
use App\Repository\FeedbackRepository;
use App\Repository\UserRepository;
use App\Service\AllFeedbacksSendingOrReceivedByUserCurrentService;
use App\Service\SendEmail;
use Doctrine\Persistence\ManagerRegistry;
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
        return $this->render('home/index.html.twig');
    }


	/**
	 * @throws \Exception
	 */
	#[Route('/show', name: 'app_interactions')]
	#[IsGranted('ROLE_USER')]
	public function showAllInteraction(
		UserRepository $userRepo,
		Request $request,
		ManagerRegistry $doctrine,
		SendEmail $email,
		FeedbackRepository $feedbackRepo
	): Response
	{
		$datasEmail = [];
		$user = $this->getUser();
		$em = $doctrine->getManager();
		$interactions = $userRepo->findColleagues($user);
		$feedbacksReceivedUserCurrent = $user->getReceivedFeedback();
		$today = new \DateTime('now', new \DateTimeZone('Europe/Paris'));

		$feedback = new Feedback();
		$feedForm = $this->createForm(FeedbackType::class, $feedback);
		$feedForm->handleRequest($request);

		//Verification du formulaire de feedback avec envoi d'email après submit
		if ($feedForm->isSubmitted() && $feedForm->isValid()){
			$dataId = $request->get('id');
			$userReceived = $userRepo->findOneBy([ 'id' => $dataId ]);

			$feedback->setIssue($this->getUser())
			         ->setReceived($userReceived)
			         ->setCreatedAt(new \DateTime('now', new \DateTimeZone('Europe/Paris')));
			$em->persist($feedback);
			$em->flush();

			$datasEmail['userEmail'] = $userReceived->getEmail();
			$datasEmail['username'] = $userReceived->fullName();
			$datasEmail['issue'] = $user->getLastname();

			try {
				$email->sendEmailTemplated($datasEmail, 'email_feedback.html.twig');
				$this->addFlash('success', 'Votre feedback pour '.$userReceived->fullName().' a été envoyer');
			}catch (\Exception $e) {
				$this->addFlash('error', $e->getMessage());
			}

			return $this->redirectToRoute('app_interactions');
		}

		$allFeedsSend =  $this->allFeedbacksSendOrReceivedUserCurrent( $feedbackRepo, $today,'issue' );
		$allFeedReceived = $this->allFeedbacksSendOrReceivedUserCurrent( $feedbackRepo, $today, 'received' );

		$scoreTotalGrade = 0;
		$middleScoreGradeInPercent = 0;

		foreach ($feedbacksReceivedUserCurrent as $receivedUserCurrent ){
			$scoreTotalGrade += $receivedUserCurrent->getGrade();
		}

		if( $scoreTotalGrade > 0){
			$middleScoreGradeInPercent += round( ( 100/$scoreTotalGrade ) * count( $feedbacksReceivedUserCurrent) , 0 );
		}

		$issuFeedToday = $feedbackRepo->findIssueFeedbackSince($user, $today);
		$count = 0;
		foreach ($issuFeedToday as $feedToday) {
			$todayFeed = $feedToday->getCreatedAt()->format('Y-m-d');
			$date = $today->format('Y-m-d');
			if($todayFeed === $date){
				$count++;
			}
		}

		return $this->render('home/interactionPage.html.twig',[
			'user' => $user,
			'countfeedToday' => $count,
			'interactions' => $interactions,
			'allFeedsSend' => $allFeedsSend,
			'allFeedReceived' => $allFeedReceived,
			'middleScoreGradeInPercent' => $middleScoreGradeInPercent,
			'feedForm' => $feedForm->createView()
		]);
	}

	private function allFeedbacksSendOrReceivedUserCurrent(
		FeedbackRepository $feedbackRepo,
		\DateTime $today,
		string $tableField): array
	{
		$checkDateGap = new AllFeedbacksSendingOrReceivedByUserCurrentService();
		$checkDateGap->setTableField($tableField);
		return $checkDateGap->checkDateGapFeedbacks( $feedbackRepo, $this->getUser(), $today );
	}


}
