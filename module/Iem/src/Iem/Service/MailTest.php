<?php

namespace Iem\Service;

/**
 * Description of MailSender
 *
 * @author cincarnato
 */
class MailTest {

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

    function __construct() {
        
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

    public function sendMail($body, $subject, $emailFrom, $nameFrom, $emailTo, $nameTo) {

        if (isset($this->smtpTransport) && isset($this->smtpOptions)) {
            try {

                $this->mail = new \Zend\Mail\Message();
                $this->mail->setBody($body);
                $this->mail->setSubject($subject);
                $this->mail->setFrom($emailFrom, $nameFrom);
                $this->mail->addTo($emailTo, $nameTo);
                $this->mail->setEncoding("UTF-8");

                $this->smtpTransport->send($this->mail);
                return array(true, "ok");
            } catch (\Zend\Mail\Protocol\Exception\RuntimeException $exc) {
             


                return array(false, $exc);
            } catch (\RuntimeException $e) {
           

                return array(false, $e);
            } catch (\Exception $exc) {
           

                return array(false, $exc);
            }
        } else {
            throw new \Exception("No Smtp transport/options defined");
        }
    }

}

?>
