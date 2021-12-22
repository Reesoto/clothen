<?php

namespace App\Controller;

use App\Service\CarrierService;
use App\Service\CartService;
use App\Service\OrderService;
use App\Service\ProductService;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeController extends AbstractController
{
    const YOUR_DOMAIN = 'http://localhost:8741';
    /**
     * @Route("/order/create-session/{reference}", name="stripe_create_session")
     */
    public function index(OrderService $orderService,
                          ProductService $productService,
                          CarrierService $carrierService,
                          $reference): Response
    {
        $stripe_sk = $this->getParameter('app.stripe_sk');
        Stripe::setApiKey($stripe_sk);

        $orderDetails = $orderService->getOrderDetailsByReference($reference);

        // if no reference has been found with this 'reference' value, redirect to 'cart' Route
        if(!$orderDetails) {
            return $this->redirectToRoute('cart');
        } else {
            $order = $orderDetails[0]->getMyOrder();
        }

        $stripeProducts = $productService->getProductsForStripe($orderDetails);
        $stripeCarrier = $carrierService->getCarrierDetailsByOrder($order);

        array_push($stripeProducts, $stripeCarrier);


        $checkout_session = Session::create([
            'customer_email' => $this->getUser()->getEmail(),
            'line_items' => [
                $stripeProducts
            ],
            'payment_method_types' => [
                'card',
            ],
            'mode'          => 'payment',
            'success_url'   => self::YOUR_DOMAIN.'/order/success/{CHECKOUT_SESSION_ID}',
            'cancel_url'    => self::YOUR_DOMAIN.'/order/cancel/{CHECKOUT_SESSION_ID}',
        ]);

        $orderService->saveStripeCheckoutSession($order, $checkout_session->id);

        return $this->redirect($checkout_session->url);
        //$response = new JsonResponse(['id' => $checkout_session->id]);
        //return $response;
    }

    /**
     * @Route("/order/success/{checkoutSession}", name="stripe_payment_success")
     */
    public function success($checkoutSession, OrderService $orderService, CartService $cartService): Response
    {
        $order = $orderService->getOrderByStripeSessionId($checkoutSession);

        if(!$order || $this->getUser() !== $order->getUser()) {
            return $this->redirectToRoute('home');
        }

        $orderService->orderIsPaid($order, true);
        $cartService->clear();


        return $this->render('order/success.html.twig', [
            'order' => $order
        ]);
    }

    /**
     * @Route("/order/cancel/{checkoutSession}", name="stripe_payment_cancel")
     */
    public function cancel($checkoutSession, OrderService $orderService): Response
    {
        $order = $orderService->getOrderByStripeSessionId($checkoutSession);

        if(!$order || $this->getUser() !== $order->getUser()) {
            return $this->redirectToRoute('home');
        }

        return $this->render('order/cancel.html.twig', [
            'order' => $order
        ]);
    }
}
