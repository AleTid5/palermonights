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
    ->where('u.enable = 1')
    ->setMaxResults(50);

$result = $query->getQuery()->getResult();


$hostname = '{imap.gmail.com:993/imap/ssl}INBOX';

$alarmaMails = array();

foreach ($result as $item) {
    sleep(1);
    $username = $item->getEmail();
    $password = $item->getPassword();

    echo $username . "\n";
    try {
        $imap = new CdiTools\Mail\Imap("imap.gmail.com", $username, $password, "993", true);

        //Reviso Last Uid
        $EmailLastUid = $em->getRepository("\Iem\Entity\EmailLastUid")->findOneBy(array("email" => $username));
        if ($EmailLastUid) {
            $lastUid = $EmailLastUid->getUid();
        }

        $emails = $imap->search('SINCE "' . $dateInit->format("Y-m-d") . '"');

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


                    $subject = preg_replace("/^re:|^responder:/i", "", $msj['subject']);
                    $emailStore->setUid($uid);
                    $emailStore->setMessageId($msj['message_id']);
                    $emailStore->setSentTo($username);
                    $emailStore->setType("Recepcion");
                    $emailStore->setBody(utf8_encode($msj['body']));
                    $emailStore->setSubject($subject);
                    try {
                        $dateSent = new \DateTime($msj['date_sent']);
                    } catch (Exception $ex) {
                        echo "Falla con Fecha: " . $msj['date_sent'] . " MsjId: " . $msj['message_id'] . " \n";
                        $dateSent = new \DateTime("now");
                    }
                    $emailStore->setDate($dateSent);
                    $emailStore->setSenderBy(preg_replace("/<|>/", "", $msj['senderBy']));
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
                            $emailConversation->setIsResponded(false);


                            //Busco si salio de una campaña


                            $query = $em->createQueryBuilder()
                                ->select('u')
                                ->from('Iem\Entity\SendingList', 'u')
                                ->where("u.emailFrom = '" . trim($emailConversation->getEmailA()) . "'")
                                ->andWhere("u.subjectParsed = '" . trim($emailConversation->getSubject()) . "'")
                                ->orderBy("u.shippingDate", "DESC")
                                ->setMaxResults(1);
                            try {
                                $SendingList = $query->getQuery()->getResult();
                            } catch (\Exception $e) {
                                echo "Error [0-SelectSendingList]: " . $e . "\n";
                            }
                            if ($SendingList) {
                                $emailConversation->setScheduleSendingEmail($SendingList[0]->getScheduleSendingEmail());
                                echo $SendingList[0]->getScheduleSendingEmail()->getId();
                            }
                            try {
                                $em->persist($emailConversation);
                                $em->flush();
                            } catch (\Exception $e) {
                                echo "Error [1-SendingList]: " . $e . "\n";
                            }
                        } else {
                            $emailConversation->setIsResponded(false);
                            try {
                                $em->persist($emailConversation);
                                $em->flush();
                            } catch (\Exception $e) {
                                echo "Error [2-ElseSendingList]: " . $e . "\n";
                            }
                        }

                        //cargo mail en alarma
                        $alarmaMails[] = $emailStore;

                        $emailStore->setConversation($emailConversation);
                    }


                    $i++;

                    try {
                        $em->persist($emailStore);
                        $em->flush();
                    } catch (\Exception $e) {
                        echo "Error [3-EmailStore]: " . $e . "\n";
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

    } catch (\Exception $e) {
        echo "Error:" . $e->getMessage();
        $item->setEnable(false);
        $em->persist($item);
        $em->flush();

        $emailFrom = $em->getRepository("\Iem\Entity\EmailAuth")->find(1);
        $smtp = $emailFrom->getSmtp();
        $mailsBody = "Sr. Charly, se notifica cuenta INHABILITADA: ". $item->getEmail();
        $mailSubject = "Cuenta Inhabilitada - ".$item->getEmail() . date("Y-m-d H:i:s");
        $MailSender = new \Iem\Service\MailOne();
        $MailSender->smtpOptions($smtp->getSmtp(), $smtp->getSmtpPort(), "login", $emailFrom->getEmail(), $emailFrom->getPassword(), $smtp->getSmtpSecure());
        $result = $MailSender->sendMail($mailsBody, $mailsBody, $mailSubject, $emailFrom->getEmail(), $emailFrom->getName(), "charly_ruez@hotmail.com", "Charly");
        $result = $MailSender->sendMail($mailsBody, $mailsBody, $mailSubject, $emailFrom->getEmail(), $emailFrom->getName(), "cristian.cdi@gmail.com", "Cristian");
    }

}


//verifico mails para enviar alarma

if (count($alarmaMails) > 0) {
    $i = 0;
    $mailsBody = "Sr. Charly, usted a recibido nuevos mails. \n\n\n";
    foreach ($alarmaMails as $mail) {

        $mailsBody .= $i . ". Email: " . $mail->getSenderBy() . " | Subject: " . $mail->getSubject() . "\n\n";

        $i++;
    }

    $emailFrom = $em->getRepository("\Iem\Entity\EmailAuth")->find(1);
    $smtp = $emailFrom->getSmtp();


    $MailSender = new \Iem\Service\MailOne();
    $MailSender->smtpOptions($smtp->getSmtp(), $smtp->getSmtpPort(), "login", $emailFrom->getEmail(), $emailFrom->getPassword(), $smtp->getSmtpSecure());
    $result = $MailSender->sendMail($mailsBody, $mailsBody, "Nuevos mails - " . date("Y-m-d H:i:s"), $emailFrom->getEmail(), $emailFrom->getName(), "charly_ruez@hotmail.com", "Charly");
}


$dateFinish = new \DateTime("now");

$tiempoFinal = microtime(TRUE);
$tiempoTotal = round(($tiempoFinal - $tiempoInicial));
echo "#" . $id . " - Finish - Date: " . $dateFinish->format("Y-m-d H:i:s") . " - Time: " . $tiempoTotal . "\n";
?>
