<?php

/**
 *
 */

namespace Iem\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class TracingController extends AbstractActionController {

    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $em;

    public function setEntityManager(EntityManager $em) {
        $this->em = $em;
    }

    public function getEntityManager() {
        if (null === $this->em) {
            $this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }
        return $this->em;
    }

    public function conversationsAction() {
        
          $query = $this->getEntityManager()
                ->createQueryBuilder('u')
                ->select('u')
                ->from('\Iem\Entity\EmailConversation', 'u')
               ->where("u.deleted is null");
        
        
        $grid = $this->getServiceLocator()->get('cdiGrid');
        $source = new \CdiDataGrid\DataGrid\Source\Doctrine($this->getEntityManager(), '\Iem\Entity\EmailConversation',$query);
        $grid->setSource($source);
        $grid->setRecordPerPage(50);
        $grid->datetimeColumn('createdAt', 'Y-m-d H:i:s');
        $grid->datetimeColumn('updatedAt', 'Y-m-d H:i:s');
        $grid->hiddenColumn('id');
        $grid->hiddenColumn('createdAt');
        $grid->hiddenColumn('updatedAt');
        $grid->hiddenColumn('createdBy');
//        $grid->hiddenColumn('type');
//        $grid->hiddenColumn('seen');
        $grid->hiddenColumn('lastUpdatedBy');
           $grid->hiddenColumn('deleted');
        $grid->hiddenColumn('responses');
        //$grid->hiddenColumn('isResponded');
//        $grid->longTextColumn("body");
        $grid->datetimeColumn('date', 'Y-m-d H:i:s');

        // $grid->addEditOption("Edit", "left", "btn btn-success fa fa-edit");
        //$grid->addDelOption("Del", "left", "btn btn-warning fa fa-trash btn-xs");


        $grid->addExtraColumn("G", "<a target='_blank'  class='btn btn-primary btn-xs' href='/iem/tracing/manage?id={{id}}'><i class='fa fa-external-link'></i></a>", "left", false);


        $grid->setOrderColumn("Del", 1);
        $grid->setOrderColumn("G", 2);
        
         $grid->setOrderColumn("subject", 3);
          $grid->setOrderColumn("emailA", 4);
          $grid->setOrderColumn("emailB", 5);

        $grid->setOrderColumn("state", 6);


        $filterType = new \DoctrineModule\Form\Element\ObjectSelect();
        $filterType->setOptions(array(
            'label' => 'state',
            'object_manager' => $this->getEntityManager(),
            'target_class' => 'Iem\Entity\ConversationState',
            'property' => 'name',
            'display_empty_item' => true,
            'empty_item_label' => 'Todos',
        ));
        $grid->setFormFilterSelect("state", $filterType);



        $filterType = new \DoctrineModule\Form\Element\ObjectSelect();
        $filterType->setOptions(array(
            'label' => 'ScheludeSendingEmail',
            'object_manager' => $this->getEntityManager(),
            'target_class' => 'Iem\Entity\ScheduleSendingEmail',
            'property' => 'name',
            'display_empty_item' => true,
            'empty_item_label' => 'Todos',
        ));
        $grid->setFormFilterSelect("ScheduleSendingEmail", $filterType);


        $select = new \Zend\Form\Element\Select('isResponded');
        $select->setValueOptions(array(
            '' => 'Todos',
            '0' => 'Pendiente',
            '1' => 'Respondido'
        ));
        $grid->setFormFilterSelect("isResponded", $select);
        $grid->booleanColumn("isResponded", "Respondido", "Pendiente");

        $grid->setTableClass("table-condensed customClass");

        $grid->addExtraColumn("D", "<a target='_blank'  class='btn btn-warning btn-xs' href='/iem/tracing/delete?id={{id}}'><i class='fa fa-trash'></i></a>", "right", false);


        $grid->addExtraColumn("B", "<a target='_blank'  class='btn btn-danger btn-xs' href='/iem/tracing/contact?id={{id}}'><i class='fa fa-user'></i></a>", "right", false);

        $grid->prepare();
        return array('grid' => $grid);
    }

    public function reboundsAction() {

        $form = new \Iem\Form\DesdeHasta();

        $grid = $this->getServiceLocator()->get('cdiGrid');
        $source = new \CdiDataGrid\DataGrid\Source\Doctrine($this->getEntityManager(), '\Iem\Entity\EmailRebound');
        $grid->setSource($source);
        $grid->setRecordPerPage(20);
        $grid->datetimeColumn('createdAt', 'Y-m-d H:i:s');
        $grid->datetimeColumn('updatedAt', 'Y-m-d H:i:s');
        $grid->hiddenColumn('id');
        $grid->hiddenColumn('createdAt');
        $grid->hiddenColumn('updatedAt');
        $grid->hiddenColumn('createdBy');
        $grid->hiddenColumn('type');
        $grid->hiddenColumn('seen');
        $grid->hiddenColumn('lastUpdatedBy');
        $grid->longTextColumn("body");
        $grid->datetimeColumn('date', 'Y-m-d H:i:s');

        // $grid->addEditOption("Edit", "left", "btn btn-success fa fa-edit");
        $grid->addDelOption("Del", "left", "btn btn-warning fa fa-trash btn-xs");


        $grid->addExtraColumn("View", "<a target='blank'  class='btn btn-primary btn-xs' href='/iem/tracing/view?id={{id}}'><i class='fa fa-bookmark-o'></i></a>", "left", false);



        $grid->setTableClass("table-condensed customClass");

        $grid->prepare();
        return array('grid' => $grid,
            'form' => $form);
    }

    public function contactAction() {
        /*
         * Recibo la informacion por GET
         */
        $aGetData = $this->getRequest()->getQuery();
        $id = $aGetData['id'];
        $emailConversation = $this->getEntityManager()->getRepository('Iem\Entity\EmailConversation')->find($id);



        $query = $this->getEntityManager()->createQueryBuilder()
                ->select('u')
                ->from('Iem\Entity\SendingList', 'u')
                ->where("u.emailFrom = '" . trim($emailConversation->getEmailA()) . "'")
                ->andWhere("u.subjectParsed = '" . trim($emailConversation->getSubject()) . "'")
                ->orderBy("u.shippingDate", "DESC")
                ->setMaxResults(20);


        $result = $query->getQuery()->getResult();
        if ($result) {
            foreach ($result as $item) {

                $contact = $this->getEntityManager()->getRepository('Iem\Entity\Contact')->findOneBy(array(
                    "facebookEmail" => $item->getFacebookEmail()));
                $contacts[] = $contact;
            }
        }
        return array('contacts' => $contacts);
    }

    public function manageAction() {

        /*
         * Recibo la informacion por GET
         */
        $aGetData = $this->getRequest()->getQuery();
        $id = $aGetData['id'];

        $formResponse = new \Iem\Form\EmailResponse($this->getEntityManager());
        $formResponse->get("conversationId")->setValue($id);

        $formState = new \Iem\Form\ConversationState($this->getEntityManager());
        $formState->get("conversationId")->setValue($id);

        $emailConversation = $this->getEntityManager()->getRepository('Iem\Entity\EmailConversation')->find($id);

        $formState->get("state")->setValue($emailConversation->getState());

        /*
         * Verifico el Post, valido formulario y persisto en caso positivo
         */
        if ($this->getRequest()->isPost()) {
            $aPostData = $this->getRequest()->getPost();


            $formState->setData($aPostData);


            if ($formState->isValid()) {
                $state = $this->getEntityManager()->getRepository('Iem\Entity\ConversationState')->find($aPostData["state"]);
                $emailConversation->setState($state);
                $this->getEntityManager()->persist($emailConversation);
                $this->getEntityManager()->flush();
            }

            $formResponse->setData($aPostData);
            $formResponse->setInputFilter($formResponse->InputFilter());

            if ($formResponse->isValid()) {

                if ($aPostData["bodyText"] != "") {
                    //$emailConversation = $this->getEntityManager()->getRepository("\Iem\Entity\EmailConversation")->find($aPostData["conversationId"]);

                    $emailFrom = $this->getEntityManager()->getRepository("\Iem\Entity\EmailAuth")->findOneBy(array("email" => $emailConversation->getEmailA()));
                    $smtp = $emailFrom->getSmtp();



                    $MailSender = new \Iem\Service\MailOne();
                    $MailSender->smtpOptions($smtp->getSmtp(), $smtp->getSmtpPort(), "login", $emailFrom->getEmail(), $emailFrom->getPassword(), $smtp->getSmtpSecure());
                    $result = $MailSender->sendMail($aPostData["bodyText"], nl2br($aPostData["bodyText"]), "Re:" . $emailConversation->getSubject(), $emailFrom->getEmail(), $emailFrom->getName()." ".$emailFrom->getLastname(), $emailConversation->getEmailB(), "");

                    $date = new \DateTime("now");

                    if ($result[0]) {
                        $emailStore = new \Iem\Entity\EmailStore();
                        $emailStore->setConversation($emailConversation);
                        $emailStore->setSenderBy($emailConversation->getEmailA());
                        $emailStore->setSentTo($emailConversation->getEmailB());
                        $emailStore->setBody($aPostData["bodyText"]);
                        $emailStore->setSubject($emailConversation->getSubject());
                        $emailStore->setType("Envio");
                        $emailStore->setDate($date);
                        $emailStore->setMessageId($date->format("YmdHis") . "-" . $emailConversation->getId());
                        $emailConversation->setIsResponded(true);
                        $this->getEntityManager()->persist($emailStore);
                        $this->getEntityManager()->flush();
                    }
                }
            }
        }





        $query = $this->getEntityManager()->createQueryBuilder()
                ->select('u')
                ->from('Iem\Entity\EmailStore', 'u')
                ->where("u.conversation = :cc")
                ->setParameter("cc", $id);
      //          ->orderBy('u.date', 'ASC');









        $colEmailStore = $query->getQuery()->getResult();
        return array('colEmailStore' => $colEmailStore,
            'emailConversation' => $emailConversation,
            'formResponse' => $formResponse,
            'formState' => $formState);
    }

    public function deleteAction() {

        /*
         * Recibo la informacion por GET
         */
        $aGetData = $this->getRequest()->getQuery();
        $id = $aGetData['id'];

        $emailConversation = $this->getEntityManager()->getRepository('Iem\Entity\EmailConversation')->find($id);

        $emailConversation->setDeleted(true);
        $this->getEntityManager()->persist($emailConversation);
                $this->getEntityManager()->flush();
        
        
        
// Pendiente proceso de borrar los mails de la cuenta
//        $emailFrom = $this->getEntityManager()->getRepository("\Iem\Entity\EmailAuth")->findOneBy(array("email" => $emailConversation->getEmailA()));
//        $smtp = $emailFrom->getSmtp();
//
//
//        $query = $this->getEntityManager()->createQueryBuilder()
//                ->select('u')
//                ->from('Iem\Entity\EmailStore', 'u')
//                ->where("u.conversation = :cc")
//                ->setParameter("cc", $id)
//                ->orderBy('u.date', 'ASC');
//
//        $colEmailStore = $query->getQuery()->getResult();
//        $hostname = '{imap.gmail.com:993/imap/ssl}INBOX';
//
//        $username = $emailFrom->getEmail();
//        $password = $emailFrom->getPassword();
//        $imap = new CdiTools\Mail\Imap("imap.gmail.com", $username, $password, "993", true);
//        foreach ($colEmailStore as $item) {
//            echo $item->getId();
//            echo $item->getUid();   
//        }
    }

    public function stateAction() {
        $grid = $this->getServiceLocator()->get('cdiGrid');
        $source = new \CdiDataGrid\DataGrid\Source\Doctrine($this->getEntityManager(), '\Iem\Entity\ConversationState');
        $grid->setSource($source);
        $grid->setRecordPerPage(20);
        $grid->datetimeColumn('createdAt', 'Y-m-d H:i:s');
        $grid->datetimeColumn('updatedAt', 'Y-m-d H:i:s');
        $grid->hiddenColumn('createdAt');
        $grid->hiddenColumn('updatedAt');
        $grid->hiddenColumn('createdBy');
        $grid->hiddenColumn('lastUpdatedBy');

        $grid->addEditOption("Edit", "left", "btn btn-success fa fa-edit");
        $grid->addDelOption("Del", "left", "btn btn-warning fa fa-trash");
        $grid->addNewOption("Add", "btn btn-primary fa fa-plus", " Agregar");

        $grid->prepare();
        return array('grid' => $grid);
    }

    public function textAction() {
        $grid = $this->getServiceLocator()->get('cdiGrid');
        $source = new \CdiDataGrid\DataGrid\Source\Doctrine($this->getEntityManager(), '\Iem\Entity\ConversationText');
        $grid->setSource($source);
        $grid->setRecordPerPage(20);
        $grid->datetimeColumn('createdAt', 'Y-m-d H:i:s');
        $grid->datetimeColumn('updatedAt', 'Y-m-d H:i:s');
        $grid->hiddenColumn('createdAt');
        $grid->hiddenColumn('updatedAt');
        $grid->hiddenColumn('createdBy');
        $grid->hiddenColumn('lastUpdatedBy');

        $grid->addEditOption("Edit", "left", "btn btn-success fa fa-edit");
        $grid->addDelOption("Del", "left", "btn btn-warning fa fa-trash");
        $grid->addNewOption("Add", "btn btn-primary fa fa-plus", " Agregar");

        $grid->prepare();
        return array('grid' => $grid);
    }

    public function reservasAction() {
        $grid = $this->getServiceLocator()->get('cdiGrid');
        $source = new \CdiDataGrid\DataGrid\Source\Doctrine($this->getEntityManager(), '\Iem\Entity\Reserva');
        $grid->setSource($source);
        $grid->setRecordPerPage(20);
        $grid->datetimeColumn('createdAt', 'Y-m-d H:i:s');
        $grid->datetimeColumn('updatedAt', 'Y-m-d H:i:s');
        $grid->hiddenColumn('createdAt');
        $grid->hiddenColumn('updatedAt');
        $grid->hiddenColumn('createdBy');
        $grid->hiddenColumn('lastUpdatedBy');
        $grid->datetimeColumn('fecha', 'Y-m-d');
        $grid->addEditOption("Edit", "left", "btn btn-success fa fa-edit");
        $grid->addDelOption("Del", "left", "btn btn-warning fa fa-trash");
        $grid->addNewOption("Add", "btn btn-primary fa fa-plus", " Agregar");
        $grid->setTableClass("table-condensed customClass");

        $grid->prepare();
        return array('grid' => $grid);
    }

    public function loadTextAction() {
        $aGetData = $this->getRequest()->getQuery();
        $id = $aGetData['id'];


        $text = $this->getEntityManager()->getRepository('Iem\Entity\ConversationText')->find($id);

        $view = new ViewModel(array(
            'text' => $text));
        $view->setTerminal(true);
        return $view;
    }

}
