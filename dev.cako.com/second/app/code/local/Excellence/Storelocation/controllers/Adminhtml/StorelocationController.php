<?php

class Excellence_Storelocation_Adminhtml_StorelocationController extends Mage_Adminhtml_Controller_action {

    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('storelocation/items')
                ->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));

        return $this;
    }

    public function indexAction() {
        $this->_initAction()
                ->renderLayout();
    }

    public function editAction() {
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('storelocation/storelocation')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            Mage::register('storelocation_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('storelocation/items');

            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock('storelocation/adminhtml_storelocation_edit'))
                    ->_addLeft($this->getLayout()->createBlock('storelocation/adminhtml_storelocation_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('storelocation')->__('Item does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function newAction() {
        $this->_forward('edit');
    }

    public function saveAction() {
        if ($data = $this->getRequest()->getPost()) {
            //	echo "<pre>";
            //	print_r($data);
            //	die;
            #upload gallery images
            $imgs = array();
            if (isset($_FILES['storelocations1']['name']) && !empty($_FILES['storelocations1']['name'])) {
                foreach ($_FILES['storelocations1']['name'] as $key => $image) {
                    if (empty($image)) {
                        continue;
                    }
                    try {
                        $uploader = new Varien_File_Uploader(
                                array(
                            'name' => $_FILES['storelocations1']['name'][$key],
                            'type' => $_FILES['storelocations1']['type'][$key],
                            'tmp_name' => $_FILES['storelocations1']['tmp_name'][$key],
                            'error' => $_FILES['storelocations1']['error'][$key],
                            'size' => $_FILES['storelocations1']['size'][$key]
                                )
                        );

                        $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                        $uploader->setAllowRenameFiles(true);

                        $uploader->setFilesDispersion(false);

                        $path = Mage::getBaseDir('media') . DS;
                        $img = $uploader->save($path, $_FILES['storelocations1']['name'][$key]);
                        $imgs[] = $img['file'];
                    } catch (Exception $e) {
                        Mage::log($e->getMessage());
                    }
                }
            }
            // delete images from gallery
            if (isset($data['delimg']) && !empty($data['delimg'])) {
                foreach ($data['delimg'] as $delid) {
                    $del = Mage::getModel('storelocation/storeimages')->load($delid);
                    unlink(Mage::getBaseDir('media') . DS . $del->getImage());
                    try {
                        $del->delete();
                    } catch (Exception $ex) {
                        Mage::log($ex->getMessage());
                    }
                }
            }
            $eid = $this->getRequest()->getParam('id');
            #set default image
            if (isset($data['baseimage'])) {

                $baseimage = $data['baseimage'];

                $coreResource = Mage::getSingleton('core/resource');
                $conn = $coreResource->getConnection('core_write');
                $conn->update(
                        $coreResource->getTableName('storelocation/storeimages'), array('def_image' => '0'), array(
                    'storelocation_id = ?' => $eid,
                        )
                );
                $conn->commit();
                $setbase = Mage::getModel('storelocation/storeimages')->load($baseimage);
                $setbase->setDefImage(1);
                $setbase->save();
            }

            //$data['storeimages1'] = implode(',',$imgs);
            $model = Mage::getModel('storelocation/storelocation');
            $model->setData($data)
                    ->setId($this->getRequest()->getParam('id'));

            try {
                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('storelocation')->__('Item was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                $i = 0;
                foreach ($imgs as $v) {

                    $imgModel = Mage::getModel('storelocation/storeimages');
                    $imgModel->setStorelocationId($model->getId());
                    if ($eid == '' && $i == 0) {
                        $imgModel->setDefImage(1);
                    }
                    $imgModel->setImage($v);
                    $imgModel->save();
                    $i++;
                }
                #add f products

                if (isset($data['links'])) {
                    $products = implode(",", Mage::helper('adminhtml/js')->decodeGridSerializedInput($data['links']['prodlist']));
                }

                $storeprod = Mage::getModel('storelocation/storeproduct')->load($model->getId(), "store_id");
                if ($storeprod) {
                    $storeprod->setProductId($products)
                            ->save();
                } else {

                    $storeprod
                            ->setStoreId($model->getId())
                            ->setProductId($products)
                            ->save();
                }



                #create url rewrite
                $targetpath = "store-locations/index/view/id/" . $model->getId() . "/";

                $reqpath = "store-locations/" . $data['googlemapapi'] . ".html";
                $id_path = "store_" . $model->getId();


                $delurl = Mage::getModel('core/url_rewrite')->load($targetpath, "target_path");
                if ($delurl->getUrlRewrite()) {
                    $delurl->setRequestPath($reqpath);
                    $delurl->setIdPath($id_path);
                    $delurl->setIsSystem(0);
                } else {
                    $delurl->setRequestPath($reqpath);
                    $delurl->setIdPath($id_path);
                    $delurl->setIsSystem(0);
                    $delurl->setTargetPath($targetpath);
                }
                $delurl->save();

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('storelocation')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
    }

    public function deleteAction() {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $del = Mage::getModel('storelocation/storeimages')->getCollection()
                        ->addFieldToFilter('storelocation_id', $this->getRequest()->getParam('id'));
                foreach ($del as $w) {
                    unlink(Mage::getBaseDir('media') . DS . $w->getImage());
                }

                foreach ($del as $e) {
                    $storelocation = Mage::getModel('storelocation/storeimages')->load($e->getImageId());
                    $storelocation->delete();
                }

                $model = Mage::getModel('storelocation/storelocation');

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
        $storelocationIds = $this->getRequest()->getParam('storelocation');
        if (!is_array($storelocationIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($storelocationIds as $storelocationId) {
                    $storelocation = Mage::getModel('storelocation/storelocation')->load($storelocationId);
                    $storelocation->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('adminhtml')->__(
                                'Total of %d record(s) were successfully deleted', count($storelocationIds)
                        )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massStatusAction() {
        $storelocationIds = $this->getRequest()->getParam('storelocation');
        if (!is_array($storelocationIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($storelocationIds as $storelocationId) {
                    $storelocation = Mage::getSingleton('storelocation/storelocation')
                            ->load($storelocationId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                        $this->__('Total of %d record(s) were successfully updated', count($storelocationIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function exportCsvAction() {
        $fileName = 'storelocation.csv';
        $content = $this->getLayout()->createBlock('storelocation/adminhtml_storelocation_grid')
                ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction() {
        $fileName = 'storelocation.xml';
        $content = $this->getLayout()->createBlock('storelocation/adminhtml_storelocation_grid')
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

    public function productsAction() {
        $this->loadLayout();
        $this->getLayout()->getBlock('products.grid')
                ->setProdlist($this->getRequest()->getPost('products_prodlist', null));
        $this->renderLayout();
    }

    public function productsgridAction() {
        $this->loadLayout();
        $this->getLayout()->getBlock('products.grid')
                ->setProdlist($this->getRequest()->getPost('products_prodlist', null));
        $this->renderLayout();
    }

}
