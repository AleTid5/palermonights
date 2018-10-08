#!/usr/bin/php
<?php
$tiempoInicial = microtime(TRUE);
include 'loademDBAL.php';


$query = $em->createQueryBuilder()
        ->select('u')
        ->from('\Iem\Entity\EmailConversation', 'u');

$colCon = $query->getQuery()->getResult();

foreach($colCon as $conv){
       echo $conv->getId();
    $query = $em->createQueryBuilder()
        ->select('u')
        ->from('\Iem\Entity\EmailStore', 'u')
        ->where("u.conversation = ".$conv->getId())
        ->orderBy("u.date", "DESC")
         ->setMaxResults( 1 );
    
    $store = $query->getQuery()->getResult();
    
   if($store[0]->getType() == "Envio"){
       $conv->setIsResponded(true);
   }
   
   if($store[0]->getType() == "Recepcion"){
         $conv->setIsResponded(false);
   }
      $em->persist($conv);
                    $em->flush();
    
    
}
