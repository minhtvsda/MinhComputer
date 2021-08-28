<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Entity\Product;
use App\Form\BrandType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BrandController extends AbstractController
{
    /**
     * @Route("/manager/brand/create", name="createBrand")
     */
    public function createBrand(Request $request): Response
    {
        $brand = new Brand();
        
        $form = $this->createForm(BrandType::class, $brand); 
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
                
                $brand->setLogo($imageName);
            }
            
            $manager->persist($brand);
            
            $manager->flush();
            
            $this->addFlash("Info", "Create brand succeed!");
            
            return $this->redirectToRoute("viewBrandManager"); 
        }

        
        return $this->render('brand/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/manager/brand/update/{id}", name="updateBrand")
     */
    public function updateBrand(Request $request, $id): Response
    {
        $brand = $this->getDoctrine()->getRepository(Brand::class)->find($id);
        
        if ($brand == null) {
            $this->addFlash("Error", "Update failed!");
            return $this->redirectToRoute("viewBrandManager");   
        }
        
        $form = $this->createForm(BrandType::class, $brand); 
        $form->handleRequest($request);
        
        
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            //Kiểm tra xem user 
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
                
                $brand->setLogo($imageName);
            }
            
            $manager->persist($brand);
            
            $manager->flush();
            
            $this->addFlash("Info", "Create brand succeed!");
            
            return $this->redirectToRoute("viewBrandManager"); 
        }

        
        return $this->render('brand/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/manager/brand/delete/{id}", name="deleteBrand")
     */
    public function deleteBrand(Request $request, $id): Response
    {
        //Tìm theo id
        $brand = $this->getDoctrine()->getRepository(Brand::class)->find($id);
        
        
        if ($brand == null) {
            $this->addFlash("Error", "Update failed!");
            return $this->redirectToRoute("viewBrandManager");   
        }
        $manager = $this->getDoctrine()->getManager();
        //Xóa 
        $manager->remove($brand);
        
        $manager->flush();
        $this->addFlash("Info", "Delete brand succeed !");
        
        return $this->redirectToRoute("viewBrandManager"); 
    }
}
