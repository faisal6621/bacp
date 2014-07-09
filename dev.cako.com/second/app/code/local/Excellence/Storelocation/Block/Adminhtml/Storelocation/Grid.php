<?php

class Excellence_Storelocation_Block_Adminhtml_Storelocation_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('storelocationGrid');
      $this->setDefaultSort('storelocation_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('storelocation/storelocation')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('storelocation_id', array(
          'header'    => Mage::helper('storelocation')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'storelocation_id',
      ));

      $this->addColumn('storename', array(
          'header'    => Mage::helper('storelocation')->__('Store name'),
          'align'     =>'left',
          'index'     => 'storename',
      ));
	 $this->addColumn('storelocation', array(
          'header'    => Mage::helper('storelocation')->__('Region'),
          'align'     =>'left',
          'index'     => 'storelocation',
      ));
	 
    
	  
        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('storelocation')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('storelocation')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
		$this->addExportType('*/*/exportCsv', Mage::helper('storelocation')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('storelocation')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('storelocation_id');
        $this->getMassactionBlock()->setFormFieldName('storelocation');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('storelocation')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('storelocation')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('storelocation/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('storelocation')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('storelocation')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));
        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}