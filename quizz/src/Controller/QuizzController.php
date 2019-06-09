<?php

namespace App\Controller;

use App\Repository\QuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class QuizzController extends AbstractController
{
	private $score;

	public function __construct() {
		$this->score = 0;
	}
	/**
	 * @param QuestionRepository $qsn
	 * @param $idCtg
	 * @param $idQsn
	 * @param Request $request
	 * @Route("/quizz/{idCtg}/{idQsn}", name="quizz")
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
    public function index(QuestionRepository $qsn, $idCtg, $idQsn, Request $request)
    {
    	$check = "";
    	$questions = $qsn->findBycategorie($idCtg);
		$question = $questions[$idQsn];
		$reponses = $question->getReponses();
		$currQsn = $request->get("idQsn");
		$nextQsn = $currQsn;
		$end = "";
		$this->score = $this->score;
		return $this->render('quizz/index.html.twig', [
			'end' => $end,
			'check' => $check,
			'idCtg' => $idCtg,
			'nextQsn' => $nextQsn,
			'currQsn' => $currQsn,
			'controller_name' => 'QuizzController',
			'question' => $question,
			'reponses' => $reponses
		]);
    }

	/**
	 * @param QuestionRepository $qsn
	 * @param $idCtg
	 * @param $idQsn
	 * @param Request $request
	 * @Route("/check/{idCtg}/{idQsn}", name="checkRep")
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
    public function checkValue(QuestionRepository $qsn, $idCtg, $idQsn, Request $request) {
		$questions = $qsn->findBycategorie($idCtg);
		$question = $questions[$idQsn];
		$reponses = $question->getReponses();
		$currQsn = $request->get("idQsn");
		$nextQsn = $currQsn+1;
		for($i = 0; $i < count($reponses); $i++) {
			if($reponses[$i]->getReponse() === $request->get("choice")){
				if($reponses[$i]->getReponseExpected() === 1) {
					$check = "vrai";
					$this->score++;
				} else {
					$check = "faux";
				};
			}
		}
		$end = "" ;
		dump($this->score);
		if((int)$currQsn === count($questions) - 1){
			$end = "end";
		}

		return $this->render('quizz/index.html.twig', [
			'end' => $end,
			'check' => $check,
			'idCtg' => $idCtg,
			'nextQsn' => $nextQsn,
			'currQsn' => $currQsn,
			'controller_name' => 'QuizzController',
			'question' => $question,
			'reponses' => $reponses
		]);
	}
}
