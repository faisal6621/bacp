<?php

class Excellence_Ordersuccesspage_Adminhtml_CartProductsController extends Mage_Adminhtml_Controller_Action {

    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('storelocation/cartproducts/items')
                ->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));

        return $this;
    }

    public function indexAction() {
        $this->_initAction();
        $this->_addContent($this->getLayout()->createBlock('ordersuccesspage/adminhtml_cartProducts'));
        $this->renderLayout();
    }

    public function productsAction() {
        $this->loadLayout();
        $this->getLayout()->getBlock('products_grid')
                ->setCartProducts($this->getRequest()->getPost('cart_products', null));
        $this->renderLayout();
    }

    public function productsGridAction() {
        $this->loadLayout();
        $this->getLayout()->getBlock('products_grid')
                ->setCartProducts($this->getRequest()->getPost('cart_products', null));
        $this->renderLayout();
    }

    public function newAction() {
        $this->_forward('edit');
    }

    public function editAction() {
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('ordersuccesspage/cartProducts')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            Mage::register('cartproducts_data', $model);

            $this->_initAction();

            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock('ordersuccesspage/adminhtml_cartProducts_edit'))
                    ->_addLeft($this->getLayout()->createBlock('ordersuccesspage/adminhtml_cartProducts_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('cblocks')->__('Item does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function saveAction() {
        $post = $this->getRequest()->getPost();
        echo '<pre>';
        print_r($post);
        print_r(Mage::helper('adminhtml/js')->decodeGridSerializedInput($post['links']['products']));
        die;
        if ($post) {
            $model = Mage::getModel('ordersuccesspage/cartProducts');
            $model->setTitle($post['title']);
            $links = $this->getRequest()->getPost('links');
            if (isset($links['products'])) {
                $model['product_ids'] = implode(',', array_keys(Mage::helper('adminhtml/js')->decodeGridSerializedInput($links['products'])));
            }
            $model->setId($this->getRequest()->getParam('id'));
            try {
                $model->save();
                $this->_getSession()->addSuccess(Mage::helper('ordersuccesspage')->__('Block saved successfully'));
                $this->_getSession()->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $ex) {
                Mage::getSingleton('adminhtml/session')->addError($ex->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($post);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ordersuccesspage')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
    }

    public function deleteAction() {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('ordersuccesspage/cartProducts');

                $model->setId($this->getRequest()->getParam('id'))
                        ->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Block was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

}
