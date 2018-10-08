#!/usr/bin/php
<?php
$tiempoInicial = microtime(TRUE);
include 'loademDBAL.php';



$dateInit = new \DateTime("2014-08-01");

//$dateTo = clone $dateInit;
//$dateTo->modify("+1 year");
$dateTo = new \DateTime("2015-05-18");
//$dateInit->modify("+1 month");
$id = $dateInit->format("YmdHis");


echo "#" . $id . " - Init - Date: " . $dateInit->format("Y-m-d H:i:s") . "\n";
$query = $em->createQueryBuilder()
        ->select('u')
        ->from('Iem\Entity\EmailAuth', 'u')
        ->setMaxResults(50);

$result = $query->getQuery()->getResult();


$hostname = '{imap.gmail.com:993/imap/ssl}[Gmail]/All Mail';

$alarmaMails = array();

foreach ($result as $item) {
    sleep(1);
    $username = $item->getEmail();
    $password = $item->getPassword();

    echo $username . "\n";

    $imap = new CdiTools\Mail\Imap("imap.gmail.com", $username, $password, "993", true,"[Gmail]/Todos");

    //Reviso Last Uid
    $EmailLastUid = $em->getRepository("\Iem\Entity\EmailLastUid")->findOneBy(array("email" => $username));
    if ($EmailLastUid) {
        $lastUid = $EmailLastUid->getUid();
    }
    $search = 'SINCE "' . $dateInit->format("Y-m-d") . '" BEFORE "'.$dateTo->format("Y-m-d").'" SUBJECT "Delivery Status Notification (Failure)"';
//$search = 'SINCE "' . $dateInit->format("Y-m-d") . '" BEFORE "'.$dateTo->format("Y-m-d").'"';
        
// $search = 'ALL BODY "automatically"';
   
// $search = 'SINCE "' . $dateInit->format("Y-m-d") . '"';
    
    echo $search."\n";
    $emails = $imap->search($search);
   // var_dump($emails);
    $i = 0;
    echo "Emails Found:".count($emails)."\n";
    if ($emails) {


        foreach ($emails as $messageId) {
            //Bajo los detalles del correo
            $uid = $imap->getUid($messageId);

            $details = $imap->getDetails($messageId);
            $i++;
           // echo $i.") Subject: ".$details['subject']."\n";
            if (preg_match("/Delivery\sStatus\sNotification/i", $details['subject'])) {

                $reg = $em->getRepository("\Iem\Entity\EmailRebound")->findOneBy(array("messageId" => $details['message_id']));
                if (!$reg) {
                    $new = true;
                    //Bajo el correo entero si es nuevo
                    $msj = $imap->getMessage($messageId);
                    $emailStore = new \Iem\Entity\EmailRebound();
                    preg_match("/[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})/", $msj['body'], $match);
                    $emailStore->setEmailRebound($match[0]);
                    $emailRebound = true;
                } else {
                    $new = false;
                }



                if (!$new) {
                    echo "Existe:" . $details['message_id'] ." ". $emailStore->getEmailRebound(). "\n";
                } else {
                    echo "Nuevo: " . $details['message_id'] ." ". $emailStore->getEmailRebound()."\n";



                    $subject = preg_replace("/^re:|^responder:/i", "", $msj['subject']);
                    $emailStore->setUid($uid);
                    $emailStore->setMessageId($msj['message_id']);
                    $emailStore->setSentTo($username);
                    $emailStore->setType("Recepcion");
                    $emailStore->setBody(utf8_encode($msj['body']));
                    $emailStore->setSubject($subject);
                    $emailStore->setDate(new \DateTime($msj['date_sent']));
                    $emailStore->setSenderBy(preg_replace("/<|>/", "", $msj['senderBy']));
                    $emailStore->setSeen($msj['seen']);
                    $emailStore->setEncoding($msj['encoding']);



                    

                    try {
                        $em->persist($emailStore);
                        $em->flush();
                    } catch (\Exception $e) {
                        echo "Error [3-EmailStore]: " . $e . "\n";
                    }
                }
            }
        }

        //Actualizo Ultimo UID
        if ($uid > $lastUid) {

            if ($EmailLastUid) {
                $EmailLastUid->setUid($uid);
            } else {
                $EmailLastUid = new \Iem\Entity\EmailLastUid();
                $EmailLastUid->setEmail($username);
                $EmailLastUid->setUid($uid);
            }
            try {
                $em->persist($EmailLastUid);
                $em->flush();
            } catch (\Exception $e) {
                echo "Error [4-LastUid]: " . $e . "\n";
            }
        }
        $imap->disconnect();
    }
}


//verifico mails para enviar alarma



$dateFinish = new \DateTime("now");

$tiempoFinal = microtime(TRUE);
$tiempoTotal = round(($tiempoFinal - $tiempoInicial));
echo "#" . $id . " - Finish - Date: " . $dateFinish->format("Y-m-d H:i:s") . " - Time: " . $tiempoTotal . "\n";
?>
