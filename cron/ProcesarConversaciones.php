#!/usr/bin/php
<?php
$tiempoInicial = microtime(TRUE);
include 'loademDBAL.php';


$query = $em->createQueryBuilder()
        ->select('u')
        ->from('\Iem\Entity\EmailConversation', 'u');

$result = $query->getQuery()->getResult();


foreach($result as $emailConversation){
    
     $query = $em->createQueryBuilder()
                ->select('u')
                ->from('Iem\Entity\SendingList', 'u')
                ->where("u.emailFrom = '".trim($emailConversation->getEmailA())."'")
                ->andWhere("u.subjectParsed = '".trim($emailConversation->getSubject())."'")
                ->orderBy("u.shippingDate", "DESC")
                ->setMaxResults(1);
        
  
        $SendingList = $query->getQuery()->getResult();
                      if($SendingList){  
                        $emailConversation->setScheduleSendingEmail($SendingList[0]->getScheduleSendingEmail()); 
                      echo $SendingList[0]->getScheduleSendingEmail()->getId();
                        
                      }
    
                        $em->persist($emailConversation);
                        $em->flush();
                      
                      
}
