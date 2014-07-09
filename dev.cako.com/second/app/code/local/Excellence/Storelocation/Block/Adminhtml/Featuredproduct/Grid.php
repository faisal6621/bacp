<?php

class Excellence_Storelocation_Block_Adminhtml_Featuredproduct_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('featuredproductGrid');
      $this->setDefaultSort('featuredproduct_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
      
  }

  protected function _prepareCollection()
  {
  
  	$collection = Mage::getModel('storelocation/featuredproduct')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('featuredproduct_id', array(
          'header'    => Mage::helper('storelocation')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'featuredproduct_id',
      ));

      $this->addColumn('title', array(
          'header'    => Mage::helper('storelocation')->__('Title'),
          'align'     =>'left',
          'index'     => 'title',
      ));

	  
      $this->addColumn('ranking', array(
			'header'    => Mage::helper('storelocation')->__('Ranking'),
			'width'     => '150px',
			'index'     => 'ranking',
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
        $this->setMassactionIdField('featuredproduct_id');
        $this->getMassactionBlock()->setFormFieldName('featuredproduct');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('storelocation')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('storelocation')->__('Are you sure?')
        ));

        
        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}