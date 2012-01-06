<?php
class APEExtension extends DataExtension {

	public function ExportedMethods() {
		$methods = Object::get_static($this->owner->ClassName, "ape_export", true);
		$methods2 = array();
		foreach($methods as $method) {
			$r = new ReflectionMethod($this->owner->ClassName, $method);
			$params = $r->getParameters();

			$methods2[$method] = array();

			foreach($params as $arg) {
				array_push($methods2[$method], $arg->getName());
			}
		}
		return $methods2;
	}
	
	public function build_js() {
		//$methods = $this->ExportedMethods();

		$func = "";
		$func .= "{$this->owner->ClassName} = {};\n\n";
		foreach($methods as $method => $args) {
			$func .= "{$this->owner->ClassName}.{$method} = function(";
			foreach ($args as $index => $arg) {
				$func .= $arg;
				$func .= ", ";
			}
			$func .= "callback) {\n";
			$func .= "	\$ape.call({'ClassName' : '{$this->owner->ClassName}', 'function' : '{$method}'";
			foreach ($args as $index => $arg) {
				if($index == 0)
					$func .= ", ";
					
				$func .= "'arg$index' : {$arg}";
				
				if($index < count($args) - 1)
					$func .= ", ";
			}
			$func .= "}, function(e) { if(typeof callback !== 'undefined') callback(e); } );\n";
			$func .= "}\n\n";
		}
		
		$file = Director::BaseFolder()."/ape/js/api/{$this->owner->ClassName}.js";
		$fh = fopen($file, 'w') or die("can't open file");
		fwrite($fh, $func);
		fclose($fh);
	}
	
	public function index() {
		$this->build_js();
		
		Requirements::javascript("sapphire/thirdparty/jquery/jquery.js");
		Requirements::javascript("ape/js/ape.engine.js");
		Requirements::javascript("ape/js/api/{$this->owner->ClassName}.js");
		Requirements::customScript("\$ape.id = {$this->owner->ID};\$ape.baseURL = '".Director::absoluteBaseURL()."';\$ape.ClassName = '{$this->owner->ClassName}'");
		return array();
	}
}


class APE_Controller extends Controller {
	public static $allowed_actions = array (
		'call'
	);
	
	public function call() {
		$object = json_decode($_POST['json']);
		$do = DataObject::get_by_id($object->ClassName, $object->ID);

		if($do->hasMethod($object->function)) {
			$result = call_user_func_array(array($do, $object->function), array($object->arg0, $object->arg1, $object->arg2, $object->arg3));
			return $this->jsonify($do, $result);
		}
		echo "function not found";
	}
	
	private function jsonify($do, $data) {
		if($data instanceof DataList)
			$data = $data->toNestedArray();

		$object = array(
			'ClassData' => json_encode($data, JSON_FORCE_OBJECT),
			'ID' => $do->ID,
			'ClassName' => $do->ClassName
		);
		return json_encode($object);
	}
}