<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoder
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = Factory::create();

        for ($i=0; $i < 10 ; $i++) { 
            $user = new User();
            $passHash = $this->encoder->encodePassword($user,'1234');

            $user->setEmail($faker->email)
                 ->setPassword($passHash);
            
            $manager->persist($user);

            for ($a=0; $a < random_int(5,15) ; $a++) { 
                $article = (new Article())->setAuthor($user)
                                          ->setContent($faker->text(300))
                                          ->setName($faker->text(50));
                $manager->persist($article);
            }
        }
        $manager->flush();
    }
}
