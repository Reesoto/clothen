<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    private $session;
    private $productService;

    /**
     * @param $session
     */
    public function __construct(SessionInterface $session, ProductService $productService)
    {
        $this->session = $session;
        $this->productService = $productService;
    }

    /**
     * @param int $id
     */
    public function add(int $id) {
        $cart = $this->session->get('cart', []);

        if ($this->productService->isExist($id)) {
            if(!empty($cart[$id])) {
                $cart[$id]++;
            } else {
                $cart[$id] = 1;
            }

            $this->session->set('cart', $cart);
        }
    }

    /**
     * @param int $id
     */
    public function decrease(int $id)
    {
        $cart = $this->session->get('cart', []);

        if($cart[$id] > 1) {
            $cart[$id]--;
        } else {
            unset($cart[$id]);
        }

        $this->session->set('cart', $cart);
    }

    /**
     * @return mixed
     */
    public function get() {
        return $this->session->get('cart');
    }

    /**
     * @return mixed
     */
    public function remove() {
        return $this->session->remove('cart');
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function deleteItem(int $id) {
        $cart = $this->session->get('cart', []);
        unset($cart[$id]);

        return $this->session->set('cart', $cart);
    }

    /**
     * @param array $cart
     * @return array
     */
    public function getDetailsFromCart(array $cart) {
        $cartContent = [];

        if(!empty($this->get())) {
            foreach ($cart as $id => $quantity) {
                $product = $this->productService->getDetails($id);
                $cartContent[] = [
                    'product' => $product,
                    'quantity' => $quantity
                ];
            }
        }

        return $cartContent;
    }
}