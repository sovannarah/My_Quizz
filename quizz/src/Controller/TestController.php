<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{

	public function __construct(){

	}
    /**
     * @Route("/test", name="test")
     */
    public function index()
    {
		$this->denyAccessUnlessGranted('ROLE_USER', null, 'Unable to access this page!');
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
}
