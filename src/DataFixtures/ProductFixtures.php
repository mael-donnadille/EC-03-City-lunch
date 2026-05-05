<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $products = [
            [
                'name' => 'Poulet',
                'description' => 'Plat avec du poulet',
                'price' => '9.90',
                'type' => 'plat',
                'image' => null,
                'available' => true,
            ],
            [
                'name' => 'Eau Plates',
                'description' => 'Eau plate du robinet (plat tres consistant)',
                'price' => '19.50',
                'type' => 'plat',
                'image' => null,
                'available' => true,
            ],
            [
                'name' => 'Salade ',
                'description' => 'Salade',
                'price' => '8.90',
                'type' => 'plat',
                'image' => null,
                'available' => true,
            ],
            [
                'name' => 'Tiramisu',
                'description' => 'Dessert',
                'price' => '4.50',
                'type' => 'dessert',
                'image' => null,
                'available' => true,
            ],
            [
                'name' => 'Macarons à la vanille',
                'description' => 'Macaron a la vanille (vraiment Très bon) ',
                'price' => '4.00',
                'type' => 'dessert',
                'image' => null,
                'available' => true,
            ],
        ];

        foreach ($products as $data) {
            $product = new Product();
            $product->setName($data['name']);
            $product->setDescription($data['description']);
            $product->setPrice($data['price']);
            $product->setType($data['type']);
            $product->setImage($data['image']);
            $product->setAvailable($data['available']);
            $product->setCreatedAt(new \DateTimeImmutable());

            $manager->persist($product);
        }

        $manager->flush();
    }
}