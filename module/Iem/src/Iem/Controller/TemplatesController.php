<?php

/**
 *
 */

namespace Iem\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class TemplatesController extends AbstractActionController {

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

 

    public function abmTextsAction() {
        $grid = $this->getServiceLocator()->get('cdiGrid');
        $source = new \CdiDataGrid\DataGrid\Source\Doctrine($this->getEntityManager(), '\Iem\Entity\Text');
        $grid->setSource($source);
        $grid->setRecordPerPage(20);
        $grid->datetimeColumn('createdAt', 'Y-m-d H:i:s');
        $grid->datetimeColumn('updatedAt', 'Y-m-d H:i:s');
        $grid->hiddenColumn('createdAt');
        $grid->hiddenColumn('updatedAt');
        $grid->hiddenColumn('createdBy');
        $grid->hiddenColumn('lastUpdatedBy');
        $grid->longTextColumn("text");
          $grid->longTextColumn("html");
        $grid->addExtraColumn("E", "<span style='color:blue; font-size: 10px;' class='glyphicon glyphicon-edit' onclick='cdiGoEdit(\"{{id}}\")'></spam>      ", "left", false);
        $grid->addDelOption("<i style='font-size: 10px;' class='fa fa-trash'></i>", "left", "btn btn-warning fa fa-trash");
        
        $filterType = new \DoctrineModule\Form\Element\ObjectSelect();
        $filterType->setOptions(array(
            'label' => 'Grouping',
            'object_manager' => $this->getEntityManager(),
            'target_class' => 'Iem\Entity\Grouping',
            'property' => 'name',
            'display_empty_item' => true,
            'empty_item_label' => 'Todos',
        ));
        $grid->setFormFilterSelect("smtp", $filterType);

           $grid->setTableClass("table-condensed customClass");
        $grid->prepare();
        return array('grid' => $grid);
    }

    public function editTextAction() {
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
            $object = $this->getEntityManager()->getRepository('Iem\Entity\Text')->find($id);
            $new = false;
        } else {
            $object = new \Iem\Entity\Text();
            $new = true;
        }

        /*
         * Declar el Formulario
         * Defino el Hidratador de Doctrine
         * Hago el Bind entre el Formulario y el objeto
         */
        $form = new \Iem\Form\Text($this->getEntityManager());
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

    public function abmGroupingAction() {
        $grid = $this->getServiceLocator()->get('cdiGrid');
        $source = new \CdiDataGrid\DataGrid\Source\Doctrine($this->getEntityManager(), '\Iem\Entity\Grouping');
        $grid->setSource($source);
        $grid->setRecordPerPage(20);
        $grid->datetimeColumn('createdAt', 'Y-m-d H:i:s');
        $grid->datetimeColumn('updatedAt', 'Y-m-d H:i:s');
        $grid->hiddenColumn('createdAt');
        $grid->hiddenColumn('updatedAt');
        $grid->hiddenColumn('createdBy');
        $grid->hiddenColumn('lastUpdatedBy');
           $grid->hiddenColumn('texts');
            $grid->hiddenColumn('subjects');
        $grid->addExtraColumn("E", "<span style='color:blue; font-size: 10px;' class='glyphicon glyphicon-edit' onclick='cdiGoEdit(\"{{id}}\")'></spam>      ", "left", false);
        $grid->addDelOption("<i class='fa fa-trash'></i>", "left", "btn btn-warning fa fa-trash");
        
           $grid->setTableClass("table-condensed customClass");
        $grid->prepare();
        return array('grid' => $grid);
    }

    public function editGroupingAction() {
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
            $object = $this->getEntityManager()->getRepository('Iem\Entity\Grouping')->find($id);
            $new = false;
        } else {
            $object = new \Iem\Entity\Grouping();
            $new = true;
        }

        /*
         * Declar el Formulario
         * Defino el Hidratador de Doctrine
         * Hago el Bind entre el Formulario y el objeto
         */
        $form = new \Iem\Form\Grouping($this->getEntityManager());
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

    public function abmSubjectsAction() {
        $grid = $this->getServiceLocator()->get('cdiGrid');
        $source = new \CdiDataGrid\DataGrid\Source\Doctrine($this->getEntityManager(), '\Iem\Entity\Subject');
        $grid->setSource($source);
        $grid->setRecordPerPage(20);
        $grid->datetimeColumn('createdAt', 'Y-m-d H:i:s');
        $grid->datetimeColumn('updatedAt', 'Y-m-d H:i:s');
        $grid->hiddenColumn('createdAt');
        $grid->hiddenColumn('updatedAt');
        $grid->hiddenColumn('createdBy');
        $grid->hiddenColumn('lastUpdatedBy');
        $grid->addExtraColumn("E", "<span style='color:blue; font-size: 10px;' class='glyphicon glyphicon-edit' onclick='cdiGoEdit(\"{{id}}\")'></spam>      ", "left", false);
        $grid->addDelOption("<i class='fa fa-trash'></i>", "left", "btn btn-warning fa fa-trash");
        
           $grid->setTableClass("table-condensed customClass");
        $grid->prepare();
        return array('grid' => $grid);
    }

    public function editSubjectAction() {
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
            $object = $this->getEntityManager()->getRepository('Iem\Entity\Subject')->find($id);
            $new = false;
        } else {
            $object = new \Iem\Entity\Subject();
            $new = true;
        }

        /*
         * Declar el Formulario
         * Defino el Hidratador de Doctrine
         * Hago el Bind entre el Formulario y el objeto
         */
        $form = new \Iem\Form\Subject($this->getEntityManager());
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
    
    public function abmLayoutAction() {
        $grid = $this->getServiceLocator()->get('cdiGrid');
        $source = new \CdiDataGrid\DataGrid\Source\Doctrine($this->getEntityManager(), '\Iem\Entity\EmailLayout');
        $grid->setSource($source);
        $grid->setRecordPerPage(20);
        $grid->datetimeColumn('createdAt', 'Y-m-d H:i:s');
        $grid->datetimeColumn('updatedAt', 'Y-m-d H:i:s');
        $grid->hiddenColumn('createdAt');
        $grid->hiddenColumn('updatedAt');
        $grid->hiddenColumn('createdBy');
        $grid->hiddenColumn('lastUpdatedBy');
        
          $grid->hiddenColumn('startTxt');
            $grid->hiddenColumn('endTxt');
              $grid->hiddenColumn('startHtml');
                $grid->hiddenColumn('endHtml');

        $grid->addEditOption("Edit", "left", "btn btn-success fa fa-edit");
        $grid->addDelOption("Del", "left", "btn btn-warning fa fa-trash");
        $grid->addNewOption("Add", "btn btn-primary fa fa-plus", " Agregar");
           $grid->setTableClass("table-condensed customClass");
        $grid->prepare();
        return array('grid' => $grid);
    }

}

