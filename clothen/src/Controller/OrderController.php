<?php

namespace App\Controller;

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
            $order = $orderService->saveOrder($form, $this->getUser(), $cart);

            return $this->render('order/add.html.twig', [
                'cart'              => $cartService->getDetailsFromCart($cart),
                'carrier'           => $carrier,
                'delivery_address'  => $deliveryAddress,
                'reference'         => $order->getReference()
            ]);
        }

        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/order/my-orders", name="my_orders")
     */
    public function orders(OrderService $orderService): Response
    {

        $orders = $orderService->getMyPaidOrders($this->getUser());

        return $this->render('order/orders.html.twig', [
            'orders'    => $orders
        ]);
    }

    /**
     * @Route("/order/{reference}", name="order_details")
     */
    public function detail(OrderService $orderService, $reference): Response
    {
        $order = $orderService->getOrderByReference($reference);

        if(!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('my_order');
        }

        return $this->render('order/order.html.twig', [
            'order'    => $order
        ]);
    }
}
