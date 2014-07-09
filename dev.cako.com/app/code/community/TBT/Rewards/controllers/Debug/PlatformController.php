<?php

/**
 *
 * @category   TBT
 * @package    TBT_Rewards
 * @author     WDCA Sweet Tooth Team <contact@wdca.ca>
 */
require_once (Mage::getModuleDir('controllers', 'TBT_Rewards'). DS .'Debug'. DS .'AbstractController.php');

class TBT_Rewards_Debug_PlatformController extends TBT_Rewards_Debug_AbstractController {

    public function indexAction() {
        echo "<h2>This tests platform functionality </h2>";
        echo "<a href='" . Mage::getUrl('rewards/debug_platform/testStory') . "'>Test creating a story on the Platform server - </a> <BR />";

    }
    
    public function testStoryAction() {
        
        $pi = Mage::getSingleton('rewards/platform_instance');
        $fields = array(
                'story_type' => 'review',
                'channel_object_id' => '1',
                'comments' => 'Customer wrote a review',
                'user' => array(
                    'firstname' => "John",        
                    'lastname' => "Doe123",        
                    'email' => "johndoe123_test@wdca.ca",        
                )
        );
        
        try {
            $story_result = $pi->story()->create($fields);
        } catch(SweetToothApiException $e) {
            die($e->getMessage());
        }
        
    }
    
    
    /*
     * {
	// One of these
	“user_id” : “1”,
	“channel_user_id : “23”,
	“user” : 			//  See User Object.	

	// required
	"story_type": "signup",
//optional
“event_data”:””,
"object_id": "11",
"channel_object_id": "23",

“comments”:”Adjustment of points”
    }
    */
 
}