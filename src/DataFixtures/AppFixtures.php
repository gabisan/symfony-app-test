<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var \Faker\Factory
     */
    private $faker;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->faker = \Faker\Factory::create();

    }

    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadBlogPost($manager);
    }

    public function loadBlogPost(ObjectManager $manager)
    {
        $user = $this->getReference('admin');

        $blogPost1 = new BlogPost();

        $blogPost1->setTitle($this->faker->realText(30));
        $blogPost1->setPublished($this->faker->dateTime);
        $blogPost1->setContent($this->faker->realText());
        $blogPost1->setAuthor($user);
        $blogPost1->setSlug('test-title-tem');

        $manager->persist($blogPost1);
        $manager->flush();
    }

    public function loadComments(ObjectManager $manager)
    {

    }

    public function loadUsers(ObjectManager $manager)
    {
        $user = new User();

        $user->setUsername('admin');
        $user->setEmail('admin@email.com');
        $user->setName('Angelo Ten');

        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'admin123'
        ));

        $this->addReference('admin', $user);

        $manager->persist($user);
        $manager->flush();
    }
}
