<?php

namespace App\Service;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;

class CarrierService
{
    private $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param Order $order
     * @return array
     */
    public function getCarrierDetailsByOrder(Order $order) {
            $carrier_for_stripe = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $order->getCarrierName(),
                        'images' => ["logo.png"]
                    ],
                    'unit_amount' => $order->getCarrierPrice(),
                ],
                'quantity' => 1,
            ];

        return $carrier_for_stripe;
    }

}