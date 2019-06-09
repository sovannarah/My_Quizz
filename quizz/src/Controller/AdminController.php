<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Question;
use App\Entity\Reponse;
use App\Entity\User;
use App\Form\CategorieType;
use App\Form\QuestionType;
use App\Form\ReponseType;
use App\Repository\CategorieRepository;
use App\Repository\QuestionRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Security;


/**
 * Class AdminController
 * @package App\Controller
 * @Route("/admin")
 */
class AdminController extends AbstractController
{





    /**
     * @Route("/index", name="admin_index")
     */
    public function index()
    {

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

	/**
	 * @param Request $request
	 * @param ObjectManager $manager
	 * @Route("/new", name="quizz_new")
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function register(Request $request, ObjectManager $manager) {
		$quizz = new Question();
//		for($i = 0; $i < 3; $i++) {
//			$quizz->addReponse(new Reponse());
//		}
		dump($quizz);
		$form= $this->createFormBuilder($quizz)
			->add('question')
			->add('reponses')
			->add("sauvegarder", SubmitType::class)
			->getForm();
		return $this->render('admin/newQuizz.html.twig', [
			'formQuizz' => $form->createView()
		]);
	}


	/**
	 * @param CategorieRepository $ctg
	 * @Route("/quizz", name="admin_quizz")
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function readQuizz(CategorieRepository $ctg) {
		$categories = $ctg->findAll();
		return $this->render('admin/quizz.html.twig', [
			'categories' => $categories
		]);
	}

	/**
	 * @param QuestionRepository $qsn
	 * @Route("/quizzz/{idCtg}", name="admin_update_quizz")
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function readUpdateQuizz(QuestionRepository $qsn, $idCtg) {
		dump($idCtg);
		$questions = $qsn->findBycategorie($idCtg);
		dump($questions);
		return $this->render('admin/updateQuizz.html.twig', [
			'questions' => $questions,
//			'reponses' => $reponses
		]);
	}

	public function updateQuizz()
	{

	}

	public function deleteQuizz()
	{

	}

	/**
	 * @param UserRepository $usr
	 * @Route("/users", name="admin_users")
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function readAccount( UserRepository $usr)
	{
		$users = $usr->findAll();
		return $this->render('admin/user.html.twig', [
			'users' => $users
		]);
	}

	/**
	 * @Route("/user/{id}", name="update_user")
	 */
	public function updateAccount( UserRepository $usr, Request $request, ObjectManager $manager, $id)
	{
		$users = $usr->findByid($id)[0];
		$form = $this->createFormBuilder($users)
			->add('username')
			->add('email')
			->add('password')
			->add('confirm_password')
			->add('roles')
			->add("sauvegarder", SubmitType::class)
			->getForm();
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid()) {
			$manager->persist($users);
			$manager->flush();
			return $this->redirectToRoute('admin_users');
		}
		return $this->render("admin/updateUser.html.twig", [
			'formUser' => $form->createView()
		]);
	}
}