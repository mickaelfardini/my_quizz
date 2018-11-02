<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/index", name="index", methods={"GET"})
     */
    public function index()
    {
        // return $this->render('index/index.html.twig', [
        return $this->render('index/index.pug', [
            'controller_name' => 'IndexController',
        ]);
    }
}
