<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Group;
use App\Entity\Media;
use App\Entity\Tricks;
use App\Entity\User;
use DateTime;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function loadUsers(ObjectManager $manager): void
    {
        $admin = new User();

        $this->addReference('admin', $admin);

        $admin->setSurname('Jimmy');
        $admin->setName('Sweat');
        $admin->setUsername('Sweety');
        $admin->setEmail('jimmy.sweat@snowtricks.fr');

        $adminPassword = $this->passwordHasher->hashPassword($admin,'adminPassword');

        $admin->setPassword($adminPassword);
        $admin->setIsActivated(true);

        $manager->persist($admin);

        for ($i = 0; $i <= 20; ++$i) {
            
            $faker = Factory::create();
            $user = new User();
            $user->setUsername($faker->userName());
            $user->setEmail($faker->email());
            $user->setPassword($faker->password());
            $user->setName($faker->lastName());
            $user->setSurname($faker->firstName());
            $user->setIsActivated(true);

            $this->addReference('user'. $i, $user);

            $manager->persist($user);
        }
        $manager->flush();
    }

    public function loadTricks(ObjectManager $manager): void 
    {
        $groups = [
            'Grabs' => [
                'Mute',
                'Indy',
                'Stalefish',
                'Tail Grab',
                'Japan Air',
            ],
            'Rotations' => [
                '180',
                '360',
                '720',
            ],
            'Flips' => null,
            'Slides' => [
                'Nose Slide',
                'Tail Slide',
            ],
            'One Foot' => null,
            'Old School' => [
                'Back Side Air',
                'Method Air',
            ],
        ];

        $i = 0;

        foreach ($groups as $group => $trickList) {
            $groupTrick = new Group($group);

            $groupTrick->setName($group);

            $manager->persist($groupTrick);

            if (null !== $trickList) {
                foreach ($trickList as $trickName) {
                    $faker = Factory::create();

                    $trickMedia = new Media();

                    $trickMedia->setUrl('https://images.unsplash.com/photo-1478700485868-972b69dc3fc4?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1740&q=80');
                    $trickMedia->setType(0);

                    $trick = new Tricks();

                    $trick->setName($trickName);
                    $trick->setDescription($faker->paragraph(3, 5));
                    $trick->getMedia($trickMedia);
                    $trick->setCategory($groupTrick);
                    $trick->setUser($this->getReference('admin'));

                    $manager->persist($trickMedia);
                    $manager->persist($trick);
                    $manager->flush();

                    $this->addReference('trick' . $i, $trick);

                    ++$i;
                }
            }
        }

        $manager->flush();
    }

    public function loadComments(ObjectManager $manager): void
    {
        for ($count = 0; $count < 20; $count++) {
            $faker = Factory::create();

            $randomNumber = rand(0, 19);
            $user = $this->getReference('user' . $randomNumber);

            $randomTrick = rand(0, 11);
            $trick = $this->getReference('trick' . $randomTrick);

            $dateComment = new DateTime();
            
            $comment = new Comment();
            $comment->setDate($dateComment);
            $comment->setContent($faker->sentence(10));
            $comment->setUser($user);
            $comment->setTrick($trick);
            $manager->persist($comment);
        }
        $manager->flush();
    }

    public function load(ObjectManager $manager): void
    {
        $this->loadUsers($manager);
        $this->loadComments($manager);
        $this->loadTricks($manager);
    }
}
