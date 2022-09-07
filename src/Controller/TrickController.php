<?php 

namespace App\Controller;

use App\Entity\Trick;
use App\Entity\Comment;
use App\Entity\Group;
use App\Form\CommentType;
use App\Form\CreateTrickType;
use App\Service\CommentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class TrickController extends AbstractController
{
    /**
    * @Route("/nouveau-trick", name="create_trick")
    */
    public function createTrick(ManagerRegistry $doctrine, Request $request) : Response
    {
        $entityManager = $doctrine->getManager();

        $trick = new Trick();
        $form = $this->createForm(CreateTrickType::class, $trick);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            

            $trick->setUser($this->getUser());
            foreach ($trick->getMedias() as $media) {
                $media->setTrick($trick);
            }

            $entityManager->persist($trick);
            $entityManager->flush();

            $this->addFlash('success', 'Trick created');

            return $this->redirectToRoute('home');
        }


        return $this->render('create_trick.html.twig', [
            'createForm'=> $form->createView(),
            'category' => $doctrine->getRepository(Group::class)->findAll()
        ]);
    }
    
    /**
    * @Route("/trick/detail/{id}-{slug}", name="trick_detail")
    */
    public function trickDetail(int $id, Request $request, ManagerRegistry $doctrine, CommentService $commentService) : Response
    {
        $entityManager = $doctrine->getManager();

        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        $trick = $doctrine->getManager()->getRepository(Trick::class)->find($id);

        if($form->isSubmitted() && $form->isValid()){

            $comment->setDate(new \DateTime('now'));
            $comment->setTrick($trick);
            $comment->setUser($this->getUser());

            $entityManager->persist($comment);
            $entityManager->flush();

            $this->addFlash('success','Comment send');
        }

        return $this->render('trick_detail.html.twig', [
            'trick' => $doctrine->getRepository(Trick::class)->find($id),
            'comments' => $commentService->getPaginatedComments($trick),
            'commentForm' => $form->createView(),
        ]);
    }

    /**
    * @Route("/trick/edit/{id}", name="edit_trick")
    */
    public function editTrick(ManagerRegistry $doctrine, Request $request, int $id)
    {
        $entityManager = $doctrine->getManager();

        $trick = new Trick();
        $trick = $entityManager->getRepository(Trick::class)->find($id);
        $form = $this->createForm(CreateTrickType::class, $trick);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            

            $trick->setUser($this->getUser());
            foreach ($trick->getMedias() as $media) {
                $media->setTrick($trick);
            }

            $entityManager->persist($trick);
            $entityManager->flush();

            $this->addFlash('success', 'Trick modifié !');

            return $this->redirectToRoute('home');
        }

        return $this->render('edit_trick.html.twig', [
            'trick' => $doctrine->getRepository(Trick::class)->find($id),
            'createForm'=> $form->createView(),
            'category' => $doctrine->getRepository(Group::class)->findAll()
        ]);
    }

    /**
    * @Route("/trick/delete/{id}", name="delete_trick", methods={"DELETE", "GET"})
    */
    public function deleteTrick(int $id, Trick $trick, ManagerRegistry $doctrine)
    {
        $doctrine->getManager()->getRepository(Trick::class)->find($id);

        $doctrine->getManager()->remove($trick);
        $doctrine->getManager()->flush();

        $this->addFlash('success', $trick->getName() . " a été supprimée avec succès !");

        return $this->redirectToRoute('home');
    }
}
