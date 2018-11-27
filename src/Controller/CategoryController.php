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

		// dump($category);
		// die;
		return $this->render('category/index.html.twig', [
			'controller_name' => 'IndexController',
			'category' => $category,
			'bg' => ['success', 'danger', 'info', 'secondary', 'warning'],
			'bg_index' => 0,
		]);
	}

	/**
	 *
	 * @Route("/category/{id}", name="category.show", methods={"GET"}, requirements={"id"="\d+"}) 
	 * @author 
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
}
