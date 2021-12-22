<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\StripeClient;

class OrderService
{
    private $em;
    private $addressService;
    private $cartService;

    /**
     * @param $em
     */
    public function __construct(EntityManagerInterface $em, AddressService $addressService, CartService $cartService)
    {
        $this->em = $em;
        $this->addressService = $addressService;
        $this->cartService = $cartService;
    }

    /**
     * @param $form
     * @param User $user
     * @return Order
     */
    public function saveOrder($form, User $user, $cart) {
        $date = new \DateTime();
        $order = new Order();
        $carriers = $form->get('carriers')->getData();
        $delivery = $form->get('addresses')->getData();

        $delivery = $this->addressService->getFormattedAddress($delivery);

        $reference = $date->format('dmY').'-'.uniqid();
        $order->setReference($reference);
        $order->setUser($user);
        $order->setCreatedAt($date);
        $order->setCarrierName($carriers->getName());
        $order->setCarrierPrice($carriers->getPrice());
        $order->setDelivery($delivery);
        $order->setIsPaid(0);

        $this->em->persist($order);

        foreach($this->cartService->getDetailsFromCart($cart) as $product) {
            $orderDetails = new OrderDetails();
            $orderDetails->setMyOrder($order);
            $orderDetails->setProduct($product['product']->getName());
            $orderDetails->setQuantity($product['quantity']);
            $orderDetails->setPrice($product['product']->getPrice()/100);
            $orderDetails->setTotal((($product['product']->getPrice())*($product['quantity']))/100);
            $this->em->persist($orderDetails);
        }

        $this->em->flush();

        return $order;
    }

    /**
     * @param $reference
     */
    public function getOrderByReference($reference) {
       $order =  $this->isReferenceExist($reference);

       if(!is_null($order)) {
           return $order;
       } else {
           return false;
       }
    }

    /**
     * @param $reference
     */
    public function getOrderDetailsByReference($reference) {
        $orders = $this->getOrderByReference($reference);

        if($orders !== false) {
            return $orders->getOrderDetails()->getValues();
        } else {
            return false;
        }
    }

    /**
     * @param string $checkoutSession
     * @return mixed
     */
    public function getOrderByStripeSessionId(string $checkoutSession) {
        return $this->em->getRepository(Order::class)->findOneByStripeSessionId($checkoutSession);
    }

    /**
     * @param Order $order
     * @param string $checkout_session
     */
    public function saveStripeCheckoutSession(Order $order, string $checkout_session) {
        $order->setStripeSessionId($checkout_session);
        $this->em->flush();
    }

    /**
     * @param Order $order
     * @param bool $value
     */
    public function orderIsPaid(Order $order, bool $value) {
        if($order->getIsPaid() !== $value) {
            $order->setIsPaid($value);
            $this->em->flush();
        }
}

    /**
     * @param $user
     * @return mixed
     */
    public function getMyPaidOrders($user) {
        return $this->em->getRepository(Order::class)->findSuccessOrder($user);
    }

    private function isReferenceExist($reference) {
        return $this->em->getRepository(Order::class)->findOneByReference($reference);
    }


}