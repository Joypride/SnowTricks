<?php 

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use App\Form\LoginType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    /**
    * @Route("/inscription", name="register")
    */
    public function register(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, MailerInterface $mailer) : Response
    {
        $user = new User();

        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){

            $passwordHashed = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($passwordHashed);

            $entityManager->persist($user);
            $entityManager->flush();

            $email = (new TemplatedEmail())
                ->from('jimmy@snowtricks.com')
                ->to($user->getEmail())
                ->subject('Activez votre compte !')
                ->htmlTemplate('emails/signup.html.twig')
                ->context([
                    'username' => $user->getUsername(),
                ])
            ;
            $mailer->send($email);

            $this->addFlash(
                'message',
                'Un email de confirmation vient de vous être envoyé. Cliquez sur le lien pour activer votre compte.'
            );
            return $this->redirectToRoute('home');
        }
        
        return $this->render('register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
    * @Route("/connexion", name="login")
    */
    public function login(Request $request) : Response
    {
        $user = new User();

        $form = $this->createForm(LoginType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){ }

        return $this->render('login.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
