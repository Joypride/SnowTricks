<?php 

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Trick;
use App\Message\Notification;
use Symfony\Component\Messenger\MessageBusInterface;


class HomeController extends AbstractController
{
    /**
    * @Route("/", name="home")
    */
    public function index(ManagerRegistry $doctrine)
    {
        $repository = $doctrine->getRepository(Trick::class);
        $tricks = $repository->findAll();

        return $this->render('index.html.twig', [
            'tricks' => $tricks,
        ]);
    }
}
