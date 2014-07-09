<?php

class Excellence_Cblocks_Block_Adminhtml_Links_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('linksGrid');
        $this->setDefaultSort('link_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('cblocks/links')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn('link_id', array(
            'header' => Mage::helper('cblocks')->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'link_id',
        ));

        $this->addColumn('block_id', array(
            'header' => Mage::helper('cblocks')->__('Block'),
            'align' => 'left',
            'index' => 'block_id',
            'type' => 'options',
            'options' => Excellence_Cblocks_Model_Source_Cblocks::getOptions(),
        ));

        $this->addColumn('title', array(
            'header' => Mage::helper('cblocks')->__('Title'),
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
