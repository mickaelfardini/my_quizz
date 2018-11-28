<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\History;
use App\Entity\Question;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HistoryController extends AbstractController
{
	/**
	 * @Route("/history", name="history.index")
	 */
	public function index()
	{
		$request = Request::createFromGlobals();

		$history = $this->getDoctrine()
			->getRepository(History::class)
			->findBy([
				'ip' => $request->getClientIp(),
				// 'agent' => $request->headers->get("User-Agent"),
			]);

		foreach ($history as $value) {
			dump($value->getQuizz());
			$category = $this->getDoctrine()
				->getRepository(Category::class)
				->find($value->getQuizz());
			$quizz[] = [
				"name" => $category->getName(),
				"total" => count($category->getQuestions()),
				"points" => $value->getPoints(),
			];
		}
		// dump($quizz);
		// dump($history);
		// die;

		return $this->render('history/index.html.twig', [
			'controller_name' => 'HistoryController',
			'quizz' => $quizz,
		]);
	}
}
