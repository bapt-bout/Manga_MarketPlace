<?php

namespace App\Controller;

use App\Entity\Membre;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints\Unique;

class RegistrationController extends AbstractController
{
    #[Route('/inscription/afficher', name: 'inscription_afficher')]
    public function afficherFormulaire(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new Membre();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        // dd($user);

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/inscription/traiter', name: 'inscription_traiter', methods: 'POST')]
public function traiterFormulaire(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
{
    $user = new Membre();
    $form = $this->createForm(RegistrationFormType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Vérification d'unicité du pseudo
        $pseudo = $form->get('pseudo')->getData();
        $existingUser = $entityManager->getRepository(Membre::class)->findOneBy(['pseudo' => $pseudo]);

        if ($existingUser) {
            // Gérer le cas où le pseudo existe déjà (par exemple, afficher une erreur)
            $this->addFlash('danger', 'Ce pseudo est déjà utilisé.');

            return $this->redirectToRoute('inscription_afficher'); // Rediriger vers le formulaire d'inscription
        }

        // Attribuer le rôle "ROLE_USER"
        $user->setRoles(['ROLE_USER']);

        $user->setPassword(
            $userPasswordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            )
        )
        ->setDateEnregistrement(new \Datetime);

        $entityManager->persist($user);
        $entityManager->flush();
        // do anything else you need here, like send an email

        return $this->redirectToRoute('home'); // Rediriger vers la page d'accueil ou une autre page
    }

    // Renvoyer la vue du formulaire de traitement même en cas de formulaire non valide
    return $this->render('registration/register_traitement.html.twig', [
        'registrationForm' => $form->createView(),
    ]);
}

}
