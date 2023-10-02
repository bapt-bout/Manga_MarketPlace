<?php

namespace App\Controller;

use App\Entity\Membre;
use App\Form\MembreType;
use App\Form\UserPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class MembreController extends AbstractController
{
    /**
     * This controller allows us to edit a user's profile.
     *
     * @param Membre $choosenUser
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param UserPasswordHasherInterface $hasher
     * @return Response
     */
    #[Security("is_granted('ROLE_USER') and user.getId() == id")]
    #[Route('/compte/edit', name: 'compte_edit', methods: ['GET', 'POST'])]
    public function edit(
        Membre $choosenUser,
    Request $request,
    EntityManagerInterface $manager,
    UserPasswordHasherInterface $hasher
    ): Response {
        // Récupérez l'instance Membre à partir de la base de données en fonction de l'ID.
        // $choosenUser = $manager->getRepository(Membre::class)->find($id);

        if (!$choosenUser) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        // Créez le formulaire pour la modification du profil utilisateur.
        $form = $this->createForm(MembreType::class, $choosenUser);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Vous pouvez vérifier le mot de passe de l'utilisateur ici s'il le souhaite.

            // Enregistrez les modifications apportées au profil utilisateur.
            $manager->flush();

            $this->addFlash(
                'success',
                'Les informations de votre compte ont bien été modifiées.'
            );

            return $this->redirectToRoute('recipe.index');
        }

        return $this->render('membre/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * This controller allows us to edit a user's password.
     *
     * @param Membre $choosenUser
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param UserPasswordHasherInterface $hasher
     * @return Response
     */
    #[Security("is_granted('ROLE_USER') and user === choosenUser")]
    #[Route('/compte/edit-mot-de-passe/{id}', name: 'compte_edit_mdp', methods: ['GET', 'POST'])]
    public function editPassword(
        Membre $choosenUser,
        Request $request,
        EntityManagerInterface $manager,
        UserPasswordHasherInterface $hasher
    ): Response {
        // Créez le formulaire pour la modification du mot de passe.
        $form = $this->createForm(UserPasswordType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Vous pouvez vérifier l'ancien mot de passe de l'utilisateur ici s'il le souhaite.

            // Mettez à jour la date d'enregistrement et le mot de passe de l'utilisateur.
            $choosenUser->updateDateEnregistrement(new \DateTimeImmutable());
            $choosenUser->setPassword(
                $hasher->hashPassword($choosenUser, $form->getData()['newPassword'])
            );

            $manager->persist($choosenUser);
            $manager->flush();

            $this->addFlash(
                'success',
                'Le mot de passe a été modifié.'
            );

            return $this->redirectToRoute('compte');
        }

        return $this->render('membre/edit_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
