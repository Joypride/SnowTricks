<?php 

namespace App\Controller;

use App\Entity\Trick;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class TrickController extends AbstractController
{
    /**
    * @Route("/créer", name="create_trick")
    */
    public function createTrick(ManagerRegistry $doctrine) : Response
    {
        $entityManager = $doctrine->getManager();

        $trick = new Trick();
        $trick->setName('Keyboard');
        $trick->setDescription(1999);

        $entityManager->persist($trick);
        $entityManager->flush();

        return new Response('Saved new product with id '.$trick->getId());
    }
    
    /**
    * @Route("/trick/detail/{id}", name="trick_detail")
    */
    public function trickDetail(ManagerRegistry $doctrine) : Response
    {
        $repository = $doctrine->getRepository(Trick::class);
        $tricks = $repository->findAll();

        return $this->render('trick_detail.html.twig', [
            'tricks' => $tricks
        ]);
    }

    /**
    * @Route("/modifier", name="modify_trick")
    */
    public function modifyTrick() : Response
    {
        return $this->render('index.html.twig');
    }

    /**
    * @Route("/supprimer", name="delete_trick")
    */
    public function deleteTrick() : Response
    {
        return $this->render('index.html.twig');
    }
}
