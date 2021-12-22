<?php

namespace App\Service;

use App\Entity\Carrier;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;

class CarrierService
{
    const YOUR_DOMAIN = 'http://localhost:8741';

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
        $carrierPicture = $this->getPictureByCarrierName($order->getCarrierName());

        $carrier_for_stripe = [
            'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                    'name' => $order->getCarrierName(),
                    'images' => [self::YOUR_DOMAIN . "/uploads/carriers/" . $carrierPicture]
                ],
                'unit_amount' => $order->getCarrierPrice(),
            ],
            'quantity' => 1,
        ];

        return $carrier_for_stripe;
    }

    /**
     * @param string $carrierName
     * @return string
     */
    private function getPictureByCarrierName(string $carrierName) : string
    {
        $carrier =  $this->em->getRepository(Carrier::class)->findOneByName($carrierName);
        return $carrier->getPicture();
    }

}