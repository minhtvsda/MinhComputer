<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ViewManagerController extends AbstractController
{
    /**
     * @Route("/manager/product", name="viewProductManager")
     */
    public function viewProductManager(): Response
    {
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll(); //Lấy danh sách sản phẩm
        return $this->render('view_manager/viewProduct.html.twig', [
            'products' => $products,
        ]);
    }

    /**
     * @Route("/manager/brand", name="viewBrandManager")
     */
    public function viewBrandManager(): Response
    {
        $brands = $this->getDoctrine()->getRepository(Brand::class)->findAll();
        return $this->render('view_manager/viewBrand.html.twig', [
            'brands' => $brands,
        ]);
    }
}
