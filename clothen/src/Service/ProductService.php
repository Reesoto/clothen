<?php

namespace App\Service;

use App\Entity\Product;
use App\Tools\Search;
use Doctrine\ORM\EntityManagerInterface;

class ProductService
{
    const YOUR_DOMAIN = 'http://localhost:8741';
    private $em;

    /**
     * @param $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param int $id
     * @return mixed|object|null
     */
    public function getDetailsById(int $id) {
       return $this->em->getRepository(Product::class)->find($id);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getDetailsByName(string $name) {
       return $this->em->getRepository(Product::class)->findOneByName($name);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function isExist(int $id) : bool {
        $result = $this->em->getRepository(Product::class)->find($id);

        return $result instanceof Product;
    }

    /**
     * @param Search $search
     * @return mixed
     */
    public function findWithFilters(Search $search) {
        $productsFiltered = $this->em->getRepository(Product::class)->findWithSearch($search);

        return $productsFiltered;
    }

    /**
     * @return array|object[]
     */
    public function findAll() {
        $products = $this->em->getRepository(Product::class)->findAll();

        return $products;
    }

    /**
     * @param array $orderDetails
     * @return array
     */
    public function getProductsForStripe(array $orderDetails) {
        $products_for_stripe = [];

        foreach($orderDetails as $product)
        {
            $product_details = $this->getDetailsByName($product->getProduct());
            $products_for_stripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $product->getProduct(),
                        'images' => [self::YOUR_DOMAIN . "/uploads/" . $product_details->getPicture()]
                    ],
                    'unit_amount' => $product->getPrice()*100,
                ],
                'quantity' => $product->getQuantity(),
            ];
        }

        return $products_for_stripe;
    }


}