<?php

class Excellence_Cblocks_Block_Adminhtml_Images_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('imagesGrid');
        $this->setDefaultSort('image_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('cblocks/images')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn('image_id', array(
            'header' => Mage::helper('cblocks')->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'image_id',
        ));

        $this->addColumn('image', array(
            'header' => Mage::helper('cblocks')->__('Image'),
            'align' => 'left',
            'index' => 'image',
            'type' => 'image',
            'renderer' => 'cblocks/adminhtml_images_renderer_image'
        ));

        $this->addColumn('title', array(
            'header' => Mage::helper('cblocks')->__('Image Title'),
            'align' => 'left',
            'index' => 'title',
        ));

        $this->addColumn('link', array(
            'header' => Mage::helper('cblocks')->__('Link'),
            'index' => 'link',
        ));

        $this->addColumn('action', array(
            'header' => Mage::helper('cblocks')->__('Action'),
            'width' => '100',
            'type' => 'action',
            'getter' => 'getId',
            'actions' => array(
                array(
                    'caption' => Mage::helper('cblocks')->__('Edit'),
                    'url' => array('base' => '*/*/edit'),
                    'field' => 'id'
                )
            ),
            'filter' => false,
            'sortable' => false,
            'index' => 'stores',
            'is_system' => true,
        ));
        return parent::_prepareColumns();
    }

    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

}
