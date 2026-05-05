<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        return $this->redirectToRoute('app_product_index');
    }

    #[Route('/products', name: 'app_product_index')]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findBy(
            ['available' => true],
            ['type' => 'ASC', 'name' => 'ASC']
        );

        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }
}
