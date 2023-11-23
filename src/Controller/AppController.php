<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class AppController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'home')]
    public function index(ProduitRepository $repo, CartService $cs): Response
    {
        $produits = $repo->findAll();
        $cart = $cs->cart();

        return $this->render('app/index.html.twig', [
            'produits' => $produits,
            'cart' => $cart,
        ]);
    }

    #[Route('/about', name: 'about')]
    public function about(): Response
    {
        return $this->render('app/about.html.twig');
    }

    #[Route('/rgpd', name: 'rgpd')]
    public function rgpd(): Response
    {
        return $this->render('app/rgpd.html.twig');
    }

    #[Route('/cgu', name: 'cgu')]
    public function cgu(): Response
    {
        return $this->render('app/cgu.html.twig');
    }

    #[Route('/cgv', name: 'cgv')]
    public function cgv(): Response
    {
        return $this->render('app/cgv.html.twig');
    }

    #[Route('/mentions', name: 'mentions')]
    public function mentions(): Response
    {
        return $this->render('app/mentions.html.twig');
    }

    #[Route('/contact', name: 'contact')]
    public function show(): Response
    {
        return $this->render('app/contact.html.twig');
    }

    #[Route('/compte/commandes', name: 'app_compte')]
    public function compteCommande()
    {
        return $this->render('app/account.html.twig');
    }

    #[Route('/vendre-produit', name: 'vendre_produit')]
    public function vendre(Request $request): Response
    {
        $produit = new Produit();

         // Définir la date d'enregistrement ici
         $produit->setDateEnregistrement(new \DateTime());

        $form = $this->createForm(ProduitType::class, $produit);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion du téléchargement de la photo
            $photoFile = $form->get('photo')->getData();

            if ($photoFile) {
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $photoFile->guessExtension();

                try {
                    $photoFile->move(
                        $this->getParameter('upload_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Gestion de l'erreur de téléchargement ici, par exemple afficher un message d'erreur
                }

                $produit->setPhoto($newFilename);
            }


            $this->entityManager->persist($produit);
            $this->entityManager->flush();

            // Ajouter un message flash de succès
            $this->addFlash('success', 'Le manga a été mis en vente avec succès !');

            return $this->redirectToRoute('home');
        }

        return $this->render('app/sell.html.twig', [
            'form' => $form->createView(),
        ]);
    }


}
