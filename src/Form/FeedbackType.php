<?php

namespace App\Form;

use App\Entity\Feedback;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class FeedbackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('grade', RangeType::class, [
	            'label' => false,
	            'attr' => [
		            'min' => 0,
		            'max'=> 5,
		            'step' => 0.5,
	            ]
            ])
            ->add('comment', TextareaType::class, [
				'label' => 'Votre message',
				'label_attr' => [
					'class' => 'text-gray-800 dark:text-gray-100 my-5'
				],
				'attr' => [
					'class' => 'w-full rounded-md text-gray-800 bg-gray-200',
					'maxLength' => 300,
					'placeholder' => 'message...'
				],
				'help' => 'Max 300 caractères',
				'help_attr' => ['class' => 'text-sm text-gray-100 italic dark:text-gray-100'
				],
				'constraints' => [
					new NotBlank([
						'message' => 'Ce champs ne doit être vide'
					])
				],
				'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Feedback::class,
        ]);
    }
}
