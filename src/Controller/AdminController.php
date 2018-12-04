<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Category;
use App\Entity\Question;
use App\Entity\User;
use App\Form\AdminUserType;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 * @IsGranted("ROLE_ADMIN")
 */
class AdminController extends AbstractController
{
	/**
	 * @Route("/", name="admin.index")
	 */
	public function index()
	{
		return $this->render('admin/index.html.twig', [
			'controller_name' => 'AdminController',
		]);
	}

	/**
	* @Route("/category/", name="category_index", methods="GET")
	*/
		public function categoryIndex(CategoryRepository $categoryRepository): Response
		{
			return $this->render('admin/category/index.html.twig', ['categories' => $categoryRepository->findAll()]);
		}

	/**
	 * @Route("/category/new", name="category_new", methods="GET|POST")
	 */
	public function categoryNew(Request $request): Response
	{
		$category = new Category();
		$form = $this->createForm(CategoryType::class, $category);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($category);
			$em->flush();

			return $this->redirectToRoute('category_index');
		}

		return $this->render('admin/category/new.html.twig', [
			'category' => $category,
			'form' => $form->createView(),
		]);
	}

	/**
	 * @Route("/category/{id}", name="category_show", methods="GET")
	 */
	public function categoryShow(Category $category): Response
	{
		return $this->render('admin/category/show.html.twig', ['category' => $category]);
	}

	/**
	 * @Route("/category/{id}/edit", name="category_edit", methods="GET|POST")
	 */
	public function categoryEdit(Request $request, Category $category): Response
	{
		$em = $this->getDoctrine()->getManager();
		if ($request->isMethod('POST')) {
			$question = $this->getDoctrine()
				->getRepository(Question::class)
				->find($request->get('question_id'));
			$question->setQuestion($request->get('question'));
			$em->persist($question);
			$em->flush();

			foreach ($request->get('answers') as $key => $value) {
				$answer = $this->getDoctrine()
					->getRepository(Answer::class)
					->find($key);
				$answer->setAnswer($value);
				$em->persist($answer);
				$em->flush();
			}
		}
		return $this->render('admin/category/edit.html.twig', [
			'category' => $category,
		]);
	}

	/**
	 * @Route("/category/{id}", name="category_delete", methods="DELETE")
	 */
	public function categoryDelete(Request $request, Category $category): Response
	{
		if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
			$em = $this->getDoctrine()->getManager();
			$em->remove($category);
			$em->flush();
		}

		return $this->redirectToRoute('category_index');
	}

		/**
	 * @Route("/user/", name="user_index", methods="GET")
	 */
		public function userIndex(UserRepository $userRepository): Response
		{
			return $this->render('admin/user/index.html.twig', ['users' => $userRepository->findAll()]);
		}

	/**
	 * @Route("/user/new", name="user_new", methods="GET|POST")
	 */
	public function userNew(Request $request): Response
	{
		$user = new User();
		$form = $this->createForm(AdminUserType::class, $user);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($user);
			$em->flush();

			return $this->redirectToRoute('user_index');
		}

		return $this->render('admin/user/new.html.twig', [
			'user' => $user,
			'form' => $form->createView(),
		]);
	}

	/**
	 * @Route("/user/{id}", name="user_show", methods="GET")
	 */
	public function userShow(User $user): Response
	{
		return $this->render('admin/user/show.html.twig', ['user' => $user]);
	}

	/**
	 * @Route("/user/{id}/edit", name="user_edit", methods="GET|POST")
	 */
	public function userEdit(Request $request, User $user): Response
	{
		$form = $this->createForm(AdminUserType::class, $user);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$this->addFlash('success', 'User Updated !');
			$this->getDoctrine()->getManager()->flush();

			return $this->redirectToRoute('user_edit', ['id' => $user->getId()]);
		}

		return $this->render('admin/user/edit.html.twig', [
			'user' => $user,
			'form' => $form->createView(),
		]);
	}

	/**
	 * @Route("/user/{id}", name="user_delete", methods="DELETE")
	 */
	public function userDelete(Request $request, User $user): Response
	{
		if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
			$em = $this->getDoctrine()->getManager();
			$em->remove($user);
			$em->flush();
		}

		return $this->redirectToRoute('user_index');
	}
}
