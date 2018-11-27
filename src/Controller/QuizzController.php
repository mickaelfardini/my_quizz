<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Question;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class QuizzController extends AbstractController
{
	/**
	 * @Route("/quizz/{id}", name="quizz.show", methods={"GET"}, requirements={"id"="\d+"})
	 */
	public function show($id)
	{
		$category = $this->getDoctrine()
		->getRepository(Category::class)
		->find($id);

		$questions = $this->getDoctrine()
			->getRepository(Question::class)
			->findByCategory($id);

		return $this->render('category/show.html.twig', [
			'controller_name' => 'IndexController',
			'category' => $category,
			'bg' => ['success', 'danger', 'info', 'secondary', 'warning'],
			'bg_index' => 0,
		]);
	}

	/**
	 * @Route("/quizz/answer", name="quizz.answer", methods={"POST"}) 
	 */
	public function answer()
	{
	    return $this->render('quizz/answer.html.twig');
	}
}
