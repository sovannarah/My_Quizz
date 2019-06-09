<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('categorie', CollectionType::class, [
            	'entry_type' => CategorieType::class,
//				'entry_options' => ['label' => true],
				'allow_add' => true,
			])
			->add('question')
			->add('reponses', CollectionType::class, [
				'entry_type' => ReponseType::class,
				'entry_options' => ['label' => true],
			])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}
