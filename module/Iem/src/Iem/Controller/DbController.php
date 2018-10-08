<?php

/**
 *
 */

namespace Iem\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class DbController extends AbstractActionController {

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

    public function abmContactsAction() {


        $grid = $this->getServiceLocator()->get('cdiGrid');
        $aData = $this->getRequest()->getQuery();

        $source = new \CdiDataGrid\DataGrid\Source\Doctrine($this->getEntityManager(), '\Iem\Entity\Contact', $query);
        $grid->setSource($source);
        $grid->setRecordPerPage(1000);
        $grid->setCsvSemicolonOn(true);
        $grid->setCsvCommaOn(true);
        $grid->setCsvTabulatorOn(true);
        $grid->datetimeColumn('createdAt', 'Y-m-d H:i:s');
        $grid->datetimeColumn('updatedAt', 'Y-m-d H:i:s');
        $grid->hiddenColumn('createdAt');
        $grid->hiddenColumn('updatedAt');
        $grid->hiddenColumn('createdBy');
        $grid->hiddenColumn('lastUpdatedBy');

        $grid->changeColumnName('name', 'Nombre');
        $grid->changeColumnName('lastname', 'Apellido');
        $grid->changeColumnName('birthdayText', 'Cumpleaños');
        $grid->changeColumnName('age', 'Edad');
        $grid->changeColumnName('facebookId', 'ID Facebook');
        $grid->changeColumnName('facebookUrl', 'Usuario Facebook');

        $grid->changeColumnName('facebookFriendFullname', 'Amigo De');
        $grid->changeColumnName('facebookFriendUsername', 'Amigo De (Usuario)');
        $grid->changeColumnName('facebookUrl', 'Usuario Facebook');


        $grid->changeColumnName('facebookNeighborhood', 'Barrio');
        $grid->changeColumnName('facebookCountry', 'Pais');
        $grid->changeColumnName('facebookProvince', 'Provincia');
        $grid->changeColumnName('facebookCity', 'Ciudad');
        $grid->changeColumnName('facebookEmail', 'Email Facebook');


        $grid->linkColumn('facebookUrl');
        $grid->linkColumn('facebookFriendUsername');
        $grid->hiddenColumn('id');
        $grid->hiddenColumn('birthdate');
        $grid->hiddenColumn('birthdayNum');
        $grid->hiddenColumn('fullname');
        $grid->hiddenColumn('email');
        $grid->hiddenColumn('facebookUser');
        $grid->hiddenColumn('origin');


        $grid->hiddenColumn('facebookLocationName');
        $grid->hiddenColumn('facebookLocationId');
        $grid->hiddenColumn('facebookHometownName');
        $grid->hiddenColumn('facebookHometownId');


        $grid->addExtraColumn("E", "<span style='color:blue; font-size: 20px;' class='glyphicon glyphicon-edit' onclick='cdiGoEdit(\"{{id}}\")'></spam>", "left", false);
        
        //$grid->addExtraColumn("D", "<span style='color:blue; font-size: 20px;' class='glyphicon glyphicon-trash' onclick='cdiGoDelete(\"{{id}}\")'></spam>", "left", false);

        $grid->setOrderColumn("name", 2);
        $grid->setOrderColumn("lastname", 3);
        $grid->setOrderColumn("facebookEmail", 4);
        $grid->setOrderColumn("birthdayText", 5);
        $grid->setOrderColumn("facebookUrl", 6);
        $grid->setOrderColumn("age", 7);
        $grid->setOrderColumn("city", 8);
        $grid->setOrderColumn("facebookNeighborhood", 9);
        $grid->setOrderColumn("facebookCity", 10);
        $grid->setOrderColumn("facebookProvince", 11);
        $grid->setOrderColumn("facebookCountry", 12);
        $grid->setOrderColumn("E", 1);
         //  $grid->addDelOption("<i class='fa fa-trash'></i>", "right", "btn btn-warning fa fa-trash");
      
        $grid->setLimitQuery(80001);

        $grid->setTableClass("customClass");

        $return = $grid->prepare();
        if ($return) {
            // var_dump($return);
            return $return;
        }
        return array('grid' => $grid, 'fecha' => $dateTime, 'formDesdeHasta' => $formDesdeHasta);
    }
    
    

    public function editContactAction() {
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
            $object = $this->getEntityManager()->getRepository('Iem\Entity\Contact')->find($id);
            $new = false;
        } else {
            $object = new \Iem\Entity\Contact();
            $new = true;
        }

        /*
         * Declar el Formulario
         * Defino el Hidratador de Doctrine
         * Hago el Bind entre el Formulario y el objeto
         */
        $form = new \Iem\Form\Contact($this->getEntityManager());
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

    public function abmContactsFilterAction() {

        $contactFilter = new \Iem\Form\ContactFilter();
        $grid = $this->getServiceLocator()->get('cdiGrid');
        $aData = $this->getRequest()->getQuery();


        $query = $this->getEntityManager()
                ->createQueryBuilder('u')
                ->select('u')
                ->from('\Iem\Entity\Contact', 'u');


        if ($aData['fromDate'] && $aData['toDate']) {

            $contactFilter->setData($aData);
            //var_dump($aPostData);
            $expFromDate = explode("/", $aData['fromDate']);
            $fromDate = $expFromDate[1] . $expFromDate[2];

            $expToDate = explode("/", $aData['toDate']);
            $toDate = $expToDate[1] . $expToDate[2];


            if ($toDate >= $fromDate) {
                $query->andWhere("u.birthdayNum >= $fromDate and u.birthdayNum <= $toDate");
            }

            if ($toDate < $fromDate) {
                $query->andWhere("u.birthdayNum >= $fromDate and u.birthdayNum <= 1231")
                        ->andWhere("u.birthdayNum >= 0101 and u.birthdayNum <= $toDate");
            }
        }

        if ($aData['neighborhood']) {
            $query->andWhere("u.facebookNeighborhood like '" . $aData['neighborhood'] . "'");
        }

        if ($aData['city']) {
            $query->andWhere("u.facebookCity like '" . $aData['city'] . "'");
        }

        if ($aData['province']) {
            $query->andWhere("u.facebookProvince like '" . $aData['province'] . "'");
        }

        if ($aData['country']) {
            $query->andWhere("u.facebookCountry like '" . $aData['country'] . "'");
        }




        $source = new \CdiDataGrid\DataGrid\Source\Doctrine($this->getEntityManager(), '\Iem\Entity\Contact', $query);
        $grid->setSource($source);
        $grid->setRecordPerPage(100);
        $grid->setExportCsv(true);
        $grid->datetimeColumn('createdAt', 'Y-m-d H:i:s');
        $grid->datetimeColumn('updatedAt', 'Y-m-d H:i:s');
        $grid->hiddenColumn('createdAt');
        $grid->hiddenColumn('updatedAt');
        $grid->hiddenColumn('createdBy');
        $grid->hiddenColumn('lastUpdatedBy');

        $grid->changeColumnName('name', 'Nombre');
        $grid->changeColumnName('lastname', 'Apellido');
        $grid->changeColumnName('birthdayText', 'Cumpleaños');
        $grid->changeColumnName('age', 'Edad');
        $grid->changeColumnName('facebookId', 'ID Facebook');
        $grid->changeColumnName('facebookUrl', 'Usuario Facebook');

        $grid->changeColumnName('facebookFriendFullname', 'Amigo De');
        $grid->changeColumnName('facebookFriendUsername', 'Amigo De (Usuario)');
        $grid->changeColumnName('facebookUrl', 'Usuario Facebook');


        $grid->changeColumnName('facebookNeighborhood', 'Barrio');
        $grid->changeColumnName('facebookCountry', 'Pais');
        $grid->changeColumnName('facebookProvince', 'Provincia');
        $grid->changeColumnName('facebookCity', 'Ciudad');
        $grid->changeColumnName('facebookEmail', 'Email Facebook');

        $grid->linkColumn('facebookUrl');
        $grid->hiddenColumn('id');
        $grid->hiddenColumn('birthdate');
        $grid->hiddenColumn('birthdayNum');
        $grid->hiddenColumn('fullname');
        $grid->hiddenColumn('email');
        $grid->hiddenColumn('facebookUser');
        $grid->hiddenColumn('origin');
        $grid->hiddenColumn('facebookLocationName');
        $grid->hiddenColumn('facebookLocationId');
        $grid->hiddenColumn('facebookHometownName');
        $grid->hiddenColumn('facebookHometownId');


        $grid->setOrderColumn("name", 2);
        $grid->setOrderColumn("lastname", 3);
        $grid->setOrderColumn("facebookEmail", 4);
        $grid->setOrderColumn("birthdayText", 5);
        $grid->setOrderColumn("facebookUrl", 6);
        $grid->setOrderColumn("age", 7);
        $grid->setOrderColumn("city", 8);
        $grid->setOrderColumn("facebookNeighborhood", 9);
        $grid->setOrderColumn("facebookCity", 10);
        $grid->setOrderColumn("facebookProvince", 11);
        $grid->setOrderColumn("facebookCountry", 12);
        $grid->setOrderColumn("E", 1);

        $grid->setLimitQuery(80000);

        $grid->setTableClass("customClass");
        $return = $grid->prepare();
        if ($return) {
            // var_dump($return);
            return $return;
        }
        return array('grid' => $grid, 'fecha' => $dateTime, 'contactFilter' => $contactFilter);
    }

    public function abmContactsBsAsAction() {

        $contactFilter = new \Iem\Form\ContactFilter();
          $contactFilter->addNeighborhood();
        $contactFilter->get('province')->setValue("Buenos Aires");


        $grid = $this->getServiceLocator()->get('cdiGrid');
        $aData = $this->getRequest()->getQuery();

        if (!$aData['province']) {
            $aData['province'] = "Buenos Aires";
        }


        $query = $this->getEntityManager()
                ->createQueryBuilder('u')
                ->select('u')
                ->from('\Iem\Entity\Contact', 'u');


        if ($aData['fromDate'] && $aData['toDate']) {

            $contactFilter->setData($aData);
            //var_dump($aPostData);
            $expFromDate = explode("/", $aData['fromDate']);
            $fromDate = $expFromDate[1] . $expFromDate[2];

            $expToDate = explode("/", $aData['toDate']);
            $toDate = $expToDate[1] . $expToDate[2];


            if ($toDate >= $fromDate) {
                $query->andWhere("u.birthdayNum >= $fromDate and u.birthdayNum <= $toDate");
            }

            if ($toDate < $fromDate) {
                $query->andWhere("u.birthdayNum >= $fromDate and u.birthdayNum <= 1231")
                        ->andWhere("u.birthdayNum >= 0101 and u.birthdayNum <= $toDate");
            }
        }

        if ($aData['neighborhood']) {
            $query->andWhere("u.facebookNeighborhood like '" . $aData['neighborhood'] . "'");
        }

        if ($aData['city']) {
            $query->andWhere("u.facebookCity like '" . $aData['city'] . "'");
        }

        if ($aData['province']) {
            $query->andWhere("u.facebookProvince like '" . $aData['province'] . "'");
        }

        if ($aData['country']) {
            $query->andWhere("u.facebookCountry like '" . $aData['country'] . "'");
        }
        
          if ($aData['rebound'] == 1) {
             $query->andWhere("u.rebound = 1");
         }else{
             $query->andWhere("(u.rebound is null or u.rebound = 0)");
         }




        $source = new \CdiDataGrid\DataGrid\Source\Doctrine($this->getEntityManager(), '\Iem\Entity\Contact', $query);
        $grid->setSource($source);
        $grid->setRecordPerPage(500);
        $grid->setExportCsv(true);
        $grid->datetimeColumn('createdAt', 'Y-m-d H:i:s');
        $grid->datetimeColumn('updatedAt', 'Y-m-d H:i:s');
        $grid->hiddenColumn('createdAt');
        $grid->hiddenColumn('updatedAt');
        $grid->hiddenColumn('createdBy');
        $grid->hiddenColumn('lastUpdatedBy');

        $grid->changeColumnName('name', 'Nombre');
        $grid->changeColumnName('lastname', 'Apellido');
        $grid->changeColumnName('birthdayText', 'Cumpleaños');
        $grid->changeColumnName('age', 'Edad');
        $grid->changeColumnName('facebookId', 'ID Facebook');
        $grid->changeColumnName('facebookUrl', 'Usuario Facebook');

        $grid->changeColumnName('facebookFriendFullname', 'Amigo De');
        $grid->changeColumnName('facebookFriendUsername', 'Amigo De (Usuario)');
        $grid->changeColumnName('facebookUrl', 'Usuario Facebook');


        $grid->changeColumnName('facebookNeighborhood', 'Barrio');
        $grid->changeColumnName('facebookCountry', 'Pais');
        $grid->changeColumnName('facebookProvince', 'Provincia');
        $grid->changeColumnName('facebookCity', 'Ciudad');
        $grid->changeColumnName('facebookEmail', 'Email Facebook');

        $grid->linkColumn('facebookUrl');
        $grid->linkColumn('facebookFriendUsername');
        $grid->hiddenColumn('id');
        $grid->hiddenColumn('birthdate');
        $grid->hiddenColumn('birthdayNum');
        $grid->hiddenColumn('fullname');
        $grid->hiddenColumn('email');
        $grid->hiddenColumn('facebookUser');
        $grid->hiddenColumn('origin');
        $grid->hiddenColumn('facebookLocationName');
        $grid->hiddenColumn('facebookLocationId');
        $grid->hiddenColumn('facebookHometownName');
        $grid->hiddenColumn('facebookHometownId');


        $grid->setOrderColumn("name", 2);
        $grid->setOrderColumn("lastname", 3);
        $grid->setOrderColumn("facebookEmail", 4);
        $grid->setOrderColumn("birthdayText", 5);
        $grid->setOrderColumn("facebookUrl", 6);
        $grid->setOrderColumn("age", 7);
        $grid->setOrderColumn("city", 8);
        $grid->setOrderColumn("facebookNeighborhood", 9);
        $grid->setOrderColumn("facebookCity", 10);
        $grid->setOrderColumn("facebookProvince", 11);
        $grid->setOrderColumn("facebookCountry", 12);
        $grid->setOrderColumn("E", 1);

        $grid->setLimitQuery(80000);

        $grid->setTableClass("customClass");
        $return = $grid->prepare();
        if ($return) {
            // var_dump($return);
            return $return;
        }
        return array('grid' => $grid, 'fecha' => $dateTime, 'contactFilter' => $contactFilter);
    }

    public function abmContactsCabaAction() {

        $contactFilter = new \Iem\Form\ContactFilter();
        $contactFilter->addNeighborhood();

        $aData = $this->getRequest()->getQuery();
        $contactFilter->setData($aData);


        if (!$aData['fs']) {
            $neighborhood = "Vicente Lopez|Olivos|Martinez|Pilar|San Isidro|Bella Vista|";
            $contactFilter->get('neighborhood')->setValue($neighborhood);
            $contactFilter->get('city')->setValue("Ciudad Autónoma de Buenos Aires");
            $aData['city'] = "Ciudad Autónoma de Buenos Aires";
            $aData['neighborhood'] = $neighborhood;
        }

        $query = $this->getEntityManager()
                ->createQueryBuilder('u')
                ->select('u')
                ->from('\Iem\Entity\Contact', 'u');


        if ($aData['fromDate'] && $aData['toDate']) {
            $expFromDate = explode("/", $aData['fromDate']);
            $fromDate = $expFromDate[1] . $expFromDate[2];

            $expToDate = explode("/", $aData['toDate']);
            $toDate = $expToDate[1] . $expToDate[2];


            if ($toDate >= $fromDate) {
                $query->andWhere("u.birthdayNum >= $fromDate and u.birthdayNum <= $toDate");
            }

            if ($toDate < $fromDate) {
                $query->andWhere("u.birthdayNum >= $fromDate and u.birthdayNum <= 1231")
                        ->andWhere("u.birthdayNum >= 0101 and u.birthdayNum <= $toDate");
            }
        }


        if ($aData['fromAge']) {
            $fromAge = $aData['fromAge'];

            $query->andWhere("u.age >= $fromAge or u.age = ''");
        }

        if ($aData['toAge']) {
            $toAge = $aData['toAge'];

            $query->andWhere("u.age <= $toAge or u.age = ''");
        }
        
         if ($aData['iedad']) {
             $query->andWhere("u.age != ''");
         }
         
          if ($aData['rebound'] == 1) {
            $query->andWhere("u.rebound = 1");
         }else{
            $query->andWhere("(u.rebound is null or u.rebound = 0)");
         }


        $query->orderBy('u.facebookFriendFullname', "ASC");




        $where = null;

        if ($aData['neighborhood'] != "") {

            $expNeighborhood = explode("|", $aData['neighborhood']);
            if (is_array($expNeighborhood) && count($expNeighborhood) > 0) {
                foreach ($expNeighborhood as $item) {
                    if ($item != "") {
                        $where .= " or u.facebookNeighborhood like '" . $item . "'";
                    }
                }
            } else {
                $where .= " or u.facebookNeighborhood like '" . $aData['neighborhood'] . "'";
            }
        }

        if ($aData['city']) {
            $where .= " or u.facebookCity like '" . $aData['city'] . "'";
            
              $where .= " or u.facebookLocationName = 'Buenos Aires, Argentina'";
        }

        if ($aData['province']) {
            $where .= " or u.facebookProvince like '" . $aData['province'] . "'";
        }

        if ($aData['country']) {
            $where .= " or u.facebookCountry like '" . $aData['country'] . "'";
        }
        
        
        

        $where = substr($where, 3);
        //echo $where;
        if ($where) {
            $query->andWhere($where);
        }
        
        //echo $query->getQuery()->getSql();
        
        $grid = $this->getServiceLocator()->get('cdiGrid');
        $source = new \CdiDataGrid\DataGrid\Source\Doctrine($this->getEntityManager(), '\Iem\Entity\Contact', $query);
        $grid->setSource($source);
        $grid->setRecordPerPage(500);
        $grid->setExportCsv(true);

//        $grid->setCsvSemicolonOn(true);
//        $grid->setCsvCommaOn(true);
//        $grid->setCsvTabulatorOn(true);

        $grid->datetimeColumn('createdAt', 'Y-m-d H:i:s');
        $grid->datetimeColumn('updatedAt', 'Y-m-d H:i:s');
        $grid->hiddenColumn('createdAt');
        $grid->hiddenColumn('updatedAt');
        $grid->hiddenColumn('createdBy');
        $grid->hiddenColumn('lastUpdatedBy');
        
          $grid->booleanColumn("rebound",'si',"no");

        $grid->changeColumnName('name', 'Nombre');
        $grid->changeColumnName('lastname', 'Apellido');
        $grid->changeColumnName('birthdayText', 'Cumpleaños');
        $grid->changeColumnName('age', 'Edad');
        $grid->changeColumnName('facebookId', 'ID Facebook');
        $grid->changeColumnName('facebookUrl', 'Usuario Facebook');

        $grid->changeColumnName('facebookFriendFullname', 'Amigo De');
        $grid->changeColumnName('facebookFriendUsername', 'Amigo De (Usuario)');
        $grid->changeColumnName('facebookUrl', 'Usuario Facebook');


        $grid->changeColumnName('facebookNeighborhood', 'Barrio');
        $grid->changeColumnName('facebookCountry', 'Pais');
        $grid->changeColumnName('facebookProvince', 'Provincia');
        $grid->changeColumnName('facebookCity', 'Ciudad');
        $grid->changeColumnName('facebookEmail', 'Email Facebook');

        $grid->linkColumn('facebookFriendUsername');
        $grid->linkColumn('facebookUrl');
        $grid->hiddenColumn('id');
        $grid->hiddenColumn('birthdate');
        $grid->hiddenColumn('birthdayNum');
        $grid->hiddenColumn('fullname');
        $grid->hiddenColumn('email');
        $grid->hiddenColumn('facebookUser');
        $grid->hiddenColumn('origin');
        $grid->hiddenColumn('facebookLocationName');
        $grid->hiddenColumn('facebookLocationId');
        $grid->hiddenColumn('facebookHometownName');
        $grid->hiddenColumn('facebookHometownId');


        $grid->setOrderColumn("name", 2);
        $grid->setOrderColumn("lastname", 3);
        $grid->setOrderColumn("facebookEmail", 4);
        $grid->setOrderColumn("birthdayText", 5);
        $grid->setOrderColumn("facebookUrl", 6);
        $grid->setOrderColumn("age", 7);
        $grid->setOrderColumn("city", 8);
        $grid->setOrderColumn("facebookNeighborhood", 9);
        $grid->setOrderColumn("facebookCity", 10);
        $grid->setOrderColumn("facebookProvince", 11);
        $grid->setOrderColumn("facebookCountry", 12);
        $grid->setOrderColumn("E", 1);

        $grid->setLimitQuery(80000);

        $grid->setTableClass("customClass");
        $return = $grid->prepare();
        if ($return) {
            // var_dump($return);
            return $return;
        }
        return array('grid' => $grid, 'fecha' => $dateTime, 'contactFilter' => $contactFilter);
    }
     
    //NEWS
    
     public function abmContactsNewAction() {


        $grid = $this->getServiceLocator()->get('cdiGrid');
        $aData = $this->getRequest()->getQuery();

        $source = new \CdiDataGrid\DataGrid\Source\Doctrine($this->getEntityManager(), '\Iem\Entity\ContactNew', $query);
        $grid->setSource($source);
        $grid->setRecordPerPage(1000);
        $grid->setCsvSemicolonOn(true);
        $grid->setCsvCommaOn(true);
        $grid->setCsvTabulatorOn(true);
        $grid->datetimeColumn('createdAt', 'Y-m-d H:i:s');
        $grid->datetimeColumn('updatedAt', 'Y-m-d H:i:s');
        $grid->hiddenColumn('createdAt');
        $grid->hiddenColumn('updatedAt');
        $grid->hiddenColumn('createdBy');
        $grid->hiddenColumn('lastUpdatedBy');

        $grid->changeColumnName('name', 'Nombre');
        $grid->changeColumnName('lastname', 'Apellido');
        $grid->changeColumnName('birthdayText', 'Cumpleaños');
        $grid->changeColumnName('age', 'Edad');
        $grid->changeColumnName('facebookId', 'ID Facebook');
        $grid->changeColumnName('facebookUrl', 'Usuario Facebook');

        $grid->changeColumnName('facebookFriendFullname', 'Amigo De');
        $grid->changeColumnName('facebookFriendUsername', 'Amigo De (Usuario)');
        $grid->changeColumnName('facebookUrl', 'Usuario Facebook');


        $grid->changeColumnName('facebookNeighborhood', 'Barrio');
        $grid->changeColumnName('facebookCountry', 'Pais');
        $grid->changeColumnName('facebookProvince', 'Provincia');
        $grid->changeColumnName('facebookCity', 'Ciudad');
        $grid->changeColumnName('facebookEmail', 'Email Facebook');


        $grid->linkColumn('facebookUrl');
        $grid->linkColumn('facebookFriendUsername');
        $grid->hiddenColumn('id');
        $grid->hiddenColumn('birthdate');
        $grid->hiddenColumn('birthdayNum');
        $grid->hiddenColumn('fullname');
        $grid->hiddenColumn('email');
        $grid->hiddenColumn('facebookUser');
        $grid->hiddenColumn('origin');


        //$grid->hiddenColumn('facebookLocationName');
        $grid->hiddenColumn('facebookLocationId');
        //$grid->hiddenColumn('facebookHometownName');
        $grid->hiddenColumn('facebookHometownId');


        $grid->addExtraColumn("E", "<span style='color:blue; font-size: 20px;' class='glyphicon glyphicon-edit' onclick='cdiGoEdit(\"{{id}}\")'></spam>", "left", false);
        
        //$grid->addExtraColumn("D", "<span style='color:blue; font-size: 20px;' class='glyphicon glyphicon-trash' onclick='cdiGoDelete(\"{{id}}\")'></spam>", "left", false);

        $grid->setOrderColumn("name", 2);
        $grid->setOrderColumn("lastname", 3);
        $grid->setOrderColumn("facebookEmail", 4);
        $grid->setOrderColumn("birthdayText", 5);
        $grid->setOrderColumn("facebookUrl", 6);
        $grid->setOrderColumn("age", 7);
        $grid->setOrderColumn("city", 8);
        $grid->setOrderColumn("facebookNeighborhood", 9);
        $grid->setOrderColumn("facebookCity", 10);
        $grid->setOrderColumn("facebookProvince", 11);
        $grid->setOrderColumn("facebookCountry", 12);
          $grid->setOrderColumn("facebookLocationName", 13);
        $grid->setOrderColumn("facebookHometownName", 14);
        $grid->setOrderColumn("E", 1);
         //  $grid->addDelOption("<i class='fa fa-trash'></i>", "right", "btn btn-warning fa fa-trash");
      
        $grid->setLimitQuery(80001);

        $grid->setTableClass("customClass");

        $return = $grid->prepare();
        if ($return) {
            // var_dump($return);
            return $return;
        }
        return array('grid' => $grid, 'fecha' => $dateTime, 'formDesdeHasta' => $formDesdeHasta);
    }
    
     public function abmContactsLoginAction() {


        $grid = $this->getServiceLocator()->get('cdiGrid');
        $aData = $this->getRequest()->getQuery();

        $source = new \CdiDataGrid\DataGrid\Source\Doctrine($this->getEntityManager(), '\Iem\Entity\ContactLogin', $query);
        $grid->setSource($source);
        $grid->setRecordPerPage(1000);
        $grid->setCsvSemicolonOn(true);
        $grid->setCsvCommaOn(true);
        $grid->setCsvTabulatorOn(true);
        $grid->datetimeColumn('createdAt', 'Y-m-d H:i:s');
        $grid->datetimeColumn('updatedAt', 'Y-m-d H:i:s');
        $grid->hiddenColumn('createdAt');
        $grid->hiddenColumn('updatedAt');
        $grid->hiddenColumn('createdBy');
        $grid->hiddenColumn('lastUpdatedBy');

        $grid->changeColumnName('name', 'Nombre');
        $grid->changeColumnName('lastname', 'Apellido');
        $grid->changeColumnName('birthdayText', 'Cumpleaños');
        $grid->changeColumnName('age', 'Edad');
        $grid->changeColumnName('facebookId', 'ID Facebook');
        $grid->changeColumnName('facebookUrl', 'Usuario Facebook');

        $grid->changeColumnName('facebookFriendFullname', 'Amigo De');
        $grid->changeColumnName('facebookFriendUsername', 'Amigo De (Usuario)');
        $grid->changeColumnName('facebookUrl', 'Usuario Facebook');


        $grid->changeColumnName('facebookNeighborhood', 'Barrio');
        $grid->changeColumnName('facebookCountry', 'Pais');
        $grid->changeColumnName('facebookProvince', 'Provincia');
        $grid->changeColumnName('facebookCity', 'Ciudad');
        $grid->changeColumnName('facebookEmail', 'Email Facebook');


        $grid->linkColumn('facebookUrl');
        $grid->linkColumn('facebookFriendUsername');
        $grid->hiddenColumn('id');
        $grid->hiddenColumn('birthdate');
        $grid->hiddenColumn('birthdayNum');
        $grid->hiddenColumn('fullname');
        $grid->hiddenColumn('email');
        $grid->hiddenColumn('facebookUser');
        $grid->hiddenColumn('origin');


        //$grid->hiddenColumn('facebookLocationName');
        $grid->hiddenColumn('facebookLocationId');
        //$grid->hiddenColumn('facebookHometownName');
        $grid->hiddenColumn('facebookHometownId');


        $grid->addExtraColumn("E", "<span style='color:blue; font-size: 20px;' class='glyphicon glyphicon-edit' onclick='cdiGoEdit(\"{{id}}\")'></spam>", "left", false);
        
        //$grid->addExtraColumn("D", "<span style='color:blue; font-size: 20px;' class='glyphicon glyphicon-trash' onclick='cdiGoDelete(\"{{id}}\")'></spam>", "left", false);

        $grid->setOrderColumn("name", 2);
        $grid->setOrderColumn("lastname", 3);
        $grid->setOrderColumn("facebookEmail", 4);
        $grid->setOrderColumn("birthdayText", 5);
        $grid->setOrderColumn("facebookUrl", 6);
        $grid->setOrderColumn("age", 7);
        $grid->setOrderColumn("city", 8);
        $grid->setOrderColumn("facebookNeighborhood", 9);
        $grid->setOrderColumn("facebookCity", 10);
        $grid->setOrderColumn("facebookProvince", 11);
        $grid->setOrderColumn("facebookCountry", 12);
          $grid->setOrderColumn("facebookLocationName", 13);
        $grid->setOrderColumn("facebookHometownName", 14);
        $grid->setOrderColumn("E", 1);
         //  $grid->addDelOption("<i class='fa fa-trash'></i>", "right", "btn btn-warning fa fa-trash");
      
        $grid->setLimitQuery(80001);

        $grid->setTableClass("customClass");

        $return = $grid->prepare();
        if ($return) {
            // var_dump($return);
            return $return;
        }
        return array('grid' => $grid, 'fecha' => $dateTime, 'formDesdeHasta' => $formDesdeHasta);
    }

}

