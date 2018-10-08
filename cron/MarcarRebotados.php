#!/usr/bin/php
<?php
$tiempoInicial = microtime(TRUE);
include 'loademDBAL.php';


$query = $em->createQueryBuilder()
        ->select('u')
        ->from('Iem\Entity\EmailRebound', 'u')
        ->where('u.subject LIKE :subject ')
        ->setParameter("subject", "%Failure%");
      //   ->setMaxResults(50);

$result = $query->getQuery()->iterate();

if($result){
foreach($result as $emailRebound){
    
    $email = $emailRebound[0]->getEmailRebound();
    
     $contact = $em->getRepository('Iem\Entity\Contact')->findOneBy(array("facebookEmail" => $email));
     if($contact){
         echo "OK: Se encontro Contacto: ".$email."\n";
    $contact->setRebound(true);
     $em->persist($contact);
     $em->flush();
     }else{
            echo "ALERTA: no se encontro Contacto: ".$email."\n";
     }
}
}else{
    echo "NO RESULT...\n";
}



$dateFinish = new \DateTime("now");

$tiempoFinal = microtime(TRUE);
$tiempoTotal = round(($tiempoFinal - $tiempoInicial));
echo "#" . $id . " - Finish - Date: " . $dateFinish->format("Y-m-d H:i:s") . " - Time: " . $tiempoTotal . "\n";
?>
