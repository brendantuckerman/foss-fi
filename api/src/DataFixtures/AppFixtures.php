<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use App\Entity\Scenario;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class AppFixtures extends Fixture
{
    // We need to create the admin entity to avoid it being removed evry time
    // fixtures are loaded
    public function __construct(
        private PasswordHasherFactoryInterface $passwordHasherFactory,
    ){
    }

    public function load(ObjectManager $manager): void
    {
        $scenarios = [
            [
                'label' => 'Conservative',
                'income' => 60000,
                'outgoings' => 30000,
                'fiTarget' => 750000,
                'investmentAmount' => 50000,
                'returnRate' => '4.00',
            ],
            [
                'label' => 'Moderate',
                'income' => 80000,
                'outgoings' => 35000,
                'fiTarget' => 1000000,
                'investmentAmount' => 100000,
                'returnRate' => '6.00',
            ],
            [
                'label' => 'Aggressive',
                'income' => 100000,
                'outgoings' => 40000,
                'fiTarget' => 1250000,
                'investmentAmount' => 200000,
                'returnRate' => '8.00',
            ],
        ];

        foreach ($scenarios as $data) {
            $scenario = (new Scenario())
                ->setLabel($data['label'])
                ->setIncome($data['income'])
                ->setOutgoings($data['outgoings'])
                ->setFiTarget($data['fiTarget'])
                ->setInvestmentAmount($data['investmentAmount'])
                ->setReturnRate($data['returnRate']);

            $manager->persist($scenario);
        }

        // Set up admin
        $admin = new Admin();
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setUsername('admin');
        $admin->setPassword($this->passwordHasherFactory->getPasswordHasher(Admin::class)->hash('admin'));
        $manager->persist($admin);

        $manager->flush();
    }
}
