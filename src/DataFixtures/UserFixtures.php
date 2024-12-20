<?php

namespace App\DataFixtures;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends AbstractFixtures
{
    private const USER_PASSWORD = 'aaa';

    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
    ) {}

    public function getEntityClass(): string
    {
        return User::class;
    }

    public function getData(): iterable
    {
        yield [
            'username' => 'admin',
            'password' => self::USER_PASSWORD,
            'email' => 'admin@test.com',
            'roles' => ['ROLE_ADMIN'],
        ];

        yield [
            'username' => 'user',
            'password' => self::USER_PASSWORD,
            'email' => 'user@test.com',
            'roles' => ['ROLE_USER'],
        ];

        yield [
            'username' => 'Joe Sans les voyelles',
            'password' => self::USER_PASSWORD,
            'email' => 'joe@test.com',
            'roles' => ['ROLE_USER'],
        ];

        yield [
            'username' => 'Ban speedrun',
            'password' => self::USER_PASSWORD,
            'email' => 'hehehe@test.com',
            'roles' => ['ROLE_BANNED'],
        ];
    }

    protected function postInstantiate($entity): void
    {
        $entity->setPassword($this->passwordHasher->hashPassword($entity, $entity->getPassword()));
    }
}
