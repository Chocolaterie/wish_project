<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Wish;

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
     * @Route("/wish/create", name="app_wish_create")
     */
    public function wishCreate(): Response
    {

        // Instancie l'objet et on "hydrate"
        $wish = new Wish();

        $wish->setTitle("Souhait test");
        $wish->setDescription("test");
        $wish->setAuthor("Stephane");
        $wish->setIsPublished(true);
        $wish->setDateCreated("21-05-2650");

        // Get l'entity manager de wish
        $em = $this->getDoctrine()->getManager();
        $em->persist($wish); // alimente l'id généré en même temps
        $em->flush();

        return new Response(sprintf("Le souhait n° %d à bien été sauvegardé", $wish->getId()));
    }
}
