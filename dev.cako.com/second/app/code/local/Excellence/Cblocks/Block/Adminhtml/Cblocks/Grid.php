<?php

class Excellence_Cblocks_Block_Adminhtml_Cblocks_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('cblocksGrid');
        $this->setDefaultSort('cblocks_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('cblocks/cblocks')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn('cblocks_id', array(
            'header' => Mage::helper('cblocks')->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'cblocks_id',
        ));

        $this->addColumn('title', array(
            'header' => Mage::helper('cblocks')->__('Block Title'),
            'align' => 'left',
            'index' => 'title',
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
