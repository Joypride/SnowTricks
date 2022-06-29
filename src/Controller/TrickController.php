<?php 

namespace App\Controller;

use App\Entity\Trick;
use App\Entity\Comment;
use App\Entity\Group;
use App\Form\CommentType;
use App\Form\CreateTrickType;
// use App\Repository\TrickRepository;
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
            $user = $this->getUser();
            $trick->setUser($user);

            $trick->setCategory();

            $entityManager->persist($trick);
            $entityManager->flush();

            $this->addFlash('success', 'Trick created');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('create_trick.html.twig', [
            'form'=> $form->createView(),
            'category' => $doctrine->getRepository(Group::class)->findAll()
        ]);
    }
    
    /**
    * @Route("/trick/detail/{id}-{slug}", name="trick_detail")
    */
    public function trickDetail(int $id, Request $request, ManagerRegistry $doctrine) : Response
    {
        $entityManager = $doctrine->getManager();

        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        // $trick = $this->trickRepository->findOneBySlug($request->attributes->get('slug'));
        $trick = $doctrine->getManager()->getRepository(Trick::class)->find($id);

        if($form->isSubmitted() && $form->isValid()){

            // $comment->setContent($form['content']->getData());
            $comment->setDate(new \DateTime('now'));
            $comment->setTrick($trick);
            $comment->setUser($this->getUser());

            $entityManager->persist($comment);
            $entityManager->flush();

            $this->addFlash('success','Comment send');
        }

        return $this->render('trick_detail.html.twig', [
            'trick' => $doctrine->getRepository(Trick::class)->find($id),
            'commentForm' => $form->createView(),
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
