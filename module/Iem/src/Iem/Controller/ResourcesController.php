<?php

/**
 *
 */

namespace Iem\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ResourcesController extends AbstractActionController {

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

    public function abmSmtpAction() {
        $grid = $this->getServiceLocator()->get('cdiGrid');
        $source = new \CdiDataGrid\DataGrid\Source\Doctrine($this->getEntityManager(), '\Iem\Entity\Smtp');
        $grid->setSource($source);
        $grid->setRecordPerPage(20);
        $grid->datetimeColumn('createdAt', 'Y-m-d H:i:s');
        $grid->datetimeColumn('updatedAt', 'Y-m-d H:i:s');
        $grid->hiddenColumn('createdAt');
        $grid->hiddenColumn('updatedAt');
        $grid->hiddenColumn('createdBy');
        $grid->hiddenColumn('emailAuthCollection');
        $grid->hiddenColumn('lastUpdatedBy');
        $grid->addExtraColumn("E", "<span style='color:blue; font-size: 20px;' class='glyphicon glyphicon-edit' onclick='cdiGoEdit(\"{{id}}\")'></spam>      ", "left", false);
      $grid->addDelOption("<i class='fa fa-trash'></i>", "left", "btn btn-warning fa fa-trash");
        
        
        $grid->prepare();
        return array('grid' => $grid);
    }

    public function editSmtpAction() {
        /*
         * Recibo la informacion por GET
         */
        $aGetData = $this->getRequest()->getQuery();
        $id = $aGetData['id'];

        /*
         * Verifico si me llega un ID por POST
         */
        if (!$id) {
            $aPostData = $this->getRequest()->getPost();
            $id = $aPostData['id'];
        }

        /*
         * En el caso de que este el ID, busco el registro en la DB
         * En el caso que ID este null, creo un nuevo objeto
         */
        if ($id) {
            $object = $this->getEntityManager()->getRepository('Iem\Entity\Smtp')->find($id);
            $new = false;
        } else {
            $object = new \Iem\Entity\Smtp();
            $new = true;
        }

        /*
         * Declar el Formulario
         * Defino el Hidratador de Doctrine
         * Hago el Bind entre el Formulario y el objeto
         */
        $form = new \Iem\Form\Smtp();
        $form->setHydrator(new \DoctrineModule\Stdlib\Hydrator\DoctrineObject($this->getEntityManager()));
        $form->bind($object);

        /*
         * Verifico el Post, valido formulario y persisto en caso positivo
         */
        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());

            $form->setInputFilter($object->getInputFilter());
            if ($form->isValid()) {

                if ($this->zfcUserAuthentication()->hasIdentity()) {
                    $user = $this->zfcUserAuthentication()->getIdentity();
                }

                if ($new) {
                    $object->setCreatedBy($user);
                    $object->setLastUpdatedBy($user);
                } else {
                    $object->setLastUpdatedBy($user);
                }


                $this->getEntityManager()->persist($object);
                $this->getEntityManager()->flush();
                $form->bind($object);
                $persist = true;
            } else {
                $persist = false;
            }
        }

        /*
         * Paso la variable persist a la view
         * Defino terminal true para no renderizar el layout (ajax)
         */
        $view = new ViewModel(array('form' => $form,
            'persist' => $persist));
        $view->setTerminal(true);
        return $view;
    }

    public function testEmailAction() {
        $aGetData = $this->getRequest()->getQuery();
        $id = $aGetData['id'];
        $form = new \Iem\Form\TestEmail();
        $form->setAttribute('action', "javascript:submitFormByAjax(\"TestEmail\", \"/iem/resources/test-email\", \"cdiAjaxContent\")");
        $form->get('id')->setValue($id);
        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            $aPostData = $this->getRequest()->getPost();
            $form->setInputFilter($form->InputFilter());
            if ($form->isValid()) {
                $id = $form->get('id')->getValue();
                $object = $this->getEntityManager()->getRepository('Iem\Entity\EmailAuth')->find($id);

                $MailSender = new \Iem\Service\MailTest();
                $MailSender->smtpOptions("smtp.gmail.com", "465", "login", $object->getEmail(), $object->getPassword(), "ssl");
                $result = $MailSender->sendMail("Prueba de envio", "Prueba de envio", $object->getEmail(), $object->getName(), $aPostData["dest"], "Fulano");
            }
        }


        $view = new ViewModel(array('form' => $form,
            'persist' => $persist,
            'resultTest' => $result));
        $view->setTerminal(true);
        return $view;
    }

    public function abmEmailAuthAction() {
        $grid = $this->getServiceLocator()->get('cdiGrid');
        $source = new \CdiDataGrid\DataGrid\Source\Doctrine($this->getEntityManager(), '\Iem\Entity\EmailAuth');
        $grid->setSource($source);
        $grid->setRecordPerPage(50);
        $grid->datetimeColumn('createdAt', 'Y-m-d H:i:s');
        $grid->datetimeColumn('updatedAt', 'Y-m-d H:i:s');
        $grid->hiddenColumn('createdAt');
            $grid->hiddenColumn('id');
        $grid->hiddenColumn('updatedAt');
        $grid->hiddenColumn('createdBy');
        $grid->hiddenColumn('lastUpdatedBy');
        
        $grid->addExtraColumn("E", "<span style=' font-size: 10px;' class='btn btn-primary btn-sm glyphicon glyphicon-edit' onclick='cdiGoEdit(\"{{id}}\")'></spam>      ", "left", false);
       
         $grid->addDelOption("<i style=' font-size: 10px;' class='fa fa-trash'></i>", "left", "btn btn-warning fa fa-trash cdiFontSize10");
        
        $grid->addExtraColumn("P", "<span style=' font-size: 10px;' class='btn btn-success btn-sm fa fa-paper-plane ' onclick='loadObjectFormToModal(\"cdiModal\", \"{{id}}\", \"/iem/resources/test-email\", \"cdiAjaxContent\")'></spam>", "left", false);


        $filterType = new \DoctrineModule\Form\Element\ObjectSelect();
        $filterType->setOptions(array(
            'label' => 'smtp',
            'object_manager' => $this->getEntityManager(),
            'target_class' => 'Iem\Entity\Smtp',
            'property' => 'name',
            'display_empty_item' => true,
            'empty_item_label' => 'Todos',
        ));
        $grid->setFormFilterSelect("smtp", $filterType);
   $grid->setTableClass("table-condensed customClass");

        $grid->prepare();
        return array('grid' => $grid);
    }

    public function editEmailAuthAction() {
        /*
         * Recibo la informacion por GET
         */
        $aGetData = $this->getRequest()->getQuery();
        $id = $aGetData['id'];

        /*
         * Verifico si me llega un ID por POST
         */
        if (!$id) {
            $aPostData = $this->getRequest()->getPost();
            $id = $aPostData['id'];
        }

        /*
         * En el caso de que este el ID, busco el registro en la DB
         * En el caso que ID este null, creo un nuevo objeto
         */
        if ($id) {
            $object = $this->getEntityManager()->getRepository('Iem\Entity\EmailAuth')->find($id);
            $new = false;
        } else {
            $object = new \Iem\Entity\EmailAuth();
            $new = true;
        }

        /*
         * Declar el Formulario
         * Defino el Hidratador de Doctrine
         * Hago el Bind entre el Formulario y el objeto
         */
        $form = new \Iem\Form\EmailAuth($this->getEntityManager());
        $form->setHydrator(new \DoctrineModule\Stdlib\Hydrator\DoctrineObject($this->getEntityManager()));
        $form->bind($object);

        /*
         * Verifico el Post, valido formulario y persisto en caso positivo
         */
        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());

            $form->setInputFilter($object->getInputFilter());
            if ($form->isValid()) {

                if ($this->zfcUserAuthentication()->hasIdentity()) {
                    $user = $this->zfcUserAuthentication()->getIdentity();
                }

                if ($new) {
                    $object->setCreatedBy($user);
                    $object->setLastUpdatedBy($user);
                } else {
                    $object->setLastUpdatedBy($user);
                }


                $this->getEntityManager()->persist($object);
                $this->getEntityManager()->flush();
                $form->bind($object);
                $persist = true;
            } else {
                $persist = false;
            }
        }

        /*
         * Paso la variable persist a la view
         * Defino terminal true para no renderizar el layout (ajax)
         */
        $view = new ViewModel(array('form' => $form,
            'persist' => $persist));
        $view->setTerminal(true);
        return $view;
    }

    public function abmEmailTestAction() {
        $grid = $this->getServiceLocator()->get('cdiGrid');
        $source = new \CdiDataGrid\DataGrid\Source\Doctrine($this->getEntityManager(), '\Iem\Entity\EmailTest');
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

}
