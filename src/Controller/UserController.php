<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
	/**
	 * @Route("/profile", name="user.index")
	 */
	public function index()
	{
		return $this->render('user/index.html.twig', [
			'controller_name' => 'UserController',
		]);
	}

	/**
	 * @Route("/profile/edit", name="user.edit")
	 */
	public function edit(Request $request, UserPasswordEncoderInterface $encoder, \Swift_Mailer $mailer)
	{
		$user = $this->getUser();
		$old_email = $user->getEmail();
		// dump();
		dump($user);
		// die;
		$form = $this->createForm(UserType::class, $user);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			if ($request->get('user')['email'] != $old_email) {
				$user->setToken(md5(mt_rand()));
				$user->setActive(0);
				$message = (new \Swift_Message('Confirm new Email'))
				->setFrom('noreply@localhost')->setTo($user->getEmail())
				->setBody($this->renderView('email/register.html.twig', [
					"token" => $user->getToken() ]));
				$mailer->send($message);
			}
			$user->setPassword($encoder->encodePassword($user, $user->getPassword()));
			$this->getDoctrine()->getManager()->flush();

			return $this->redirectToRoute('user.index', ['id' => $user->getId()]);
		}

		return $this->render('user/edit.html.twig', [
			'user' => $user,
			'form' => $form->createView(),
		]);
	}
}
