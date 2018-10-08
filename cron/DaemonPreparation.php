#!/usr/bin/php
<?php
$tiempoInicial = microtime(TRUE);
include 'loademDBAL.php';

echo "Init DaemonPreparation\n";
$query = $em->createQueryBuilder()
                ->select('u')
                ->from('Iem\Entity\ScheduleSendingEmail', 'u')
                ->where("u.state = 'programmed'")->setMaxResults(1);

$result = $em->createQuery($query)->getResult();

if ($result) {
    foreach ($result as $item) {

        echo "Preparing:" . $item->getName() . "\n";
        $MailSender = new \Iem\Service\MailSender($em, $item);
        $MailSender->generateSendingList();
        $MailSender->prepareSchedule();
        unset($MailSender);
    }
}
echo "Finish DaemonPreparation\n";
$tiempoFinal = microtime(TRUE);
$tiempoTotal = round(($tiempoFinal - $tiempoInicial));
echo $tiempoTotal . "\n";
?>
