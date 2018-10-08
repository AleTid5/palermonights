<?php

namespace Iem\Service;

/**
 * Description of MailSender
 *
 * @author cincarnato
 */
class MailSender {

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var \Iem\Entity\ScheduleSendingEmail
     */
    protected $scheduleSendingEmail;

    /**
     * @var \Zend\Mail\Message
     */
    protected $mail;

    /**
     * @var \Zend\Mail\Transport\Smtp
     */
    protected $smtpTransport = null;

    /**
     * @var \Zend\Mail\Transport\SmtpOptions
     */
    protected $smtpOptions = null;

    /**
     * @var array
     */
    protected $combination = null;

    /**
     * @var array
     */
    protected $sendingListCollection;

    /**
     * @var array
     */
    protected $emailAuthCollection;

    /**
     * @var array
     */
    protected $subjectTemplateCollection;

    /**
     * @var array
     */
    protected $textTemplateCollection;

    public function getEm() {
        return $this->em;
    }

    public function setEm(\Doctrine\ORM\EntityManager $em) {
        $this->em = $em;
    }

    public function setEntityManager(EntityManager $em) {
        $this->em = $em;
    }

    public function getEntityManager() {
        if (null === $this->em) {
            $this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }
        return $this->em;
    }

    public function getScheduleSendingEmail() {
        return $this->scheduleSendingEmail;
    }

    public function setScheduleSendingEmail(\Iem\Entity\ScheduleSendingEmail $scheduleSendingEmail) {
        $this->scheduleSendingEmail = $scheduleSendingEmail;
    }

    function __construct(\Doctrine\ORM\EntityManager $em, \Iem\Entity\ScheduleSendingEmail $scheduleSendingEmail) {
        $this->em = $em;
        $this->scheduleSendingEmail = $scheduleSendingEmail;
    }

    public function smtpOptions($host, $port, $connection_class, $user, $pass, $secure) {
        $this->smtpOptions = new \Zend\Mail\Transport\SmtpOptions(array(
            'host' => $host,
            'port' => $port,
            'connection_class' => $connection_class,
            'connection_config' => array(
                'username' => $user,
                'password' => $pass,
                'ssl' => $secure,
        )));
        $this->smtpTransport = new \Zend\Mail\Transport\Smtp();
        $this->smtpTransport->setOptions($this->smtpOptions);
    }

    public function sendMail($plainText,$htmlText, $subject, $emailFrom, $nameFrom, $emailTo, $nameTo) {

        if (isset($this->smtpTransport) && isset($this->smtpOptions)) {
            try {

                $text = new \Zend\Mime\Part($plainText);
                $text->type = "text/plain";
                $text->charset = 'utf-8';
                $html = new \Zend\Mime\Part($htmlText);
                $html->type = "text/html";
                $html->charset = 'utf-8';

                $body = new \Zend\Mime\Message();
                $body->setParts(array($text, $html));

                $this->mail = new \Zend\Mail\Message();
                $this->mail->setSubject($subject);
                $this->mail->setFrom($emailFrom, $nameFrom);
                $this->mail->addTo($emailTo, $nameTo);
                $this->mail->setEncoding("UTF-8");
                $this->mail->setBody($body);
                $this->mail->getHeaders()->get('content-type')->setType('multipart/alternative');

                $this->smtpTransport->send($this->mail);
                return array(true, "ok");
            } catch (\Zend\Mail\Protocol\Exception\RuntimeException $exc) {
                echo "Exception1: " . $exc . "\n";
                echo $exc->getTraceAsString();
                return array(false, $exc);
            } catch (\RuntimeException $e) {
                echo "Exception2: " . $e . "\n";
                echo $e->getTraceAsString();
                return array(false, $e);
            } catch (\Exception $exc) {
                echo "Exception3: " . $exc . "\n";
                echo $exc->getTraceAsString();
                return array(false, $exc);
            }
        } else {
            throw new \Exception("No Smtp transport/options defined");
        }
    }

    public function sendSchedule() {

        $this->scheduleSendingEmail->setState("processing");
        $this->getEntityManager()->persist($this->scheduleSendingEmail);
        $this->getEntityManager()->flush();

        $EmailsProcessed = 0;
        $EmailsPending = $this->scheduleSendingEmail->getEmailsTotal();
        $EmailsSent = 0;
        $EmailsFailed = 0;
        foreach ($this->scheduleSendingEmail->getSendingList() as $sendingList) {

            $pauseControl = $this->getEntityManager()->getRepository('Iem\Entity\ScheduleEmailControl')->findOneBy(array("scheduleSendingEmail" => $this->scheduleSendingEmail->getId()));

            if ($pauseControl) {
                if ($pauseControl->getState() == "pause") {
                    $this->scheduleSendingEmail->setState("pause");
                    $this->getEntityManager()->persist($this->scheduleSendingEmail);
                    $this->getEntityManager()->flush();
                    return null;
                }
            }


            if ($sendingList->getStatus() != "sent" && $sendingList->getStatus() != "failed" && $sendingList->getStatus() != "invalid") {
                $this->smtpOptions($this->scheduleSendingEmail->getSmtp()->getSmtp(), $this->scheduleSendingEmail->getSmtp()->getSmtpPort(), "login", $sendingList->getEmailAuth()->getEmail(), $sendingList->getEmailAuth()->getPassword(), $this->scheduleSendingEmail->getSmtp()->getSmtpSecure());

                if ($sendingList->getEmail()) {
                    $email = $sendingList->getEmail();
                } else if ($sendingList->getFacebookEmail()) {
                    $email = $sendingList->getFacebookEmail();
                }
                $email = $sendingList->getFacebookEmail();


                //Valido el Mail

                $validator = new \Zend\Validator\EmailAddress();

                if ($validator->isValid($email)) {
//                    echo "Init: " . $email . "\n";
                    if($this->scheduleSendingEmail->getEmailLayout()){
                    $html = $this->scheduleSendingEmail->getEmailLayout()->getStartHtml().$sendingList->getHtmlParsed().$this->scheduleSendingEmail->getEmailLayout()->getEndHtml();
                    $text = $this->scheduleSendingEmail->getEmailLayout()->getStartTxt().$sendingList->getTextParsed().$this->scheduleSendingEmail->getEmailLayout()->getEndTxt();
                    }else{
                       $html = $sendingList->getHtmlParsed();
                         $text = $sendingList->getTextParsed();
                    }
                    $result = $this->sendMail($text,$html, $sendingList->getSubjectParsed(), $sendingList->getEmailFrom(), $sendingList->getDisplaynameFrom(), $email, $sendingList->getName() . " " . $sendingList->getLastname());
                    if ($result[0]) {
                        $EmailsSent++;
                        $sendingList->setShippingDate(new \DateTime("now"));
                        $sendingList->setStatus("sent");
                        $this->getEntityManager()->persist($sendingList);
                        $this->getEntityManager()->flush();
//                        echo "Email sent: " . $email . "\n";
//                        echo "TimeInterval:" . $sendingList->getTimeInterval() . "\n";
                        sleep($sendingList->getTimeInterval());
                    } else {
                        $EmailsFailed++;
                        $sendingList->setShippingDate(new \DateTime("now"));
                        $sendingList->setStatus("failed");
                        $sendingList->setSendError($result[1]);
                        $this->getEntityManager()->persist($sendingList);
                        $this->getEntityManager()->flush();
//                        echo "Email fail: " . $email . "\n";
//                        echo "TimeInterval:" . $sendingList->getTimeInterval() . "\n";
                        sleep($sendingList->getTimeInterval());
                    }
                } else {

                    foreach ($validator->getMessages() as $messageId => $message) {
                        $ErrorInvalid = "Validation failure '$messageId': $message\n";
                    }

                    $EmailsFailed++;
                    $sendingList->setShippingDate(new \DateTime("now"));
                    $sendingList->setStatus("invalid");
                    $sendingList->setSendError($ErrorInvalid);
                    $this->getEntityManager()->persist($sendingList);
                    $this->getEntityManager()->flush();
//                    echo "Email Invalid: " . $email . "\n";
//                    echo "TimeInterval:" . $sendingList->getTimeInterval() . "\n";
                    sleep($sendingList->getTimeInterval());
                }




                $EmailsProcessed++;
                $EmailsPending--;
                $this->scheduleSendingEmail->setEmailsProcessed($EmailsProcessed);
                $this->scheduleSendingEmail->setEmailsPending($EmailsPending);
                $this->scheduleSendingEmail->setEmailsSent($EmailsSent);
                $this->scheduleSendingEmail->setEmailsFailed($EmailsFailed);
                $this->getEntityManager()->persist($this->scheduleSendingEmail);
                $this->getEntityManager()->flush();
            } else {
                $EmailsProcessed++;
                $EmailsPending--;
                if ($sendingList->getStatus() == "sent") {
                    $EmailsSent++;
                }
                if ($sendingList->getStatus() == "failed" || $sendingList->getStatus() == "invalid") {
                    $EmailsFailed++;
                }
            }
        }

        $this->scheduleSendingEmail->setState("finish");
        $this->scheduleSendingEmail->setDatetimeFinish(new \DateTime("now"));
        $this->getEntityManager()->persist($this->scheduleSendingEmail);
        $this->getEntityManager()->flush();
    }

    protected function getEmailAuthEnable($smtp){
        $query = $this->getEntityManager()->createQueryBuilder()
            ->select('u')
            ->from('Iem\Entity\EmailAuth', 'u')
            ->where('u.enable = 1')
            ->andWhere('u.smtp = :smtp')
            ->setParameter('smtp',$smtp->getId());

        return $query->getQuery()->getResult();

    }

    public function prepareSchedule() {


        // Defino SMTP
        $smtp = $this->scheduleSendingEmail->getSmtp();

        // Defino Cuentas Autenticadoras de SMTP
        $this->emailAuthCollection = $this->getEmailAuthEnable($smtp);

        // $emailMax = count($emailAuthCollection);
        // Defino Templates
        $this->subjectTemplateCollection = $this->scheduleSendingEmail->getGroupingTemplate()->getSubjects();
        //    $subjectMax = count($subjectTemplateCollection);

        $this->textTemplateCollection = $this->scheduleSendingEmail->getGroupingTemplate()->getTexts();
        //    $textMax = count($textTemplateCollection);
        // Defino Lista de Envios
        $this->sendingListCollection = $this->scheduleSendingEmail->getSendingList();
        $sendingMax = count($this->sendingListCollection);


        // Defino Intervalo y Intervalo Random
        $interval = $this->scheduleSendingEmail->getTimeInterval();

        // Defino items a toda la sending List
        $emailCount = 0;
        $textCount = 0;
        $subjectCount = 0;

        $sendingCount = 0;
        $timeIntervalTotal = 0;

        $this->generateCombination();

        shuffle($this->combination);
        $combinationMax = count($this->combination);
        $this->scheduleSendingEmail->setCombination($combinationMax);
        //echo "combination Max:".$combinationMax."\n";
        $combinationCount = 0;
        foreach ($this->sendingListCollection as $sendingList) {


            //Le asigno el intervalo
            $rand = rand(2, 15);
            $sumInterval = $interval + $rand;
            $timeIntervalTotal += $sumInterval;
            $sendingList->setTimeInterval($sumInterval);



            //Reseteo las combinaciones si llego al limite, tambien las vuelvo a mesclar
            if ($combinationCount >= $combinationMax) {
                $combinationCount = 0;
                shuffle($this->combination);
            }

            //Le asigno una cuenta

            $emailAuth = $this->combination[$combinationCount]["emailAuth"];
            $sendingList->setEmailAuth($emailAuth);
            $sendingList->setEmailFrom($emailAuth->getEmail());
            $sendingList->setDisplaynameFrom($emailAuth->getName() . " " . $emailAuth->getLastname());

            //Le asigno el Texto
            $textTemplate = $this->combination[$combinationCount]["text"];
            $sendingList->setText($textTemplate);
            $sendingList->setTextParsed($this->textParsed($textTemplate->getText(), $sendingList));
            //Le asigno el HTML
            $sendingList->setHtmlParsed($this->textParsed($textTemplate->getHtml(), $sendingList));

            //Le asigno el Asunto
            $subjectTemplate = $this->combination[$combinationCount]["subject"];
            $sendingList->setSubject($subjectTemplate);
            $sendingList->setSubjectParsed($this->subjectParsed($subjectTemplate->getSubject(), $sendingList));




            $sendingList->setStatus("pending");
            $this->getEntityManager()->persist($sendingList);
            $sendingCount++;

            if ($sendingCount % 100 == 0) {
                $this->getEntityManager()->flush();
                //$this->getEntityManager()->clear();
            }
            $combinationCount++;
        }
        $this->getEntityManager()->flush();
        $this->scheduleSendingEmail->setState("pending");
        $this->scheduleSendingEmail->setTimeIntervalTotal($timeIntervalTotal);
        $this->getEntityManager()->persist($this->scheduleSendingEmail);
        $this->getEntityManager()->flush();
        //$this->getEntityManager()->clear();
        //Comienzo el envio
    }

    protected function generateCombination() {

        if (isset($this->emailAuthCollection) && isset($this->subjectTemplateCollection) && isset($this->textTemplateCollection)) {
            $count = 0;
            foreach ($this->emailAuthCollection as $keyEmail => $itemEmailAuth) {

                foreach ($this->subjectTemplateCollection as $keySubject => $itemSubject) {

                    foreach ($this->textTemplateCollection as $keyText => $itemText) {
                        //$index = $keyEmail . "_" . $keySubject . "_" . $keyText;
                        $this->combination[$count]["emailAuth"] = $itemEmailAuth;
                        $this->combination[$count]["subject"] = $itemSubject;
                        $this->combination[$count]["text"] = $itemText;

                        $count++;
                    }
                }
            }

            return $this->combination;
        } else {
            throw new \Exception("Faltan colecciones");
            return null;
        }
    }

    protected function textParsed($textTemplate, $sendingList) {
        $aWildcard = array("{NOMBRE}", "{APELLIDO}", "{CUMPLE}", "{EDAD}");
        $aReplace = array($sendingList->getName(), $sendingList->getLastname(), $sendingList->getBirthdayText(), $sendingList->getAge());
        $msjParsed = str_replace($aWildcard, $aReplace, $textTemplate);
        return $msjParsed;
    }

    protected function subjectParsed($subjectTemplate, $sendingList) {
        $aWildcard = array("{NOMBRE}", "{APELLIDO}", "{CUMPLE}", "{EDAD}");
        $aReplace = array($sendingList->getName(), $sendingList->getLastname(), $sendingList->getBirthdayText(), $sendingList->getAge());
        $msjParsed = str_replace($aWildcard, $aReplace, $subjectTemplate);
        return $msjParsed;
    }

    public function generateSendingList() {

        $mailingListCollection = $this->scheduleSendingEmail->getMailingListManager()->getMailingList();

        $count = 0;
        foreach ($mailingListCollection as $mailingList) {
            $sendingList = new \Iem\Entity\SendingList();
            $sendingList->setName($mailingList->getName());
            $sendingList->setAge($mailingList->getAge());
            $sendingList->setLastname($mailingList->getLastname());
            $sendingList->setFacebookEmail($mailingList->getFacebookEmail());
            $sendingList->setEmail($mailingList->getEmail());
            $sendingList->setBirthdayText($mailingList->getBirthdayText());
            $sendingList->setScheduleSendingEmail($this->scheduleSendingEmail);
            $this->getEntityManager()->persist($sendingList);
            $count++;
            if ($count % 100 == 0) {
                $this->getEntityManager()->flush();
                //$this->getEntityManager()->clear();
            }
        }
        $this->scheduleSendingEmail->setEmailsProcessed(0);
        $this->scheduleSendingEmail->setEmailsTotal($count);
        $this->scheduleSendingEmail->setEmailsPending($count);
        $this->scheduleSendingEmail->setEmailsSent(0);
        $this->scheduleSendingEmail->setEmailsFailed(0);
        $this->getEntityManager()->persist($this->scheduleSendingEmail);
        $this->getEntityManager()->flush();
        // $this->getEntityManager()->clear();
    }

}

?>
