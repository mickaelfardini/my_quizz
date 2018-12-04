<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\History;
use App\Entity\Question;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class QuizzController extends AbstractController
{
	/**
	 * @Route("/quizz/{id}", name="quizz.show", requirements={"id"="\d+"})
	 */
	public function show(Category $category, Request $request)
	{
		if ($request->isMethod('POST')) {
			$question_id = $request->get('question_id');
			$result = $this->checkAnswer($request->get('question_id'), $request->get('answer'), $category->getId());
			if ($question_id < count($category->getQuestions()) -1) {
				$question_id++;
				if ($question_id == count($category->getQuestions())-1){
					$done = true;
				}
				$question = $category->getQuestions()[$question_id];
			}
		} else {
			$question = $category->getQuestions()[0];
		}
		return $this->render('category/show.html.twig', [
			'category' => $category,
			'question_id' => isset($question_id) ? $question_id : 0,
			'question' => $question,
			'done' => isset($done) ? $done : 0,
			'result' => isset($result) ? $result : 0,
		]);
	}

	private function checkAnswer($question, $answer, $quizz)
	{
		$session = new Session();
		$session->set('quizz', $quizz);
		$result = $this->getDoctrine()
					->getRepository(Category::class)
					->find($quizz)
					->getQuestions()[$question]
					->getAnswer();
		if ($result->getId() == $answer) {
			$session->set('score', $session->get('score') + 1);
		}
		return $result;
	}
	/**
	 * @Route("/quizz/result", name="quizz.result") 
	 */
	public function result()
	{
		$session = new Session();
		$correct = is_null($session->get('score')) ? 0 : $session->get('score');
		$quizz = $session->get('quizz');
		$questions = $this->getDoctrine()
			->getRepository(Question::class)
			->findByCategory($quizz);
		$total = count($questions);
		$this->pushResult($correct, $quizz);
		$session->clear();
	    return $this->render('quizz/result.html.twig', [
	    	'correct' => $correct,
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
