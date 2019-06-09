<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
	/**
	 * @param Request $request
	 * @param ObjectManager $manager
	 * @param UserPasswordEncoderInterface $encoder
	 * @Route("/register", name="user_register")
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function register(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder, \Swift_Mailer $mailer) {
		$user = new User();
		$form = $this->createForm(RegisterType::class, $user);
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid()) {
			$hash = $encoder->encodePassword($user, $user->getPassword());
			$user->setPassword($hash);
			$manager->persist($user);
			$manager->flush();
			$message = (new \Swift_Message('Hello'))
				->setFrom('sovannara.hem@gmail.com')
				->setTo($user->getEmail())
				->setBody(
				    $this->renderView(
				        'user/confirm.html.twig',
                        ['userMail' => $user->getEmail()]
                    )
                );
			$mailer->send($message);
			return $this->redirectToRoute('user_confirm');
		}
		return $this->render('user/register.html.twig', [
			'formUser' => $form->createView()
		]);
	}

	/**
	 * @Route("/login", name="user_login")
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function login() {
		return $this->render("user/login.html.twig");
	}

	/**
	 * @Route("/logout", name="user_logout")
	 */
	public function logout() {

	}

    /**
     * @Route("/subscribe", name="user_confirm")
     */
	public function confirmMessage() {
	    return $this->render('user/confirmMessage.html.twig');
    }

	/**
	 * @Route("/account", name="user_account")
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
    public function account() {
		return $this->render('user/account.html.twig');
	}
}
