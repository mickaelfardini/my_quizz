<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
	public function edit(Request $request)
	{
		$user = $this->getUser();
		$form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_edit', ['id' => $user->getId()]);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
	}
}
