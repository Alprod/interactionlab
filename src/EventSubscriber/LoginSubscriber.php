<?php

namespace App\EventSubscriber;

use App\Security\AccountNotVerifiedAuthenticationException;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;
use Symfony\Component\Security\Http\Event\LoginFailureEvent;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class LoginSubscriber extends AbstractController implements EventSubscriberInterface
{
	private LoggerInterface $logger;

	public function __construct(
		LoggerInterface $loginsubscriberLogger,
		private RouterInterface $router,
		private RequestStack $requestStack
	)
	{
		$this->logger = $loginsubscriberLogger;
	}

	/**
	 * @inheritDoc
	 */
	public static function getSubscribedEvents(): array
	{
		return [
			LoginFailureEvent::class => 'onLoginFailure',
			CheckPassportEvent::class => 'CheckPassportEvent',
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
		$this->addFlash('success', 'Cool!!! '. ucfirst($user->getLastname()). ' et bienvenue');
	}

	public function LogoutSuccessEvent( LogoutEvent $event ): void
	{
		$this->addFlash('success', 'Salut');
	}

	public function CheckPassportEvent(CheckPassportEvent $event): void
	{
		$user = $event->getPassport()->getUser();
		if(!$user->isVerified()) {
			throw new AccountNotVerifiedAuthenticationException('Mon message', 0,null, $this->requestStack);
		}
	}

	public function onLoginFailure(LoginFailureEvent $event): void
	{
		if( !$event->getException() instanceof AccountNotVerifiedAuthenticationException) {
			return;
		}
		$email = $event->getRequest()->get('_username');
		$session = $this->requestStack->getSession();
		$session->set('_username', $email);

		$response = new RedirectResponse($this->router->generate('app_verify_resend'));
		$event->setResponse($response);
	}

}