<?php
/**
 * @title		INDEX
 * @description	modulo di dashboard
 * @task		a
 */


class indexController extends Controller{
	
	var $_tags;
	
	function execView(){
		$workspace = $this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		
		$my = $workspace->loadHandler("access");

		
		//contatti
		if(class_exists("Contact")){
			$contact = new Contact($workspace);
			$workspace->set("DashContacts", $contact->getAll("*",false,"created DESC",3));
	
			//stats contatti oggi
	
			$usefilter = new LikeFilter("created", date("Y-m-d"));
			$filter = new AndFilter($usefilter);
	
			
			$contact = new Contact($workspace);
			$workspace->set("DashContactsStatsToday", $contact->getAll("id",$filter->toSQL()));
			
			//stats contatti settimana
			$contact = new Contact($workspace);
			$workspace->set("DashContactsStatsWeek", $contact->getAll("id","YEARWEEK(created) = YEARWEEK(NOW())"));
			
			//stats contatti mese
			$contact = new Contact($workspace);
			$workspace->set("DashContactsStatsMonth", $contact->getAll("id","YEAR(created) = YEAR(NOW()) AND MONTH(created) = MONTH(NOW())"));
			
			//stats contatti anno
			$contact = new Contact($workspace);
			$workspace->set("DashContactsStatsYear", $contact->getAll("id","YEAR(created) = YEAR(NOW())"));
			
			//config stats
			$config=new Config($workspace);
			$config->getSingle("*",array("id"=>1));
			$workspace->set("DashConfig",$config);
		}		
		//news
		if(class_exists("News")){
			$news = new News($workspace);
			$workspace->set("DashNews", $news->getAll("*",false,"pubdate DESC",3));
		}
		if(class_exists("Stats")){
			$workspace->set("DashStats", true);
		}

		Utils::getTemplate($workspace,$module,$task);

	}
}
