<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Customer;
use App\Entity\Product;
use App\Repository\CartItemRepository;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart_index')]
    public function index(CartRepository $cartRepository): Response
    {
        /** @var Customer|null $customer */
        $customer = $this->getUser();

        if (!$customer) {
            return $this->redirectToRoute('app_login');
        }

        $cart = $cartRepository->findOneBy(['customer' => $customer]);

        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
        ]);
    }

    #[Route('/cart/add/{id}', name: 'app_cart_add')]
    public function add(
        Product $product,
        CartRepository $cartRepository,
        CartItemRepository $cartItemRepository,
        EntityManagerInterface $entityManager
    ): Response {
        /** @var Customer|null $customer */
        $customer = $this->getUser();

        if (!$customer) {
            return $this->redirectToRoute('app_login');
        }

        $cart = $cartRepository->findOneBy(['customer' => $customer]);

        if (!$cart) {
            $cart = new Cart();
            $cart->setCustomer($customer);
            $cart->setCreatedAt(new \DateTimeImmutable());
            $cart->setUpdatedAt(new \DateTimeImmutable());

            $entityManager->persist($cart);
        }

        $cartItem = $cartItemRepository->findOneBy([
            'cart' => $cart,
            'product' => $product,
        ]);

        if ($cartItem) {
            $cartItem->setQuantity($cartItem->getQuantity() + 1);
        } else {
            $cartItem = new CartItem();
            $cartItem->setCart($cart);
            $cartItem->setProduct($product);
            $cartItem->setQuantity(1);

            $entityManager->persist($cartItem);
        }

        $cart->setUpdatedAt(new \DateTimeImmutable());

        $entityManager->flush();

        return $this->redirectToRoute('app_cart_index');
    }

    #[Route('/cart/remove/{id}', name: 'app_cart_remove')]
    public function remove(
        CartItem $cartItem,
        EntityManagerInterface $entityManager
    ): Response {
        /** @var Customer|null $customer */
        $customer = $this->getUser();

        if (!$customer) {
            return $this->redirectToRoute('app_login');
        }

        if ($cartItem->getCart()->getCustomer() !== $customer) {
            throw $this->createAccessDeniedException();
        }

        $entityManager->remove($cartItem);
        $entityManager->flush();

        return $this->redirectToRoute('app_cart_index');
    }

    #[Route('/cart/clear', name: 'app_cart_clear')]
    public function clear(
        CartRepository $cartRepository,
        EntityManagerInterface $entityManager
    ): Response {
        /** @var Customer|null $customer */
        $customer = $this->getUser();

        if (!$customer) {
            return $this->redirectToRoute('app_login');
        }

        $cart = $cartRepository->findOneBy(['customer' => $customer]);

        if ($cart) {
            foreach ($cart->getCartItems() as $cartItem) {
                $entityManager->remove($cartItem);
            }

            $cart->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_cart_index');
    }
}