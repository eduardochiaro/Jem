<?php
/**
 * @title			Stats
 */


require_once(_LIBRARIES_PATH.'/ga/analytics.class.php');
	
class statsController extends Controller{
	
	function execView(){
		$workspace = $this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");
		
		//contatti totali
		$contact = new Contact($workspace);
		$total = $contact->getAll("*","YEAR(created) = '".date("Y")."' ");
		$workspace->set("StatsContacts", $total  );
		
		$region = array();
		foreach($total  as $t){
			$region[] = $t->getParameter("region","non definito");
		
		
			if(Utils::parseDate($t->created,"%H") == "00" || Utils::parseDate($t->created,"%H") == "01") $workspace->set("StatsContacts-H0", ($workspace->get("StatsContacts-H0") + 1)  );
			if(Utils::parseDate($t->created,"%H") == "02" || Utils::parseDate($t->created,"%H") == "03") $workspace->set("StatsContacts-H2", ($workspace->get("StatsContacts-H2") + 1)  );
			if(Utils::parseDate($t->created,"%H") == "04" || Utils::parseDate($t->created,"%H") == "05") $workspace->set("StatsContacts-H4", ($workspace->get("StatsContacts-H4") + 1)  );
			if(Utils::parseDate($t->created,"%H") == "06" || Utils::parseDate($t->created,"%H") == "07") $workspace->set("StatsContacts-H6", ($workspace->get("StatsContacts-H6") + 1)  );
			if(Utils::parseDate($t->created,"%H") == "08" || Utils::parseDate($t->created,"%H") == "09") $workspace->set("StatsContacts-H8", ($workspace->get("StatsContacts-H8") + 1)  );
			if(Utils::parseDate($t->created,"%H") == "10" || Utils::parseDate($t->created,"%H") == "11") $workspace->set("StatsContacts-H10", ($workspace->get("StatsContacts-H10") + 1)  );
			if(Utils::parseDate($t->created,"%H") == "12" || Utils::parseDate($t->created,"%H") == "13") $workspace->set("StatsContacts-H12", ($workspace->get("StatsContacts-H12") + 1)  );
			if(Utils::parseDate($t->created,"%H") == "14" || Utils::parseDate($t->created,"%H") == "15") $workspace->set("StatsContacts-H14", ($workspace->get("StatsContacts-H14") + 1)  );
			if(Utils::parseDate($t->created,"%H") == "16" || Utils::parseDate($t->created,"%H") == "17") $workspace->set("StatsContacts-H16", ($workspace->get("StatsContacts-H16") + 1)  );
			if(Utils::parseDate($t->created,"%H") == "18" || Utils::parseDate($t->created,"%H") == "19") $workspace->set("StatsContacts-H18", ($workspace->get("StatsContacts-H18") + 1)  );
			if(Utils::parseDate($t->created,"%H") == "20" || Utils::parseDate($t->created,"%H") == "21") $workspace->set("StatsContacts-H20", ($workspace->get("StatsContacts-H20") + 1)  );
			if(Utils::parseDate($t->created,"%H") == "22" || Utils::parseDate($t->created,"%H") == "23") $workspace->set("StatsContacts-H22", ($workspace->get("StatsContacts-H22") + 1)  );
			
			
			
			if(Utils::parseDate($t->created,"%m") == "1") $workspace->set("StatsContacts-M1", ($workspace->get("StatsContacts-M1") + 1)  );
			if(Utils::parseDate($t->created,"%m") == "2") $workspace->set("StatsContacts-M2", ($workspace->get("StatsContacts-M2") + 1)  );
			if(Utils::parseDate($t->created,"%m") == "3") $workspace->set("StatsContacts-M3", ($workspace->get("StatsContacts-M3") + 1)  );
			if(Utils::parseDate($t->created,"%m") == "4") $workspace->set("StatsContacts-M4", ($workspace->get("StatsContacts-M4") + 1)  );
			if(Utils::parseDate($t->created,"%m") == "5") $workspace->set("StatsContacts-M5", ($workspace->get("StatsContacts-M5") + 1)  );
			if(Utils::parseDate($t->created,"%m") == "6") $workspace->set("StatsContacts-M6", ($workspace->get("StatsContacts-M6") + 1)  );
			if(Utils::parseDate($t->created,"%m") == "7") $workspace->set("StatsContacts-M7", ($workspace->get("StatsContacts-M7") + 1)  );
			if(Utils::parseDate($t->created,"%m") == "8") $workspace->set("StatsContacts-M8", ($workspace->get("StatsContacts-M8") + 1)  );
			if(Utils::parseDate($t->created,"%m") == "9") $workspace->set("StatsContacts-M9", ($workspace->get("StatsContacts-M9") + 1)  );
			if(Utils::parseDate($t->created,"%m") == "10") $workspace->set("StatsContacts-M10", ($workspace->get("StatsContacts-M10") + 1)  );
			if(Utils::parseDate($t->created,"%m") == "11") $workspace->set("StatsContacts-M11", ($workspace->get("StatsContacts-M11") + 1)  );
			if(Utils::parseDate($t->created,"%m") == "12") $workspace->set("StatsContacts-M12", ($workspace->get("StatsContacts-M12") + 1)  );
		}
		$workspace->set("StatsContacts-region",array_count_values($region));
			
		Utils::getTemplate($workspace,$module,$task,false);
	}
	
	private function _arraySearch($needle = null, $haystack_array = null, $skip = 0){
		if($needle == null || $haystack_array == null)
			die('$needle and $haystack_array are mandatory for functie my_array_search()');
		foreach($haystack_array as $key => $eval)
		{
			if($skip != 0)$eval = substr($eval, $skip);
			if(stristr($eval, $needle) !== false) return true;
		}
		return false;
	}
	
	function execGoogle(){
		$workspace = $this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");
		
		
		$model=new Config($workspace);
		$model->getSingle("*",array("id"=>1));
		
			
		/* retrieve information from google analytics */
		$analytics = new analytics($model->analytics_username, $model->analytics_password);

		$aProfiles = $analytics->getProfileList();  
		$aProfileKeys = array_keys($aProfiles);
		$workspace->set("profiles",$aProfiles);
		
		$aProfileID = $workspace->getRequest("profileId", $aProfileKeys[0]);

		$analytics->setProfileById($aProfileID);

		Utils::getTemplate($workspace,$module,$task,$analytics);
	}

}
