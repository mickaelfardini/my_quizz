<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
	/**
	 * @Route("/security", name="security")
	 */
	public function index()
	{
		return $this->render('security/index.html.twig', [
			'controller_name' => 'SecurityController',
		]);
	}

	/**
	* @Route("/register", name="registration")
	*/
	public function registration(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder, \Swift_Mailer $mailer)
	{
		if (!$this->checkEmail($request->get('registration')['email'])) {
			return $this->redirectToRoute('registration'); }
		$user = new User();
		$form = $this->createForm(RegistrationType::class, $user);
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()) {
			$user->setPassword($encoder->encodePassword($user, $user->getPassword()));
			$user->setToken(md5(mt_rand()));
			$user->setActive(0);
	
			$message = (new \Swift_Message('Inscription'))
				->setFrom('noreply@localhost')->setTo($user->getEmail())
				->setBody($this->renderView('email/register.html.twig', [
					"token" => $user->getToken() ]));
			$mailer->send($message);
			$manager->persist($user);
			$manager->flush();
			return $this->redirectToRoute('app_login');
		}
		
		return $this->render('security/registration.html.twig', [
			'form' => $form->createView(),
		]);
	}

	/**
	 * @Route("/login", name="app_login")
	 */
	public function login(AuthenticationUtils $authenticationUtils): Response
	{
		// get the login error if there is one
		$error = $authenticationUtils->getLastAuthenticationError();
		// last username entered by the user
		$lastUsername = $authenticationUtils->getLastUsername();

		return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
	}

	/**
	 * @Route("/confirm/{token}", name="register.confirm")
	 */
	public function confirmEmail($token, ObjectManager $manager)
	{
	    $user = $this->getDoctrine()
					->getRepository(User::class)
					->findOneByToken($token);
		if ($user) {
			$user->setActive(1);
			$manager->persist($user);
			$manager->flush();
		}

		return $this->render('email/register.html.twig', [
			"token" => 12,
		]);
	}

	private function checkEmail($email)
	{
		$user = $this->getDoctrine()
					->getRepository(User::class)
					->findByEmail($email);
		
		return $user ? false : true;
	}
}
