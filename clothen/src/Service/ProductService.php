<?php

namespace App\Service;

use App\Entity\Product;
use App\Tools\Search;
use Doctrine\ORM\EntityManagerInterface;

class ProductService
{
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
    public function getDetails(int $id) {
       return $this->em->getRepository(Product::class)->find($id);
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


}