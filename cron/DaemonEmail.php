#!/usr/bin/php
<?php
$tiempoInicial = microtime(TRUE);
include 'loademDBAL.php';



$dateInit = new \DateTime("now");
$id = $dateInit->format("YmdHis");


echo "#".$id." - Init - Date: " . $dateInit->format("Y-m-d H:i:s") . "\n";
$query = $em->createQueryBuilder()
        ->select('u')
        ->from('Iem\Entity\ScheduleSendingEmail', 'u')
        ->andWhere("u.datetimeSchedule < :dateNow")
        ->andWhere("u.state = 'pending' or u.state = 'restart'")
        ->setParameter("dateNow", new \DateTime("now"))
        ->setMaxResults(1);

$result = $query->getQuery()->getResult();

foreach ($result as $item) {
    $MailSender = new \Iem\Service\MailSender($em, $item);
    $MailSender->sendSchedule();
}
$dateFinish = new \DateTime("now");

$tiempoFinal = microtime(TRUE);
$tiempoTotal = round(($tiempoFinal - $tiempoInicial));
echo "#".$id." - Finish - Date: " . $dateFinish->format("Y-m-d H:i:s") ." - Time: ".$tiempoTotal. "\n";

?>
