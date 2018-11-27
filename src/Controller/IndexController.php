<?php

namespace App\Controller;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
	/**
	 * @Route("/index", name="index", methods={"GET"})
	 */
	public function index()
	{
		$category = $this->getDoctrine()
		->getRepository(Category::class)
		->findAll();

		// dump($category);
		// die;
		return $this->render('index/index.html.twig', [
			'controller_name' => 'IndexController',
			'category' => $category,
			'bg' => ['success', 'danger', 'info', 'secondary', 'warning'],
			'bg_index' => 0,
		]);
	}
}
