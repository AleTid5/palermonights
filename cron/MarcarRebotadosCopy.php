#!/usr/bin/php
<?php
$tiempoInicial = microtime(TRUE);
include 'loademDBAL.php';


$query = $em->createQueryBuilder()
        ->select('u')
        ->from('Iem\Entity\EmailReboundCopy', 'u');
//   ->setMaxResults(50);

$result = $query->getQuery()->iterate();

if ($result) {
    $i = 0;
    $u=0;
    foreach ($result as $emailRebound) {
$u++;
        $email = $emailRebound[0]->getEmailRebound();

        $contact = $em->getRepository('Iem\Entity\Contact')->findOneBy(array("facebookEmail" => $email));
        
        if ($contact) {
            if ($contact->getRebound() == false || $contact->getRebound() == null) {
                echo "OK: Se encontro Contacto: " . $email . "\n";
                $contact->setRebound(true);
                $em->persist($contact);
                $i++;
                if($i == 30){
                    $i=0;
                $em->flush();
                }
            }
        } else {
            echo "ALERTA: no se encontro Contacto: " . $email . "\n";
        }
        echo $u."\n";
    }
    
       $em->flush();
} else {
    echo "NO RESULT...\n";
}



$dateFinish = new \DateTime("now");

$tiempoFinal = microtime(TRUE);
$tiempoTotal = round(($tiempoFinal - $tiempoInicial));
echo "#" . $id . " - Finish - Date: " . $dateFinish->format("Y-m-d H:i:s") . " - Time: " . $tiempoTotal . "\n";
?>
