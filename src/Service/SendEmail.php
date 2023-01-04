<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class SendEmail extends AbstractController
{
	private LoggerInterface $logger;
	public function __construct(
		private MailerInterface $mailer,
		LoggerInterface $sendemailLogger)
	{
		$this->logger = $sendemailLogger;
	}

	/**
	 * @throws \Exception
	 */
	public function sendEmailTemplated( array $datas, string $htmlFile ): void
	{
		$date = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
		$userCurrent = $this->getUser();
		$mail = (new TemplatedEmail())
			->from('contact@dsides.net')
			->to($datas['userEmail'])
			->subject('InteactionLab | feedback donner par un participant')
			->htmlTemplate('email/'.$htmlFile);

		if($datas) {
			$rmFirstElmnt = array_shift($datas);
			if($rmFirstElmnt === null) {
				throw new \RuntimeException("Un problème est survenu lors de l'envoi de votre mail");
			}
			$mail->context($datas);
		}

		try {
			$this->mailer->send($mail);
			$this->logger->info('email envoyer à : '. $datas['username'], [
				'userCurrent' => $userCurrent->fullname(),
				'date_send_feedabck'=> $date->format('d/m/Y H:i:s'),
				'user_recived_feedback' => $datas['username']
			]);
		}catch ( TransportExceptionInterface $e){
			$this->logger->error($e->getMessage());
		}
	}
}