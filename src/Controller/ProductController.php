<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Entity\Product;
use App\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/manager/product/create", name="createProduct")
     */
    public function createProduct(Request $request): Response
    {
        $product = new Product();
        //Tạo form dựa trên form type (xem App\Form\ProductType)
        $form = $this->createForm(ProductType::class, $product); 
        $form->handleRequest($request);
        
        
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            
            $image = $form->get('ImageUpload')->getData();
           
            if ($image != null) {
               
                $fileName = md5(uniqid());
                
                $fileExtension = $image->guessExtension();
               
                $imageName = $fileName . '.' . $fileExtension;
           
                try {
                    $image->move(
                        $this->getParameter('images'), $imageName
                    );
                } catch (FileException $e) {
                   
                    return new Response(
                        json_encode(['error' => $e->getMessage()]),
                        Response::HTTP_INTERNAL_SERVER_ERROR,
                        [
                            'content-type' => 'application/json'
                        ]
                    );
                }
                //Set tên file ảnh cho $product 
                $product->setImage($imageName);
            }
           
            $manager->persist($product);
          
            $manager->flush();
            
            $this->addFlash("Info", "Create product succeed!");
            //Điều hướng về trang quản lý (xem ViewManagerController)
            return $this->redirectToRoute("viewProductManager"); 
        }

        //Nếu truy cập -> render trang create
        return $this->render('product/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/manager/product/update/{id}", name="updateProduct")
     */
    public function updateProduct(Request $request, $id): Response
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
       
        if ($product == null) {
            $this->addFlash("Error", "Update failed!");
            return $this->redirectToRoute("viewProductManager");   
        }
       
        $form = $this->createForm(ProductType::class, $product); 
        $form->handleRequest($request);
        
        
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            
            $image = $form->get('ImageUpload')->getData();
            
            if ($image != null) {
                $fileName = md5(uniqid());
                $fileExtension = $image->guessExtension();
                $imageName = $fileName . '.' . $fileExtension;
                try {
                    $image->move(
                        $this->getParameter('images'), $imageName
                    );
                } catch (FileException $e) {
                    //Nếu lỗi thì return lỗi
                    return new Response(
                        json_encode(['error' => $e->getMessage()]),
                        Response::HTTP_INTERNAL_SERVER_ERROR,
                        [
                            'content-type' => 'application/json'
                        ]
                    );
                }
              
                $product->setImage($imageName);
            }
           
            $manager->persist($product);
           
            $manager->flush();
            
            $this->addFlash("Info", "Update product succeed!");
            
            return $this->redirectToRoute("viewProductManager"); 
        }

        
        return $this->render('product/update.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/manager/product/delete/{id}", name="deleteProduct")
     */
    public function deleteProduct(Request $request, $id): Response
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        //Nếu kết quả là null (không tìm thấy) thì điều hướng về trang quản lý
        if ($product == null) {
            $this->addFlash("Error", "Update failed!");
            return $this->redirectToRoute("viewProductManager");   
        }
        $manager = $this->getDoctrine()->getManager();
  
        $manager->remove($product);
        //Lưu thay đổi vào database
        $manager->flush();
        $this->addFlash("Info", "Delete product succeed !");
        //Điều hướng về trang quản lý
        return $this->redirectToRoute("viewProductManager"); 
    }
}
