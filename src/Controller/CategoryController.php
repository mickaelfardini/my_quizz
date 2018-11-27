<?php

namespace App\Controller;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
	/**
	 * @Route("/categories", name="category.index", methods={"GET"})
	 */
	public function index()
	{
		$category = $this->getDoctrine()
		->getRepository(Category::class)
		->findAll();

		return $this->render('category/index.html.twig', [
			'controller_name' => 'IndexController',
			'category' => $category,
			'bg' => ['success', 'danger', 'info', 'secondary', 'warning'],
			'bg_index' => 0,
		]);
	}
}
