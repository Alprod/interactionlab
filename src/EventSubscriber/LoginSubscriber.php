<?php

namespace App\EventSubscriber;

use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class LoginSubscriber extends AbstractController implements EventSubscriberInterface
{
	private $logger;
	public function __construct(LoggerInterface $loginsubscriberLogger)
	{
		$this->logger = $loginsubscriberLogger;
	}

	/**
	 * @inheritDoc
	 */
	public static function getSubscribedEvents(): array
	{
		return [
			LoginSuccessEvent::class => 'LoginSuccessEvent',
			LogoutEvent::class => 'LogoutSuccessEvent'
		];
	}

	/**
	 * @throws Exception
	 */
	public function LoginSuccessEvent( LoginSuccessEvent $event): void
	{
		$user = $event->getUser();
		$date = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
		$day = $date->format('d-m-Y H:i:s');
		$this->logger->info( 'Connecter : '. $user->getUserIdentifier().' le '. $day );
		$this->addFlash('green', 'Cool!!!');
	}

	public function LogoutSuccessEvent( LogoutEvent $event ): void
	{
		$this->addFlash('blue', 'Salut');
	}
}