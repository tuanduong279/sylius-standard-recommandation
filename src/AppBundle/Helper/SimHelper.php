<?php

namespace AppBundle\Helper;

// Correct use statements here ...
use Symfony\Component\DependencyInjection\ContainerInterface;
class SimHelper {

    private $container;
    private $data;
    
    public function setContainer(ContainerInterface $container) {
        $this->container = $container;
    }

    public function Similarity($item1, $item2) {
       $repository = $this->container->get('sylius.repository.product_review');
       $reviews = $repository->findAll();
       $rating1 =0;
       $rating2 =0;
       $up =0;
       if($item1==$item2)
       {
           return 0;
       }
       foreach($reviews as $review)
       {
           $data[$review->getAuthor()->getId()][$review->getReviewSubject()->getId()] = $review->getRating();
           $count[$review->getAuthor()->getId()] =count($data[$review->getAuthor()->getId()]);
                 
      }
      foreach($reviews as $review)
      {
          if($data[$review->getAuthor()->getId()][$review->getReviewSubject()->getId()]==0)
           {
               $count[$review->getAuthor()->getId()]--;
           }  
      }
       foreach($reviews as $review)
       {
           $average[$review->getAuthor()->getId()] = array_sum($data[$review->getAuthor()->getId()]) / $count[$review->getAuthor()->getId()];
           //up
           $up += ($data[$review->getAuthor()->getId()][$item1] - $average[$review->getAuthor()->getId()])
                   *($data[$review->getAuthor()->getId()][$item2] - $average[$review->getAuthor()->getId()]);
           //down
           $rating1 += $data[$review->getAuthor()->getId()][$item1] - $average[$review->getAuthor()->getId()];
           $rating2 += $data[$review->getAuthor()->getId()][$item2] - $average[$review->getAuthor()->getId()];
       }
       $result = ($up)/(sqrt($rating1*$rating1)*sqrt($rating2*$rating2));
       return $result;
    }
    public function Prediction($user, $item)
    {
       $repository = $this->container->get('sylius.repository.product_review');
       $reviews = $repository->findAll();
       $up=0;
       $down=0;
       foreach($reviews as $review)
       {
           $data[$review->getAuthor()->getId()][$review->getReviewSubject()->getId()] = $review->getRating();
       }
       foreach($reviews as $review)
       {
           //up
           if($user == $review->getAuthor()->getId())
           {
            $up += $this->Similarity($item, $review->getReviewSubject()->getId())
                    * $data[$user][$review->getReviewSubject()->getId()] ;
            //down
            $down += abs($this->Similarity($item, $review->getReviewSubject()->getId()));
           }
       }
       
       $result = $up / $down;
       return abs($result);
    }
    public function getProductPrediction($user, $current_item)
    {
       $repository = $this->container->get('sylius.repository.product_review');
       $reviews = $repository->findAll();
       $prediction = null;
       $products = null;
       
       $repos = $this->container->get('sylius.repository.product');
       $current_product = $repos->find($current_item);
       $current_IdCategory = $current_product->getArchetype()->getId();

       // FOR TEST ONLY USER 1,2,4,5,19,20
       // 

       foreach($reviews as $review)
       {
           $data[$review->getAuthor()->getId()][$review->getReviewSubject()->getId()] = $review->getRating();
       }
       foreach($reviews as $review)
       {
           if($user == $review->getAuthor()->getId() 
                 &&  $data[$review->getAuthor()->getId()][$review->getReviewSubject()->getId()] == 0
                 && $review->getReviewSubject()->getArchetype()->getId() == $current_IdCategory)
           {
                $prediction[] = array(
                    "product" => $review->getReviewSubject()->getId(),
                    "value"   => $this->Prediction($user, $review->getReviewSubject()->getId()),
                );
           }
       }
                  
       for($i =0; $i <count($prediction)-1; $i++)
       {
           for($j = $i+1; $j< count($prediction); $j++)
           {
               if($prediction[$i]["value"] < $prediction[$j]["value"])
               {
                   $temp = $prediction[$i];
                   $prediction[$i] = $prediction[$j];
                   $prediction[$j] = $temp;
               }
           }
       }
        for($i =0; $i <count($prediction); $i++)
       {
             $repos = $this->container->get('sylius.repository.product');
             $product = $repos->find($prediction[$i]["product"]);
             if($product->getArchetype()->getId() == $current_IdCategory)
             {
                $products[] = $product;
             }
             
       }
       return $products;
    }

    public function testUser($current_item)
    {
        $repository = $this->container->get('sylius.repository.product_review');
        $manager = $this->container->get('sylius.manager.product_review');
        $reviews = $repository->findBy(array('author' => 1 ,
                                                 'reviewSubject' => $current_item ));

        if($reviews == null)
        {
          $review =  $repository->createNew();
          $review ->setAuthor(1)
                  ->setReviewSubject($current_item)
                  ->setRating(0);
          $manager->persist($review);
          $manager->flush();
        }
    }
}