<?php

class Excellence_Posimg_Adminhtml_PosimgController extends Mage_Adminhtml_Controller_action {

    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('posimg/items')
                ->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));

        return $this;
    }

    public function indexAction() {
        $this->_initAction()
                ->renderLayout();
    }

    public function editAction() {
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('posimg/posimg')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            Mage::register('posimg_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('posimg/items');

            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock('posimg/adminhtml_posimg_edit'))
                    ->_addLeft($this->getLayout()->createBlock('posimg/adminhtml_posimg_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('posimg')->__('Item does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function newAction() {
        $this->_forward('edit');
    }

    public function saveAction() {
        $skip = $this->getRequest()->getParam('skip');
        $products = Mage::getModel('catalog/product')->getCollection()
                ->addAttributeToFilter('visibility', array('eq' => Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH))
                ->addAttributeToFilter('status', array('eq' => Mage_Catalog_Model_Product_Status::STATUS_ENABLED))
        ;
        $dst = Mage::getBaseDir('media') . DS . 'products195';
        if (!file_exists($dst)) {
            if (!mkdir($dst, 0777, true)) {
                Mage::log('failed mkdir: ' . $dst);
                $this->_getSession()->addError('failed mkdir: ' . $dst);
                $this->_redirectReferer();
                return;
            }
        }
        $csv[] = array(
            'sku' => 'SKU',
            'product' => 'Product Name',
            'thumbnail' => 'URL'
        );
        $i = 0;
//        echo '<pre>';
        foreach ($products as $product) {
            $_product = Mage::getModel('catalog/product')->load($product->getId());
            $images = $_product->getMediaGallery();
            $img = '';
            if (count($images['images']) > 1) {
                if ($_product->getImage() != 'no_selection') {
                    $img = $_product->getImage(); # . '<br/>';
                } else {
                    if ($images['images'][0]['file']) {
                        $img = $images['images'][0]['file']; # . '<br/>';
                    }
                }
            } else {
                $img = $images['images'][0]['file']; # . '<br/>';
            }
//            echo sprintf('%5s', ++$i) . '. '
//            . $_product->getId() . ': '
//            . $_product->getName() . ' - '
//            . $_product->getSku() . '<br/>'
//            . '<img src="' . Mage::getBaseUrl('media') . 'catalog/product' . $img . '" width="195" height="195"/><br/>';
            if ($img) {
                ++$i;
                $src = Mage::getBaseDir('media') . DS . 'catalog' . DS . 'product' . $img;
                if (file_exists($src)) {
                    $ext = explode('.', $img);
                    if ($skip && file_exists($dst . DS . $_product->getSku() . '.' . end($ext))) {
                        $csv[] = array(
                            'sku' => $_product->getSku(),
                            'product' => $_product->getName(),
                            'thumbnail' => Mage::getBaseUrl('media') . 'products195/' . $_product->getSku() . '.' . end($ext),
//                        'thumbnail' => $dst . DS . $_product->getSku() . '.' . end($ext),
                        );
                    } else {
                        $thumb = new Varien_Image($src);
                        $thumb->constrainOnly(false);
                        $thumb->keepFrame(false);
                        $thumb->keepAspectRatio(true);
                        $thumb->resize(195, 195);
                        $thumb->save($dst . DS . $_product->getSku() . '.' . end($ext));
                        $csv[] = array(
                            'sku' => $_product->getSku(),
                            'product' => $_product->getName(),
                            'thumbnail' => Mage::getBaseUrl('media') . 'products195/' . $_product->getSku() . '.' . end($ext),
//                        'thumbnail' => $dst . DS . $_product->getSku() . '.' . end($ext),
                        );
                    }
//                    echo sprintf('%5s', $i) . '. <img src="' . Mage::getBaseUrl('media') . 'posimg/' . $_product->getSku() . '.' . end($ext) . '" alt="_blank"/>' . /* $_product->getName() . */ '<br/>';
                }
            } else {
                ++$i;
//                echo '<br/><br/>';
            }
//            print_r($_product->debug());
//            die;
        }
//        print_r($csv);
//        foreach ($csv as $entry) {
//            printf('%15s   %-50s %-150s <br/>', $entry['sku'], $entry['product'], $entry['thumbnail']);
//        }
        $csvPath = $dst . DS . 'posimgs.csv';
        try {
            $poscsv = new Varien_File_Csv();
            $poscsv->saveData($csvPath, $csv);
            $this->_redirectUrl(Mage::getBaseUrl('media') . 'products195/posimgs.csv');
        } catch (Exception $ex) {
            $this->_getSession()->addError($ex->getMessage());
            $this->_redirectReferer();
            return;
        }
        return;
    }

//    public function customerListAction() {
////        $fromdate = date('Y-m-01 00:00:00', Mage::getModel('core/date')->timestamp(time()));
////        $todate = date('Y-m-t 23:59:59', Mage::getModel('core/date')->timestamp(time()));
//        $fromdate = date('Y-m-01 00:00:00', Mage::getModel('core/date')->timestamp(strtotime('-1 month') + $this->getTimezoneOffset()));
//        $todate = date('Y-m-t 23:59:59', Mage::getModel('core/date')->timestamp(strtotime('-1 month') + $this->getTimezoneOffset()));
//        $csvData[] = array(
//            'company' => 'Company Name',
//            'firstname' => 'First Name',
//            'lastname' => 'Last Name',
//            'address' => 'Address',
//            'phone' => 'Phone',
//        );
//        $customers = Mage::getModel('customer/customer')->getCollection()
//        ;
////        echo implode('<br/>', $customers->getColumnValues('entity_id'));
//        $orders = Mage::getModel('sales/order')->getCollection()
//                ->addAttributeToFilter('customer_id', array(
//                    'in' => implode(',', $customers->getColumnValues('entity_id')))
//                )
//                ->addAttributeToFilter('created_at', array(
//            'from' => $fromdate,
//            'to' => $todate,
//                ))
//        ;
////        echo '<pre>';
////        echo $orders->getSelect().'<br/>';
////        echo implode('<br/>', $orders->getColumnValues('increment_id'));
//        foreach ($orders as $order) {
//            $shipping = $order->getShippingAddress();
//            $street = $shipping->getStreet1() . ', ' . $shipping->getStreet2() . ', ';
////            print_r($shipping->getData());
//            $address = str_replace(' ,', '', $street) . $shipping->getCity() . ', ' .
//                    $shipping->getRegion() . ', ' .
//                    Mage::getModel('directory/country')->loadByCode($shipping->getCountryId())->getName() . ', ' .
//                    $shipping->getPostcode();
//            $csvData[] = array(
//                'company' => $shipping->getCompany(),
//                'firstname' => $shipping->getFirstname(),
//                'lastname' => $shipping->getLastname(),
//                'address' => $address,
//                'phone' => $shipping->getTelephone(),
//            );
//        }
//
//        $dst = Mage::getBaseDir('media') . DS . 'posimg';
//        if (!file_exists($dst)) {
//            if (!mkdir($dst, 0777, true)) {
//                Mage::log('failed mkdir: ' . $dst);
//                $this->_getSession()->addError('failed mkdir: ' . $dst);
//                $this->_redirectReferer();
//                return;
//            }
//        }
//        $csvPath = $dst . DS . 'customer_list.csv';
//        try {
//            $poscsv = new Varien_File_Csv();
//            $poscsv->saveData($csvPath, $csvData);
//            $this->_redirectUrl(Mage::getBaseUrl('media') . 'posimg/customer_list.csv');
//        } catch (Exception $ex) {
//            $this->_getSession()->addError($ex->getMessage());
//            $this->_redirectReferer();
//            return;
//        }
//        return;
//    }
//
//    public function getTimezoneOffset() {
//        $timezone = Mage::app()->getStore()->getConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_TIMEZONE);
//
//        $timezone_offset = Mage::getModel('core/date')->calculateOffset($timezone);
//        return $timezone_offset;
//    }

    public function deleteAction() {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('posimg/posimg');

                $model->setId($this->getRequest()->getParam('id'))
                        ->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction() {
        $posimgIds = $this->getRequest()->getParam('posimg');
        if (!is_array($posimgIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($posimgIds as $posimgId) {
                    $posimg = Mage::getModel('posimg/posimg')->load($posimgId);
                    $posimg->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('adminhtml')->__(
                                'Total of %d record(s) were successfully deleted', count($posimgIds)
                        )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massStatusAction() {
        $posimgIds = $this->getRequest()->getParam('posimg');
        if (!is_array($posimgIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($posimgIds as $posimgId) {
                    $posimg = Mage::getSingleton('posimg/posimg')
                            ->load($posimgId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                        $this->__('Total of %d record(s) were successfully updated', count($posimgIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function exportCsvAction() {
        $fileName = 'posimg.csv';
        $content = $this->getLayout()->createBlock('posimg/adminhtml_posimg_grid')
                ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction() {
        $fileName = 'posimg.xml';
        $content = $this->getLayout()->createBlock('posimg/adminhtml_posimg_grid')
                ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType = 'application/octet-stream') {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK', '');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename=' . $fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }

}
