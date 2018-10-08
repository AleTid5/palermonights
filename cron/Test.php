#!/usr/bin/php
<?php
$tiempoInicial = microtime(TRUE);
include 'loademDBAL.php';

$item = new \Iem\Entity\ScheduleSendingEmail();

$MailSender = new \Iem\Service\MailSender($em, $item);
$MailSender->smtpOptions( "smtp.gmail.com", "465", "login", "banights00@gmail.com", "airioria25", "ssl");
$MailSender->sendMail("Prueba de envio", "Prueba de envio", "banights00@gmail.com", "Charly Ruez", "charly.ruez@facebook.com", "Cristian");



echo "Finish DaemonEmail\n";
$tiempoFinal = microtime(TRUE);
$tiempoTotal = round(($tiempoFinal - $tiempoInicial));
echo $tiempoTotal . "\n";
?>
