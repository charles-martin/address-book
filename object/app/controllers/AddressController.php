<?php

    /**
     * Created by PhpStorm.
     * User: Charles.Martin
     * Date: 8/11/2015
     * Time: 10:23 AM
     */

    use Phalcon\Mvc\Controller as pController;

    class AddressController  extends pController {
        public function indexAction($iPage = 1) {
            $this->view->setVar('url', '/address/index/{page}');
            $this->view->setVar('page', $iPage);
            $this->view->setVar('results', Address::find());
        }

        public function searchAction($sSearch = '', $iPage = 1) {
            $sSearch = $sSearch ?: $_GET['search'];
            $this->view->pick('Address/index');
            $this->view->setVar('search' , $sSearch);
            $this->view->setVar('results', $this->_buildSearch($sSearch)->execute());
            $this->view->setVar('page'   , $iPage);
            $this->view->setVar('url'    , '/address/search/' . urlencode($sSearch) . '/{page}/');
        }

        public function addAction() {
            $mdAddress = new Address();
            if ($this->session->get('data')) {
                $mdAddress->assign($this->session->get('data'));
            }
            $this->view->pick('Address/edit');
            $this->view->setVar('address', $mdAddress);
        }

        public function editAction($id) {
            $mdAddress = Address::findFirstById($id);
            if ($this->session->get('data')) {
                $mdAddress->assign($this->session->get('data'));
            }

            $this->view->setVar('address', $mdAddress);
        }

        public function deleteAction($id) {
            $mdAddress = Address::findFirstById($id);
            if ($mdAddress) {
                $mdAddress->delete();
            }

            $this->dispatcher->forward(['controller' => 'address', 'action' => 'index']);
        }

        public function saveAction($id) {
            $id = (int) $id;
            $mdAddress = new Address();
            if ($id > 0) {
                $mdAddress = Address::findFirstById($id);
            }

            $mdAddress->assign($_POST);

            $id == 0 ? $mdAddress->create() : $mdAddress->save();

            if ($mdAddress->getMessages()) {
                foreach($mdAddress->getMessages() as $message) {
                    $this->flash->error($message->getMessage());
                }
                $this->session->set('data', $_POST);
                $this->response->redirect("/address/" . (($id != 0) ?  'edit/' . $id . '/' : 'add'));
                $this->view->disable();
                return;
            }

            $this->dispatcher->forward(['controller' => 'address', 'action' => 'index']);
        }

        protected function _buildSearch($sSearch) {
            $crSearch = Address::query()
                ->andWhere('name LIKE :search:', ['search' => '%' . $sSearch . '%']);

            return $crSearch;
        }
    }