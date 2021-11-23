<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Form\OrderType;
use App\Service\AddressService;
use App\Service\CartService;
use App\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
     * @param Request $request
     * @param CartService $cartService
     * @return Response
     * @Route("/order", name="order")
     */
    public function index(Request $request, CartService $cartService): Response
    {
        if(!$this->getUser()->getAddresses()->getValues()) {
            return $this->redirectToRoute('account_address_add');
        }

        $form = $this->createForm(OrderType::class, null, [
            'user'  => $this->getUser()
        ]);

        return $this->render('order/index.html.twig', [
            "form"  => $form->createView(),
            "cart"  => $cartService->getDetailsFromCart($cartService->get())
        ]);
    }

    /**
     * @param Request $request
     * @param CartService $cartService
     * @return Response
     * @Route("/order/summary", name="order_summary", methods={"POST"})
     */
    public function add(Request $request,
                        CartService $cartService,
                        OrderService $orderService,
                        AddressService $addressService
    ): Response
    {
        $form = $this->createForm(OrderType::class, null, [
            'user'  => $this->getUser()
        ]);

        $form->handleRequest($request);
        $cart = $cartService->get();
        $delivery = $form->get('addresses')->getData();
        $deliveryAddress = $addressService->getFormattedAddress($delivery);

        if($form->isSubmitted() && $form->isValid()) {
            $carrier = $form->get('carriers')->getData();
            $orderService->saveOrder($form, $this->getUser(), $cart);

            return $this->render('order/add.html.twig', [
                'cart'      => $cartService->getDetailsFromCart($cart),
                'carrier'   => $carrier,
                'delivery_address'  => $deliveryAddress
            ]);
        }

        return $this->redirectToRoute('cart');


    }
}
