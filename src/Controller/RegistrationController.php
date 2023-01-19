<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(
		EmailVerifier $emailVerifier,
		private RequestStack $requestStack,
	    private EntityManagerInterface $entityManager
    )
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/register', name: 'app_register')]
    public function register(
		Request $request,
		UserPasswordHasherInterface $userPasswordHasher,
		FileUploader $uploader ): Response
    {
        $user = new User();
		$em = $this->entityManager;
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
			$user->setCreatedAt(new \DateTime('now', new \DateTimeZone('Europe/Paris')));

			/** @var UploadedFile $avatarFile */
			$avatarFile = $form->get('avatar')->getData();

			if($avatarFile) {
				$newFilename = $uploader->upload($avatarFile);
				$user->setAvatar($newFilename);
			}

            $em->persist($user);
            $em->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('equipe@makelearn.fr', 'Interactions Lab teams'))
                    ->to($user->getEmail())
                    ->subject('Veuillez confirmé votre Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            // do anything else you need here, like send an email
			$this->addFlash('green', 'Aller à votre boite mail afin de valider l\'email');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator, UserRepository $userRepository): Response
    {
        $id = $request->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_register');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_register');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('green', 'Merci votre email : '.$user->getEmail().' à été verifier.');

        return $this->redirectToRoute('app_login');
    }

    /**
     * @throws TransportExceptionInterface
     */
    #[Route('verify/resend', name: 'app_verify_resend')]
	public function resendVerifyEmail(Request $request)
	{
		$em = $this->entityManager;
		$email = $request->getSession()->get('_username');
		$user = $em->getRepository(User::class)->findOneBy(['email' => $email]);

		if(!$user) {
			$this->addFlash('danger', 'Désolé mais '.$email.' ne fais pas partis d\'interactionLab. Veuiilez vous inscrire');
			return $this->redirectToRoute('app_register');
		}

		$this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('equipe@makelearn.fr', 'Interactions Lab teams'))
                    ->to($user->getEmail())
                    ->subject('Veuillez confirmé votre Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
		);
		return $this->render('registration/resend_verify_email.html.twig');
	}
}
