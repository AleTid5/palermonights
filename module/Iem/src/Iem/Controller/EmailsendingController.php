<?php

/**
 *
 */

namespace Iem\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class EmailsendingController extends AbstractActionController {

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

    public function abmScheduleAction() {
        $grid = $this->getServiceLocator()->get('cdiGrid');
        $source = new \CdiDataGrid\DataGrid\Source\Doctrine($this->getEntityManager(), '\Iem\Entity\ScheduleSendingEmail');
        $grid->setSource($source);
        $grid->setRecordPerPage(20);
        $grid->datetimeColumn('createdAt', 'Y-m-d H:i:s');
        $grid->datetimeColumn('updatedAt', 'Y-m-d H:i:s');
        $grid->hiddenColumn('createdAt');
        $grid->hiddenColumn('updatedAt');
        $grid->hiddenColumn('createdBy');
        $grid->hiddenColumn('lastUpdatedBy');
        $grid->hiddenColumn('sendingList');
        $grid->hiddenColumn('datetimeFinish');
        $grid->hiddenColumn('estimatedEndingtime');
        $grid->hiddenColumn('timeIntervalTotal');


        $grid->hiddenColumn('emailsPending');
        $grid->hiddenColumn('emailsSent');
        $grid->hiddenColumn('emailsFailed');
        $grid->hiddenColumn('emailsTotal');
        $grid->hiddenColumn('emailsProcessed');



        $grid->datetimeColumn("datetimeSchedule", "Y-m-d H:i");

        $grid->changeColumnName("name", "nombre");
        $grid->changeColumnName("datetimeSchedule", "Fecha y Hora");
        $grid->changeColumnName("timeInterval", "Intervalo");
        $grid->changeColumnName("mailingListManager", "Lista");
        $grid->changeColumnName("groupingTemplate", "Templates");

        //$grid->addExtraColumn("E", "<span style='color:blue; font-size: 20px;' class='glyphicon glyphicon-edit' onclick='cdiGoEdit(\"{{id}}\")'></spam>      ", "left", false);
        // $grid->addExtraColumn("D", "<span style='color:blue; font-size: 20px;' class='glyphicon glyphicon-trash' onclick='cdiGoDelete(\"{{id}}\")'></spam>", "left", false);
        $grid->setTableClass("customClass tauto");
        $grid->prepare();
        return array('grid' => $grid);
    }

    public function editScheduleAction() {
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
            $object = $this->getEntityManager()->getRepository('Iem\Entity\ScheduleSendingEmail')->find($id);
            $new = false;
        } else {
            $object = new \Iem\Entity\ScheduleSendingEmail();
            $new = true;
        }

        /*
         * Declar el Formulario
         * Defino el Hidratador de Doctrine
         * Hago el Bind entre el Formulario y el objeto
         */
        $form = new \Iem\Form\ScheduleSendingEmail($this->getEntityManager());
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
                    $object->setState("programmed");
                } else {
                    $object->setLastUpdatedBy($user);
                }

                //Armo el SendingList


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

    public function statusFinishAction() {


        $query = $this->getEntityManager()
                ->createQueryBuilder('u')
                ->select('u')
                ->from('\Iem\Entity\ScheduleSendingEmail', 'u')
                ->where("u.state = 'finish' ");

        $ScheduleSendingEmail = $query->getQuery()->getResult();

        $view = new ViewModel(array('ScheduleSendingEmail' => $ScheduleSendingEmail));
        return $view;
    }

    public function statusPendingAction() {


        $query = $this->getEntityManager()
                ->createQueryBuilder('u')
                ->select('u')
                ->from('\Iem\Entity\ScheduleSendingEmail', 'u')
                ->where("u.state != 'finish' ");

        $ScheduleSendingEmail = $query->getQuery()->getResult();

        $view = new ViewModel(array('ScheduleSendingEmail' => $ScheduleSendingEmail));
        return $view;
    }

    public function statusAllAction() {


        if ($this->getRequest()->isPost()) {
            $aPostData = $this->getRequest()->getPost();
            if ($aPostData["control"] == "pause") {
                $object = $this->getEntityManager()->getRepository('Iem\Entity\ScheduleSendingEmail')->find($aPostData["id"]);
                $object->setState("pause");
                if (!$object->getScheduleEmailControl()) {
                    $controlState = new \Iem\Entity\ScheduleEmailControl();
                    $controlState->setScheduleSendingEmail($object);
                    $object->setScheduleEmailControl($controlState);
                }

                $object->getScheduleEmailControl()->setState("pause");



                $this->getEntityManager()->persist($object);
                $this->getEntityManager()->flush();
            }

            if ($aPostData["control"] == "restart") {
                $object = $this->getEntityManager()->getRepository('Iem\Entity\ScheduleSendingEmail')->find($aPostData["id"]);
                $object->setState("restart");
                $object->getScheduleEmailControl()->setState("restart");

                $this->getEntityManager()->persist($object);
                $this->getEntityManager()->flush();
            }
        }


        $query = $this->getEntityManager()
                ->createQueryBuilder('u')
                ->select('u')
                ->from('\Iem\Entity\ScheduleSendingEmail', 'u');

        $ScheduleSendingEmail = $query->getQuery()->getResult();

        $view = new ViewModel(array('ScheduleSendingEmail' => $ScheduleSendingEmail));
        return $view;
    }

    public function listViewAction() {
        $aGetData = $this->getRequest()->getQuery();
        $id = $aGetData['id'];
        $ScheduleSendingEmail = $this->getEntityManager()->getRepository('\Iem\Entity\ScheduleSendingEmail')->find($id);
        $view = new ViewModel(array('ScheduleSendingEmail' => $ScheduleSendingEmail));
        return $view;
    }

}
