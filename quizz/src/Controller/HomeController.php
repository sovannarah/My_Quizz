<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(CategorieRepository $categorie)
    {
    	$allCategories = $categorie->findAll();
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
			'categories' => $allCategories
        ]);
    }
}
