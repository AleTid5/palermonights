<?php

/**
 *
 */

namespace Iem\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class MailinglistController extends AbstractActionController {

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

    public function fromArchiveAction() {
        
    }

    public function abmArchiveAction() {
        $grid = $this->getServiceLocator()->get('cdiGrid');
        $source = new \CdiDataGrid\DataGrid\Source\Doctrine($this->getEntityManager(), '\Iem\Entity\MailingListManager');
        $grid->setSource($source);
        $grid->setRecordPerPage(20);
        $grid->datetimeColumn('createdAt', 'Y-m-d H:i:s');
        $grid->datetimeColumn('updatedAt', 'Y-m-d H:i:s');
        $grid->hiddenColumn('createdAt');
        $grid->hiddenColumn('updatedAt');
        $grid->hiddenColumn('createdBy');
        $grid->hiddenColumn('lastUpdatedBy');
        $grid->hiddenColumn('list');
         $grid->hiddenColumn('id');
        $grid->hiddenColumn('csvSeparator');
         $grid->hiddenColumn('mailingList');
        $grid->changeColumnName('name', 'Nombre');
        $grid->changeColumnName('fileName', 'Nombre de Archivo');
        $grid->changeColumnName('filePath', 'Path de Archivo');


       // $grid->addExtraColumn("E", "<span style='color:blue; font-size: 20px;' class='glyphicon glyphicon-edit' onclick='cdiGoEdit(\"{{id}}\")'></spam>      ", "left", false);
        $grid->addExtraColumn("Lista", "<a style='color:blue; ' class='' target='_blank' href='/iem/mailinglist/list-view?ml={{id}}' )'>LISTA</a>", "left", false);


// $grid->addExtraColumn("D", "<span style='color:blue; font-size: 20px;' class='glyphicon glyphicon-trash' onclick='cdiGoDelete(\"{{id}}\")'></spam>", "left", false);
        $grid->prepare();
        return array('grid' => $grid);
    }

    public function listViewAction() {
        $aGetData = $this->getRequest()->getQuery();
        $id = $aGetData['ml'];
          $mailingListManager =  $this->getEntityManager()->getRepository('\Iem\Entity\MailingListManager')->find($id);
        $view = new ViewModel(array('mailingListManager' => $mailingListManager));
        return $view;
    }

    public function editArchiveAction() {
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
            $object = $this->getEntityManager()->getRepository('\Iem\Entity\MailingListManager')->find($id);
            $new = false;
        } else {
            $object = new \Iem\Entity\MailingListManager();
            $new = true;
        }

        /*
         * Declar el Formulario
         * Defino el Hidratador de Doctrine
         * Hago el Bind entre el Formulario y el objeto
         */
        $form = new \Iem\Form\MailingListManager();
        $form->setHydrator(new \DoctrineModule\Stdlib\Hydrator\DoctrineObject($this->getEntityManager()));
        $form->bind($object);

        /*
         * Verifico el Post, valido formulario y persisto en caso positivo
         */
        if ($this->getRequest()->isPost()) {

            $data = array_merge_recursive(
                    $this->getRequest()->getPost()->toArray(), $this->getRequest()->getFiles()->toArray()
            );


            $form->setData($data);

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

                $size = new \Zend\Validator\File\Size(array('max' => 50000000)); //minimum bytes filesize
                $extension = new \Zend\Validator\File\Extension(array('csv'));
                $adapter = new \Zend\File\Transfer\Adapter\Http();
                $adapter->setValidators(array($size, $extension), $File['uploadlist']);

                if (!$adapter->isValid()) {
                    $dataError = $adapter->getMessages();
                    $error = array();
                    foreach ($dataError as $key => $row) {
                        $error[] = $row;
                    }

                    $form->setMessages(array('uploadlist' => $error));
                } else {
                    $adapter->setDestination('/var/www/palermonights/App/public/media/list');
                    if ($adapter->receive($File['uploadlist'])) {
                        $newfile = $adapter->getFileName(null, true);

                        $object->setFileName($adapter->getFileName(null, false));
                        $object->setFilePath('/var/www/palermonights/App/public/media/list/');

                        $this->getEntityManager()->persist($object);
                        $this->getEntityManager()->flush();



                        $rows = str_getcsv(file_get_contents($newfile), "\n"); //parse the rows 
                        foreach ($rows as &$row) {
                            $aCsv[] = str_getcsv($row, $object->getCsvSeparator()); //parse the items in rows 
                        }

                        foreach ($aCsv[0] as $key => $value) {

                            if (preg_match('/^nombre|^name/i', trim($value))) {
                                $nKey["name"] = $key;
                            }

                            if (preg_match('/^apellido|^lastname/i', trim($value))) {
                                $nKey["lastname"] = $key;
                            }


                            if (preg_match('/^Email\sFacebook|^facebookEmail/i', trim($value))) {
                                $nKey["facebookEmail"] = $key;
                            }

                            if (preg_match('/^cumple|^birthdayText/i', trim($value))) {
                                $nKey["birthdayText"] = $key;
                            }

                            if (preg_match('/^edad|^age/i', trim($value))) {
                                $nKey["age"] = $key;
                            }
                        }
                        
                        unset($aCsv[0]);

                        foreach ($aCsv as $item) {

                            $mailingList = new \Iem\Entity\MailingList();

                            $mailingList->setName($item[$nKey["name"]]);
                            $mailingList->setFacebookEmail($item[$nKey["facebookEmail"]]);
                            $mailingList->setBirthdayText($item[$nKey["birthdayText"]]);
                            $mailingList->setLastname($item[$nKey["lastname"]]);
                            $mailingList->setAge($item[$nKey["age"]]);

                            $mailingList->setMailingListManager($object);

                            $this->getEntityManager()->persist($mailingList);
                        }
                        $this->getEntityManager()->flush();



                        $form->bind($object);
                        $persist = true;
                    }
                }
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

}

