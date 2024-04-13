<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
class AppFixtures extends Fixture
{
    private ?UserPasswordHasherInterface $passwordHasher = null;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $em): void
    {

        $user1 = new User();
        $user1  ->setLogin('sadmin')
            ->setFirstname('topi')
            ->setName('nambour')
            ->setBirthdate(new \DateTime())
            ->setRoles(['ROLE_SUPERADMIN']);
            $user1 ->setPassword('$2y$13$L7hFhe4npdebqpjOEBSMQ.Qt2ZBdBuf4ByLw8OteiUKowdeHnXx2i');

        $em->persist($user1);

        $user2 = new User();
        $user2  ->setLogin('gilles')
            ->setFirstname('topi')
            ->setName('nambour')
            ->setBirthdate( new \DateTime())
            ->setRoles(['ROLE_ADMIN']);
        $user2 ->setPassword('$2y$13$c/R6x5jyR91olB6chgW6iuJlK2BXh8yVDGtLgy.xvu0BNR4v69lju');

        $em->persist($user2);

        $user3 = new User();
        $user3  ->setLogin('rita')
            ->setFirstname('topi')
            ->setName('nambour')
            ->setBirthdate( new \DateTime())
            ->setRoles(['ROLE_CLIENT']);
        $user3 ->setPassword('$2y$13$/.2E6LWo.wGfs4DGhRNJbOMBc//mAwMb3IoleFLw6bpzKSnkc9vW6');

        $em->persist($user3);

        $user4 = new User();
        $user4  ->setLogin('boumediene')
            ->setFirstname('topi')
            ->setName('nambour')
            ->setBirthdate( new \DateTime())
            ->setRoles(['ROLE_CLIENT']);
        $user4 ->setPassword('$2y$13$lDN8OeCYXpchwjAzjMyX..K.LNjjMISsFkMtixfm79iSDXWnURpe6');

        $em->persist($user4);

        $em->flush();
    }
}
