#!/usr/bin/php
<?php
$tiempoInicial = microtime(TRUE);
include 'loademDBAL.php';


$query = $em->createQueryBuilder()
        ->select('u')
        ->from('Iem\Entity\Text', 'u');

$result = $query->getQuery()->getResult();

foreach ($result as $item) {
$item->setHtml(nl2br($item->getHtml()));
    $em->persist($item);
    $em->flush();
    
}
$dateFinish = new \DateTime("now");

$tiempoFinal = microtime(TRUE);
$tiempoTotal = round(($tiempoFinal - $tiempoInicial));

?>
