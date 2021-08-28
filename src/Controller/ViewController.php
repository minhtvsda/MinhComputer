<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ViewController extends AbstractController
{
    /**
     * @Route("/all", name="allProduct")
     */
    public function allProduct(): Response
    {
        //Mặc định, xem tất cả sản phẩm 
        $brands = $this->getDoctrine()->getRepository(Brand::class)->findAll(); 
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll(); 

        return $this->render('view/index.html.twig', [
            'brands' => $brands,
            'products' => $products,
        ]);
    }

    /**
     * @Route("/brand/{id}", name="filterByBrand")
     */
    public function filterByBrand($id): Response
    {
        //Xem sản phẩm theo hãng (brand)
        $brands = $this->getDoctrine()->getRepository(Brand::class)->findAll(); 
        $brand = $this->getDoctrine()->getRepository(Brand::class)->find($id); //Tìm hãng theo id
        
        if ($brand == null) return $this->redirectToRoute("allProduct");

        $products = $brand->getProducts(); //lấy danh sách sản phẩm theo hãng

        return $this->render('view/index.html.twig', [
            'brands' => $brands,
            'products' => $products,
        ]);
    }

    /**
     * @Route("/sort/name", name="sortByName")
     */
    public function sortByName(): Response
    {
       
        $brands = $this->getDoctrine()->getRepository(Brand::class)->findAll(); 

        $products = $this->getDoctrine()->getRepository(Product::class)->findAllOrderByName(); 

        return $this->render('view/index.html.twig', [
            'brands' => $brands,
            'products' => $products,
        ]);
    }

    /**
     * @Route("/sort/price-asc", name="sortByPriceASC")
     */
    public function sortByPriceASC(): Response
    {
        
        $brands = $this->getDoctrine()->getRepository(Brand::class)->findAll(); //Lấy danh sách hãng laptop, mục đích: hiển thị trên thanh điều hướng 
        $products = $this->getDoctrine()->getRepository(Product::class)->findAllOrderByPriceAsc(); 

        return $this->render('view/index.html.twig', [
            'brands' => $brands,
            'products' => $products,
        ]);
    }

    /**
     * @Route("/sort/price-desc", name="sortByPriceDESC")
     */
    public function sortByPriceDESC(): Response
    {
        //Sắp xếp theo giá cao đến thấp
        $brands = $this->getDoctrine()->getRepository(Brand::class)->findAll(); 

        $products = $this->getDoctrine()->getRepository(Product::class)->findAllOrderByPriceDesc();
        

        return $this->render('view/index.html.twig', [
            'brands' => $brands,
            'products' => $products,
        ]);
    }
}
