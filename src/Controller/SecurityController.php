<?php

namespace App\Controller;

use App\Form\ResetPasswordRequestFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\MembreRepository;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\SendMailService;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'luffy')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/oubli-pass', name: 'forgotten_password')]
    public function forgottenPassword(): Response
    {
        return $this->render('security/reset_password_request.html.twig');
    }
    // #[Route('/oubli-pass', name: 'forgotten_password')]
    // public function forgottenPassword(
    //     Request $request,
    //     MembreRepository $usersRepository,
    //     TokenGeneratorInterface $tokenGenerator,
    //     EntityManagerInterface $entityManager,
    //     SendMailService $mail
    // )
    // {
    //     $form = $this->createForm(ResetPasswordRequestFormType::class);

    //     $form->handleRequest($request);
    // }

//     #[Route('/oubli-pass', name:'forgotten_password')]
//     public function forgottenPassword(
//         Request $request,
//         UsersRepository $usersRepository,
//         TokenGeneratorInterface $tokenGenerator,
//         EntityManagerInterface $entityManager,
//         SendMailService $mail
//     ): Response
//     {
//         $form = $this->createForm(ResetPasswordRequestFormType::class);

//         $form->handleRequest($request);

//         if($form->isSubmitted() && $form->isValid()){
//             //On va chercher l'utilisateur par son email
//             $user = $usersRepository->findOneByEmail($form->get('email')->getData());

//             // On vérifie si on a un utilisateur
//             if($user){
//                 // On génère un token de réinitialisation
//                 $token = $tokenGenerator->generateToken();
//                 $user->setResetToken($token);
//                 $entityManager->persist($user);
//                 $entityManager->flush();

//                 // On génère un lien de réinitialisation du mot de passe
//                 $url = $this->generateUrl('reset_pass', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
                
//                 // On crée les données du mail
//                 $context = compact('url', 'user');

//                 // Envoi du mail
//                 $mail->send(
//                     'no-reply@e-commerce.fr',
//                     $user->getEmail(),
//                     'Réinitialisation de mot de passe',
//                     'password_reset',
//                     $context
//                 );

//                 $this->addFlash('success', 'Email envoyé avec succès');
//                 return $this->redirectToRoute('app_login');
//             }
//             // $user est null
//             $this->addFlash('danger', 'Un problème est survenu');
//             return $this->redirectToRoute('app_login');
//         }

//         return $this->render('security/reset_password_request.html.twig', [
//             'requestPassForm' => $form->createView()
//         ]);
//     }
}
