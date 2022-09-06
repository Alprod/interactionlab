<?php

namespace App\Controller;

use App\Entity\Feedback;
use App\Entity\User;
use App\Form\FeedbackType;
use App\Repository\FeedbackRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
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
		Request $request,
		ManagerRegistry $doctrine,
		MailerInterface $mailer
	): Response
	{
		$user = $this->getUser();
		$interactions = $userRepo->findAll();
		$today = new \DateTime('today', new \DateTimeZone('Europe/Paris'));
		$em = $doctrine->getManager();
		$key = array_search($user, $interactions, true);
		if ($key !== false){
			unset($interactions[$key]);
		}
		$feedback = new Feedback();
		$feedForm = $this->createForm(FeedbackType::class, $feedback);
		$feedForm->handleRequest($request);

		if ($feedForm->isSubmitted() && $feedForm->isValid()){
			$dataId = $request->get('id');
			$userReceived = $userRepo->findOneBy([ 'id' => $dataId ]);

			$feedback->setIssue($this->getUser())
			         ->setReceived($userReceived)
			         ->setCreatedAt(new \DateTime('now', new \DateTimeZone('Europe/Paris')));
			$em->persist($feedback);
			$em->flush();

			$email = (new TemplatedEmail())
				->from('contact@disdes.net')
				->to($userReceived->getEmail())
				->subject('InteactionLab | feedback donner par un participant')
				->htmlTemplate('email/email_feedback.html.twig')
				->context([
					'username' => $userReceived->fullName(),
					'issue' => $request->get('appUser')
				]);
			try {
				$mailer->send($email);
			}catch (\Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface $mail) {
				$this->addFlash('error', $mail->getMessage("Une erreur c'est produit lors de l'envoie du mail"));
			}


			$this->addFlash('success', 'Votre feedback a été envoyer');
			return $this->redirectToRoute('app_interactions');
		}

		return $this->render('home/interactionPage.html.twig',[
			'user' => $user,
			'interactions' => $interactions,
			'feedForm' => $feedForm->createView()
		]);
	}
}
