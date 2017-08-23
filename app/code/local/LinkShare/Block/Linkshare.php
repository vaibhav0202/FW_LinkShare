<?php
class FW_LinkShare_Block_Linkshare extends Mage_Core_Block_Text
{	
	/**
	 * Render the Link Share tracking scripts
	 * @return string
	 */
	protected function _toHtml()
	{
		$return = '';
		
		// Load Link Share Helper
		$helper = Mage::helper('fw_linkshare');	
		$storeid = Mage::app()->getStore()->getStoreId();
		
		// Make sure Link Share is enabled and all required data is configured
		if ($helper->isLinkShareAvailable($storeid))
		{			
			// Check if there is OrderIds set
			if (($orderIds = $this->getOrderIds()) && is_array($orderIds))
			{			
				// Get a collection of the orders from the DB
				$collection = Mage::getResourceModel('sales/order_collection')
					->addFieldToFilter('entity_id', array('in' => $orderIds));					
				
				foreach ($collection as $order) // Output for each order
				{
					//INIT DYNAMIC LINK SHARE VALUES
					$skulist = array();
					$qtylist = array();
					$amtlist = array();
					$namelist = array();
					
					//GATHER AND LOAD ALL PRODUCTS TO FORMAT LINK SHARE VARS
					$order_items = $order->getAllItems();
					
					//LOOP THRU ORDER ITEMS
					foreach($order_items as $item)
					{
						//ADD SKU TO LIST
						$skulist[] = urlencode($item->getSku());
						
						//ADD QTY OF ITEM TO LIST
						$qtylist[] =  number_format($item->getQtyOrdered(), 0, '.', '');
						
						//ADD AMT OF ITEM TO LIST - This is the amount value in form of (price * quantity * 100)
						$amtlist[] = number_format($item->getPrice()*$item->getQtyOrdered()*100, 0, '.', '');
						
						//ADD NAME OF ITEM TO LIST
						$namelist[] = urlencode($item->getName());
					}
					
					//CHECK FOR DISCOUNTS AND ADD IF SET
					if($order->getDiscountAmount() < 0){
						//ADD DISCOUNT TO SKU LIST
						$skulist[] = "Discount";
						
						//ADD DISCOUNT QTY ITEM TO LIST
						$qtylist[] = 0;
						
						//ADD DISCOUNT AMT OF ITEM TO LIST
						$amtlist[] = number_format($order->getDiscountAmount()*100, 0, '.', '');
						
						//ADD DISCOUNT NAME TO LIST
						$namelist[] = "Discount";
					}
					
					$return .= sprintf('<img src="http://track.linksynergy.com/ep?mid=%1$s&ord=%2$s&skulist=%3$s&qlist=%4$s&amtlist=%5$s&cur=USD&namelist=%6$s">',
						$this->escapeUrl($helper->getMerchantID()),		// Merchant ID from config
						$this->escapeUrl($order->getIncrementId()),		// Order ID
						$this->escapeUrl(implode("|", $skulist)),		// SKU LIST
						$this->escapeUrl(implode("|", $qtylist)),		// QTY LIST
						$this->escapeUrl(implode("|", $amtlist)),		// AMT LIST
						$this->escapeUrl(implode("|", $namelist))		// PRODUCT NAME LIST
					);
		        }
			}
		}
		return $return;
	}
}