<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Wish;
use App\Form\WishType;

class WishController extends AbstractController
{
    /**
     * @Route("/wish-list", name="app_wish_list")
     */
    public function wishList(): Response
    {
        // Repo Wish
        $repoWish = $this->getDoctrine()->getRepository(Wish::class); // Récuperer l'entity manager doctrine
        
        // la liste de tout les voeux
        //  $wishList = $repoWish->findAll();
        $wishList = $repoWish->findBy(array(), null, 20, null);

        return $this->render('wish/index.html.twig', [
            "wishList" => $wishList
        ]);
    }

    /**
     * @Route("/wish/show/{id}", name="app_wish_show")
     */
    public function wishShow($id): Response
    {
        // Repo Wish
        $repoWish = $this->getDoctrine()->getRepository(Wish::class); // Récuperer l'entity manager doctrine
        
        // Je récupere un Wish 
        $wish = $repoWish->find($id);

        return $this->render('wish/show.html.twig', ["wish" => $wish]);
    }

    /**
     * @Route("/wish/create/{id}", name="app_wish_create")
     */
    public function wishCreate($id = -1, Request $request): Response
    {
        // Part : 01
        // Si id < 1 = Par defaut Wish vide
        $wish = new Wish();
        // Mais si l'id > 0 : Récuperer un existant
        if ($id > 0 ){
            // Repo Wish
            $repoWish = $this->getDoctrine()->getRepository(Wish::class); // Récuperer l'entity manager doctrine
        
            $wish = $repoWish->find($id);
        }

        // Instancie le formulaire WishType avec un Wish vide
        $wishForm = $this->createForm(WishType::class, $wish);

        // Part : 02
        // Ecouter la requette http 
        $wishForm->handleRequest($request);

        // Part : 03
        // --Tester si le form à des données envoyées
        if ($wishForm->isSubmitted() && $wishForm->isValid()){
            // Traitement
            // -- récuperer l'entité du formumlaire
            $wishToSave = $wishForm->getData();

            // -- force published a true et date d'aujourd'hui
            $wishToSave->setIsPublished(true);
            $wishToSave->setDateCreated("12-03-2022"); //date_format(\DateTime(), "dd-mm-yyyy")
            // ps: c'est ici quon génére un slug en théorie quand necessaire

            // -- partie base de données
            $em = $this->getDoctrine()->getManager();
            $em->persist($wishToSave);
            $em->flush();

            // Message temporaire
            $this->addFlash("message_success", "Idea successfully added!");

            // Redirection sur detail du souhait à partir d'id
            return $this->redirectToRoute("app_wish_show", [
                "id" => $wishToSave->getId()
            ]);
        }

        // -- Sinon juste afficherr le formualaire vide par défaut (premiere fois)
        // Retourner le rendu
        return $this->render('wish/wish-form.html.twig', [
            "wishForm" => $wishForm->createView()
        ]);
    }
}
