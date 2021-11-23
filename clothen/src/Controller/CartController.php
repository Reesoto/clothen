<?php

namespace App\Controller;

use App\Service\CartService;
use App\Service\ProductService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{

    /**
     * @param CartService $cartService
     * @param ProductService $productService
     * @return Response
     * @Route("/cart", name="cart")
     */
    public function index(CartService $cartService, ProductService $productService): Response
    {
        $cartContent = $cartService->getDetailsFromCart($cartService->get());

        return $this->render('cart/index.html.twig', [
            'cart'  => $cartContent
        ]);
    }

    /**
     * @param CartService $cartService
     * @param int $id
     * @return Response
     * @Route("/cart/add/{id}", name="add_to_cart")
     */
    public function add(CartService $cartService, int $id)
    {
        $cartService->add($id);
        return $this->redirectToRoute('cart');
    }

    /**
     * @param CartService $cartService
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/cart/remove", name="remove_from_cart")
     */
    public function remove(CartService $cart) {
        $cart->remove();

        return $this->redirectToRoute('products');
    }

    /**
     * @param CartService $cartService
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/cart/delete/{id}", name="delete_item_cart")
     */
    public function deleteItem(CartService $cartService, int $id) {
        $cartService->deleteItem($id);

        return $this->redirectToRoute('cart');
    }

    /**
     * @param CartService $cartService
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/cart/decrease/{id}", name="decrease_item_cart")
     */
    public function decreaseItem(CartService $cartService, int $id) {
        $cartService->decrease($id);

        return $this->redirectToRoute('cart');
    }
}
