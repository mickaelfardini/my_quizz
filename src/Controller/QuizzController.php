<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Question;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

		return $this->render('category/show.html.twig', [
			'controller_name' => 'IndexController',
			'category' => $category,
			'bg' => ['success', 'danger', 'info', 'secondary', 'warning'],
			'bg_index' => 0,
		]);
	}

	/**
	 * @Route("/quizz/result", name="quizz.result", methods={"POST"}) 
	 */
	public function result(Request $request)
	{
		$questions = $this->getDoctrine()
			->getRepository(Question::class)
			->findByCategory($request->request->get('quizz'));
		$correct = 0;
		$result = [];
		$total = count($questions);
		$post = $request->request->all();
		foreach ($questions as $question) {
			dump($question->getAnswer()->getId());
			$answer = $question->getAnswer()->getId();
			if (isset($post[$question->getId()])) {
				if ($post[$question->getId()] == $answer) {
					dump("a");
					$correct++;
					$result[] = 1;
				} else {
					$result[] = 0;
				}
			}
		}
		dump($request->request->all());
		// die;
	    return $this->render('quizz/result.html.twig', [
	    	'questions' => $questions,
	    	'correct' => $correct,
	    	'result' => $result,
	    	'total' => $total,
	    ]);
	}
}
