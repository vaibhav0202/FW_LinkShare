<?php
class FW_LinkShare_Model_Observer
{
    /**
     * Add order information into LinkShare block to render on checkout success pages
     * @param Varien_Event_Observer $observer
     */
    public function onOrderSuccessPageView(Varien_Event_Observer $observer)
    {
    	$orderIds = $observer->getEvent()->getOrderIds();		// Get Order IDs
        if (empty($orderIds) || !is_array($orderIds)) return;	// No Order IDs 
		
		$layout = Mage::app()->getFrontController()->getAction()->getLayout();	// Get the layout	
		$beforeBodyEnd = $layout->getBlock('before_body_end');					// Get before_body_end
		if (empty($beforeBodyEnd)) return;										// before_body_end doesn't exist
		
		$block = $layout->createBlock('fw_linkshare/linkshare','linkshare');	// Create block
		if (empty($block)) return;												// block doesn't exist
		
		$block->setOrderIds($orderIds);							// Set Order IDs
		$layout->getBlock('before_body_end')->append($block);	// Add block to before_body_end
    }
}
