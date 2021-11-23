<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class OrderService
{
    private $em;

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
    }


}