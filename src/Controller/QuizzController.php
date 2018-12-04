<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\History;
use App\Entity\Question;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class QuizzController extends AbstractController
{
	/**
	 * @Route("/quizz/{id}", name="quizz.show", methods={"GET"}, requirements={"id"="\d+"})
	 */
	public function show(CategoryRepository $category, $id)
	{
		return $this->render('category/show.html.twig', [
			'category' => $category->find($id),
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
			$answer = $question->getAnswer()->getId();
			if (isset($post[$question->getId()])) {
				if ($post[$question->getId()] == $answer) {
					$correct++;
					$result[] = 1;
				} else {
					$result[] = 0;
				}
			}
		}

		$this->pushResult($correct, $request->request->get('quizz'));
	    return $this->render('quizz/result.html.twig', [
	    	'questions' => $questions,
	    	'correct' => $correct,
	    	'result' => $result,
	    	'total' => $total,
	    ]);
	}

	private function pushResult($points, $quizz)
	{
		$request = Request::createFromGlobals();
		$em = $this->getDoctrine()->getManager();
		$history = new History();

		$history->setIp($request->getClientIp())
			->setAgent($request->headers->get("User-Agent"))
			->setPoints($points)
			->setQuizz($quizz);
			
		$em->persist($history);
		$em->flush();
		return true;
	}
}
