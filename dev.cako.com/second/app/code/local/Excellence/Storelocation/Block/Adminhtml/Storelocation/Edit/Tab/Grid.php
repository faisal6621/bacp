<?php
class Excellence_Storelocation_Block_Adminhtml_Storelocation_Edit_Tab_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct() {

		parent::__construct();
		$this->setId('products_grid');
		$this->setDefaultSort('entity_id');
		$this->setDefaultDir('ASC');

		$this->setUseAjax(true);
		if ((int) $this->getRequest()->getParam('id', 0)) {
			$this->setDefaultFilter(array('in_products' => 1));
		}
	}
	protected function _prepareCollection()
	{
		$collection = Mage::getModel('catalog/product')
		->getCollection()
		->addAttributeToFilter('visibility', 4)
		->addAttributeToSelect('*');
		 
		//echo "<pre>";
		//print_r($collection->getData());
		//die;
		$this->setCollection($collection);

		parent::_prepareCollection();
		$this->getCollection()->addWebsiteNamesToResult();
		return $this;
	}

	protected function _addColumnFilterToCollection($column)
	{
		// Set custom filter for in product flag
		if ($column->getId() == 'in_products') {
			$productIds = $this->_getSelectedProducts();
			if (empty($productIds)) {
				$productIds = 0;
			}
			if ($column->getFilter()->getValue()) {
				$this->getCollection()->addFieldToFilter('entity_id', array('in'=>$productIds));
			} else {
				if($productIds) {
					$this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$productIds));
				}
			}
		} else {
			parent::_addColumnFilterToCollection($column);
		}
		return $this;
	}

	protected function _prepareColumns() {
		$this->addColumn('in_products', array(
            'header_css_class' => 'a-center',
            'type' => 'checkbox',
            'name' => 'in_products',
            'values' => $this->_getSelectedProducts(),
            'align' => 'center',
            'index' => 'entity_id'
            ));
            $this->addColumn('entity_id', array(
            'header'    => Mage::helper('storelocation')->__('ID'),
            'type' => 'text',
            'name' => 'entity_id',
            'align' => 'left',
            'index' => 'entity_id'
            ));
            $this->addColumn('name', array(
            'header'    => Mage::helper('storelocation')->__('Name'),
            'type' => 'text',
            'name' => 'name',
            'align' => 'left',
            'index' => 'name'
            ));
           $this->addColumn('position', array(
        'header'            => Mage::helper('storelocation')->__('Position'),
        'name'              => 'position',
        'width'             => 60,
        'type'              => 'number',
        'validate_class'    => 'validate-number',
        'index'             => 'position',
        'editable'          => true,
        'edit_only'         => true
        ));

	}
	protected function _getSelectedProducts() {
		$products = $this->getRequest()->getPost('products_prodlist', null);
		if (!is_array($products)) {
			$products = array_keys($this->getSelectedProducts());
		}
		return $products;
	}

	public function getSelectedProducts() {
		$products = array();
		$tm_id = $this->getRequest()->getParam('id');
		$productload = Mage::getModel('storelocation/storeproduct')->load($tm_id,"store_id");
		$products=explode(",",$productload->getEntityId());
		$prodtIds = array();

		foreach($products as $product) {

			$prodIds[$product] = $product;

		}
		return $prodIds;
	}

 public function getGridUrl()
    {
        return $this->_getData('grid_url') ? $this->_getData('grid_url') : $this->getUrl('*/*/products', array('_current'=>true));
    }
	 
	 

}
?>