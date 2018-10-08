#!/usr/bin/php
<?php
$tiempoInicial = microtime(TRUE);
include 'loademDBAL.php';



$dateInit = new \DateTime("now");
$dateInit->modify("-1 day");
$id = $dateInit->format("YmdHis");


echo "#" . $id . " - Init - Date: " . $dateInit->format("Y-m-d H:i:s") . "\n";
$query = $em->createQueryBuilder()
        ->select('u')
        ->from('Iem\Entity\EmailAuth', 'u')
        ->where("u.smtp = 1")
        ->setMaxResults(1);

$result = $query->getQuery()->getResult();


$hostname = '{imap.gmail.com:993/imap/ssl}INBOX';



foreach ($result as $item) {
    sleep(1);
    $username = $item->getEmail();
    $password = $item->getPassword();

    echo $username . "\n";

    $imap = new CdiTools\Mail\Imap("imap.gmail.com", $username, $password, "993", true);

    //Reviso Last Uid
    $EmailLastUid = $em->getRepository("\Iem\Entity\EmailLastUid")->findOneBy(array("email" => $username));
    if ($EmailLastUid) {
        $lastUid = $EmailLastUid->getUid();
        $search = 'SEARCH UID ' . $lastUid . ':*';
        echo $search;
        $emails = $imap->search($search);
        var_dump($emails);
    } else {
        //Si no tengo last UID busco por fecha
        $emails = $imap->search('SINCE "' . $dateInit->format("Y-m-d") . '"');
    }
    $i = 0;

    if ($emails) {
        foreach ($emails as $messageId) {
            //Bajo los detalles del correo
            $uid = $imap->getUid($messageId);

            $details = $imap->getDetails($messageId);


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
            } else {
                $reg = $em->getRepository("\Iem\Entity\EmailStore")->findOneBy(array("messageId" => $details['message_id']));
                if (!$reg) {
                    $new = true;
                    //Bajo el correo entero si es nuevo
                    $msj = $imap->getMessage($messageId);
                    $emailRebound = false;
                    $emailStore = new \Iem\Entity\EmailStore();
                } else {
                    $new = false;
                }
            }


            if (!$new) {
                echo "Existe:" . $details['message_id'] . "\n";
            } else {
                echo "Nuevo: " . $details['message_id'] . "\n";



                $subject = preg_replace("/^re:/i", "", $msj['subject']);
                $emailStore->setUid($uid);
                $emailStore->setMessageId($msj['message_id']);
                $emailStore->setSentTo($username);
                $emailStore->setType("Recepcion");
                $emailStore->setBody(utf8_encode($msj['body']));
                $emailStore->setSubject($subject);
                $emailStore->setDate(new \DateTime($msj['date_sent']));
                $emailStore->setSenderBy($msj['senderBy']);
                $emailStore->setSeen($msj['seen']);
                $emailStore->setEncoding($msj['encoding']);

                if (!$emailRebound) {
                    $emailConversation = $em->getRepository("\Iem\Entity\EmailConversation")->findOneBy(array(
                        "subject" => $emailStore->getSubject(),
                        "emailA" => $emailStore->getSentTo(),
                        "emailB" => $emailStore->getSenderBy()));

                    if (!$emailConversation) {
                        $state = $em->getRepository("\Iem\Entity\ConversationState")->find(1);

                        $emailConversation = new \Iem\Entity\EmailConversation();
                        $emailConversation->setSubject($emailStore->getSubject());
                        $emailConversation->setEmailA($emailStore->getSentTo());
                        $emailConversation->setEmailB($emailStore->getSenderBy());
                        $emailConversation->setState($state);
                        $em->persist($emailConversation);
                        $em->flush();
                    }

                    $emailStore->setConversation($emailConversation);
                }


                $i++;

                try {
                    $em->persist($emailStore);
                    $em->flush();
                } catch (Exception $e) {
                    echo "Error: " . $e . "\n";
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
            
            $em->persist($EmailLastUid);
            $em->flush();
        }
        $imap->disconnect();
    }
}



$dateFinish = new \DateTime("now");

$tiempoFinal = microtime(TRUE);
$tiempoTotal = round(($tiempoFinal - $tiempoInicial));
echo "#" . $id . " - Finish - Date: " . $dateFinish->format("Y-m-d H:i:s") . " - Time: " . $tiempoTotal . "\n";
?>
