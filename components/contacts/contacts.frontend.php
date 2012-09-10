<?php

class ContactsFrontend extends Frontend{
	
	
	function loadForm($replies){
		$workspace = JEM::getInstance();
		$translations = $workspace->getTranslation();
		if(is_array($replies)){
			$model = new Reply($workspace);
				// Costruisco filtro ricerca
	
			if(is_array($replies)) {
				$modelfilter = new InFilter($model ->getTable().".id", $replies);
			} else {
				$modelfilter = new TrueFilter();
			}
			$alterfilter = new LikeFilter($model ->getTable().".publish", 1, _LIKE_STRICT );
			$filter = new AndFilter($modelfilter, $alterfilter);
			if(!$filter) {
				$filter = new TrueFilter();
			}
			$all = $model->getAll("*", $filter->toSQL());
		
			$workspace->set("methods",$all);
			
			
			$countries = Filesystem::loadFile(_LIBRARIES_PATH._PATH_SEPARATOR."json"._PATH_SEPARATOR."countries.json");
			Zend_Json::$useBuiltinEncoderDecoder = true;
			$workspace->set("countries",Zend_Json::decode($countries));
			
			if($workspace->getPost("sent-it")){
				$return = ContactsFrontend::saveForm();
				if($return){
					$workspace->deleteFromPost();
				}
			}
			
			include(_FRONTEND_PATH._PATH_SEPARATOR."includes"._PATH_SEPARATOR."contacts.php");
		}
	}
	
	function saveForm(){
		$workspace = JEM::getInstance();
		$translations = $workspace->getTranslation();
		
		$error = false;
		$post = $workspace->getPost();
		
		$required = $workspace->getSession("to-validate");
		
		$toDB = array();
		$toParameters = array();
		
		foreach($required as $key => $element){
			
			
			if($element["database"]){
				$toDB[$key] = $post[$key];
			}else{
				$toParameters[$key] = $post[$key];
			}
			
			
			$fun = "check".ucfirst($element["type"]);
			
			if(is_callable(array("CheckForm", $fun))){

				if($element["required"] && !CheckForm::$fun($post[$key])){

					switch($element["type"]){
						case "text":
							$workspace->addFrontErrorMessage(sprintf(_LN_ERROR_EMPTY, $element["name"]));
							$error = true;
							break;
						case "email":
							$workspace->addFrontErrorMessage(sprintf(_LN_ERROR_EMAIL,$element["name"]));
							$error = true;
							break;
						case "checkbox":
							$workspace->addFrontErrorMessage(sprintf(_LN_ERROR_CHECKBOX,$element["name"]));
							$error = true;
							break;
						case "int":
							$workspace->addFrontErrorMessage(sprintf(_LN_ERROR_NUMERIC,$element["name"]));
							$error = true;
							break;
						case "dateValid":
							$workspace->addFrontErrorMessage(sprintf(_LN_ERROR_DATE,$element["name"]));
							$error = true;
							break;
					}
				}
			}
			
		}
		$toParameters["ip"] = $workspace->getServer("REMOTE_ADDR");
		
		if($error){
			include(_FRONTEND_PATH._PATH_SEPARATOR."includes"._PATH_SEPARATOR."errors.php");
			$workspace->deleteFromSession(_JEM_FRONT_ERROR_MESSAGES_);
			
			return false;
		}
		
		$toDB["parameters"] = $toParameters;
		$model = new Contact($workspace);
		
		$model->save($toDB);
		
		$model->insert();
		
		ContactsFrontend::sendTo($model);
		
		$workspace->addFrontInfoMessage(_LN_INFO_SEND_CONTACT);
		include(_FRONTEND_PATH._PATH_SEPARATOR."includes"._PATH_SEPARATOR."info.php");
		$workspace->deleteFromSession(_JEM_FRONT_INFO_MESSAGES_);
		
		return true;
	}
	
	function sendTo($user){
		$workspace = JEM::getInstance();
		$translations = $workspace->getTranslation();

		
		$idfilter = new LikeFilter("id", $user->reply ,_LIKE_STRICT);
		$filter = new AndFilter($idfilter);
		if(!$filter) {
			$filter = new TrueFilter();
		}
		
		$config=new Config($workspace);
		$config->getSingle("*",array("id"=>1));
		
		$reply = new Reply($workspace);
		$reply->getSingle("*",$filter->toSQL());
		
		if($reply->id){
			$sendmail = new PHPMailer(true); //defaults to using php "mail()"; the true param means it will throw exceptions on errors, which we need to catch
			$sendmail->IsHTML(false);
			
			try {
				$sendmail->AddAddress($user->getParameter("email"), $user->getParameter("name").' '.$user->getParameter("surname"));
				
				if($config->replayto) $sendmail->AddReplyTo($config->replayto);
				
				$sendmail->SetFrom($reply->email);
				
				$sendmail->Subject = $reply->title;
				$sendmail->AltBody = $reply->text; // optional - MsgHTML will create an alternate automatically
	
				$sendmail->Body = $reply->text;
				$sendmail->Send();
				
			} catch (phpmailerException $e) {
				$workspace->addErrorInfoMessage(_LN_ERROR_SEND);
			} catch (Exception $e) {
				$workspace->addErrorInfoMessage(_LN_ERROR_SEND);
			}
			
		}
	}
}