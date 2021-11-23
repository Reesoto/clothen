<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\SearchType;
use App\Service\ProductService;
use App\Tools\Search;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    private $em;
    private $productService;

    /**
     * @param $em
     */
    public function __construct(EntityManagerInterface $em, ProductService $productService)
    {
        $this->em = $em;
        $this->productService = $productService;
    }


    /**
     * @param Request $request
     * @return Response
     * @Route("/our-products", name="products")
     */
    public function index(Request $request): Response
    {
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $products = $this->productService->findWithFilters($search);
        } else {
            $products = $this->productService->findAll();
        }

        return $this->render('product/index.html.twig',[
            "products"  =>  $products,
            "form"      => $form->createView()
        ]);
    }


    /**
     * @Route("/product/{slug}", name="product")
     */
    public function show(string $slug): Response
    {
        $product = $this->em->getRepository(Product::class)->findOneBySlug($slug);

        if(!$product) {
            return $this->redirectToRoute('products');
        }

        return $this->render('product/show.html.twig',[
            "product"  =>  $product
        ]);
    }


}
