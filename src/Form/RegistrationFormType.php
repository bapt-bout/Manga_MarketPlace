<?php

namespace App\Form;


use App\Entity\Membre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
            ->add('prenom')
            ->add('nom')
            ->add('pseudo')
            ->add('email', null, [
                'constraints' => [
                    new Callback([$this, 'validateEmailDomain']),
                ],
            ])
            ->add('civilite', ChoiceType::class, [
                'choices' => [
                    'Homme' => 'H',
                    'Femme' => 'F',
                ],
            ])
            ->add('accepteConditions', CheckboxType::class, [
                'label' => "J'accepte les conditions générales d'utilisation",
                'mapped' => false, // Ne pas lier ce champ à une propriété de l'entité
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter les conditions générales d\'utilisation.',
                    ]),
                ],
            ])
            // ->add('date_enregistrement')

            // ->add('agreeTerms', CheckboxType::class, [
            //     'mapped' => false,
            //     'constraints' => [
            //         new IsTrue([
            //             'message' => 'You should agree to our terms.',
            //         ]),
            //     ],
            // ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => ['label' => 'mot de passe'],
                'second_options' => ['label' => 'confimer le mot de passe'],
                'invalid_message' => 'les mots de passes ne correspondent pas',

                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le mot de passe obligatoire',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Votre mot de passe doit faire au minimum {{ limit }} characteres',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                    new Regex([
                        'pattern' => '/^(?=.*[a-z])(?=.*\d)(?=.*[@$!%#*?&])[A-Za-z\d@$!%#*?&]{12,}$/',
                        'match' => true,
                        "message" => 'Votre mot de passe doit contenir 12 caratères, avec au moins un chiffre, un caractère spécial (@$!#%*?&), une lettre minuscule et une lettre majuscule'
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Membre::class,
        ]);
    }


    public function validateEmailDomain($value, ExecutionContextInterface $context)
    {
        $allowedDomains = ['gmail.com', 'yahoo.com', 'yahoo.fr', 'laposte.net', 'hotmail.com', 'icloud.com', 'outlook.com'];

        $emailParts = explode('@', $value);
        $domain = end($emailParts);

        if (!in_array($domain, $allowedDomains)) {
            $context
                ->buildViolation('L\'adresse email doit être valide')
                ->atPath('email')
                ->addViolation();
        }
    }
}