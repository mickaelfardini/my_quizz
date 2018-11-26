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
	public function registration(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
	{
		$user = new User();
		$form = $this->createForm(RegistrationType::class, $user);
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()) {
			$user->setPassword($encoder->encodePassword($user, $user->getPassword()));

			$manager->persist($user);
			$manager->flush();
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
}
