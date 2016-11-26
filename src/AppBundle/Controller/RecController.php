<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Product;
use AppBundle\Entity\ProductRespository;
use AppBundle\Entity\ProductTranslation;
use Doctrine\ORM\EntityRepository;
use Sylius\Component\User\Model\CustomerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Exception\AccessDeniedException;
class RecController  extends Controller
{
    /**
     * @Route("/hello/{name}", name="hello")
     */
    public function indexAction($name)
    {
        return new Response('<html><body>Hello '.$name.'!</body></html>');
    }
    public function listAction()
    {
      
        $repository = $this->container->get('sylius.repository.product');
        $products = $repository->findAll();
         return $this->render(
            'AppBundle:Frontend/Product:list_product.html.twig',
            array('products' => $products)
        );  
    }
    public function recommendAction($id)
    {
        $customer = $this->getCustomer();
        $simHelper = $this->get('app.helper.sim');
        $products = $simHelper->getProductPrediction($customer->getId(),$id);      
        return $this->render(
            'AppBundle:Frontend/Product:list_product.html.twig',
            array('products' => $products)    
                );
    }

    public function recThisPageAction($id)
    {

        $customer = $this->getCustomer();
        
        $simHelper = $this->get('app.helper.sim');
       
        $products = $simHelper->getProductPrediction($customer->getId(),$id);      
        return $this->render(
            'AppBundle:Frontend/Product:recommand_product.html.twig',
            array('products' => $products)    
                );
    }

    public function getCustomer()
    {
        $customer = $this->container->get('sylius.context.customer')->getCustomer();

        return $customer;
    }
}
