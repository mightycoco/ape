<?php
class APE_Config extends Controller {
	static $allowed_actions = array( 
        'build',
		'test'
 	);
	
	function init() {
		parent::init();
		
		// Special case for dev/build: Defer permission checks to DatabaseAdmin->init() (see #4957)
		$requestedDevBuild = (stripos($this->request->getURL(), 'apecfg/build') === 0);
		
		// We allow access to this controller regardless of live-status or ADMIN permission only
		// if on CLI.  Access to this controller is always allowed in "dev-mode", or of the user is ADMIN.
		$canAccess = (
			$requestedDevBuild 
			|| Director::isDev() 
			|| Director::is_cli() 
			// Its important that we don't run this check if dev/build was requested
			|| Permission::check("ADMIN")
		);
		if(!$canAccess) return Security::permissionFailure($this);
		
		// check for valid url mapping
		// lacking this information can cause really nasty bugs,
		// e.g. when running Director::test() from a FunctionalTest instance
		global $_FILE_TO_URL_MAPPING;
		if(Director::is_cli()) {
			if(isset($_FILE_TO_URL_MAPPING)) {
				$fullPath = $testPath = BASE_PATH;
				while($testPath && $testPath != "/" && !preg_match('/^[A-Z]:\\\\$/', $testPath)) {
					$matched = false;
					if(isset($_FILE_TO_URL_MAPPING[$testPath])) {
						$matched = true;
					    break;
					}
					$testPath = dirname($testPath);
				}
				if(!$matched) {
					echo 'Warning: You probably want to define '.
						'an entry in $_FILE_TO_URL_MAPPING that covers "' . Director::baseFolder() . '"' . "\n";
				}
			}
			else {
				echo 'Warning: You probably want to define $_FILE_TO_URL_MAPPING in '.
					'your _ss_environment.php as instructed on the "sake" page of the doc.silverstripe.org wiki' . "\n";
			}
		}
	}
	
	function index() {
		$actions = array(
			"build" => "Build/rebuild this environment.  Call this whenever you have updated your exported ape methods in <i>public static \$ape_export = array ()</i>",
			"test" => "Test APE API Scripts",
		);
		
		// Web mode
		if(!Director::is_cli()) {
			// This action is sake-only right now.
			unset($actions["modules/add"]);
			
			$renderer = Object::create('DebugView');
			$renderer->writeHeader();
			$renderer->writeInfo("Sapphire Development Tools", Director::absoluteBaseURL());
			$base = Director::baseURL();

			echo '<div class="options"><ul>';
			foreach($actions as $action => $description) {
				echo "<li><a href=\"{$base}apecfg/$action\"><b>/dev/$action:</b> $description</a></li>\n";
			}

			$renderer->writeFooter();
		
		// CLI mode
		} else {
			echo "SAPPHIRE DEVELOPMENT TOOLS\n--------------------------\n\n";
			echo "You can execute any of the following commands:\n\n";
			foreach($actions as $action => $description) {
				echo "  sake dev/$action: $description\n";
			}
			echo "\n\n";
		}
	}
	
	function build($request) {
		if (!Director::is_cli()) {
			$renderer = Object::create('DebugView');
			$renderer->writeHeader();
			$renderer->writeInfo("Defaults Builder", Director::absoluteBaseURL());
			echo "<div style=\"margin: 0 2em\">";
		}
		
		$dos = DataObject::get("SiteTree");
		$unique = array();
		foreach($dos as $do) {
			if(!in_array($do->ClassName, $unique)) {
				if($do->hasMethod("build_js")) {
					echo "Recreating ape/js/api/{$do->ClassName}.js<br/>";
					$do->build_js();
				}
			}
			array_push($unique, $do->ClassName);
		}
	
		if (!Director::is_cli()) {
			echo "</div>";
			$renderer->writeFooter();
		}
	}
	
	function test($request) {
		if (!Director::is_cli()) {
			$renderer = Object::create('DebugView');
			$renderer->writeHeader();
			$renderer->writeInfo("Defaults Builder", Director::absoluteBaseURL());
			echo "<div style=\"margin: 0 2em\">";
		}

		if(!isset($_REQUEST["api"])) {
			$files = scandir(Director::baseFolder()."/ape/js/api/");
			echo "<ul>";
			foreach($files as $file) {
				if(!is_dir(Director::baseFolder()."/ape/js/api/$file")) {
					$api = basename($file, ".js");
					echo "<li><a href='?api=$api'>$api</a></li>";
				}
			}
			echo "</ul>";
		} else {
			$api = $_REQUEST["api"];
			$baseurl = Director::AbsoluteBaseURL();
			echo "<script type='text/javascript' src='{$baseurl}sapphire/thirdparty/jquery/jquery.js'></script>";
			echo "<script type='text/javascript' src='{$baseurl}ape/js/ape.engine.js'></script>";
			echo "<script type='text/javascript' src='{$baseurl}ape/js/api/$api.js'></script>";
			
			echo <<<EOT
			To include this API on a Pagetype other than "$api" add the following code to the template or use the 
			native Require::javascript() function.<br/>
			<pre>
			&lt;script type='text/javascript' src='{$baseurl}ape/js/ape.engine.js'&gt;&lt;/script&gt;
			&lt;script type='text/javascript' src='{$baseurl}ape/js/api/$api.js'&gt;&lt;/script&gt;
			</pre>
			<br/>
EOT;
			
			$dos = DataObject::get($api);
			if($dos) {
				$do = $dos->First();
				if($do) {
					echo "<script type='text/javascript'>\$ape.id = {$do->ID};\$ape.baseURL = '$baseurl';\$ape.ClassName = '$api'</script>";
					echo "<div style='float:left;width: 40%;'>";
					foreach($do->ExportedMethods() as $method => $args) {
						echo "$api.$method (";
						foreach($args as $index => $arg) {
							echo "<input name='$arg' rel='$method' value='' placeholder='$arg'/> ";
							if($index < count($args)-1)
								echo ", ";
						}
						echo " )";
						echo " <button class='testbutton' type='button' value='test' rel='$method'>test</button>";
						echo "<br/>";
					}
					echo "</div>";
				}
				echo <<<script
					<div style='float: left;border: 1px solid black;width: 59%;overflow:auto;background-color: #fff;'><pre id='ape_result'>&nbsp;</pre></div>
					
					<script type='text/javascript'>
					$(document).ready(function(e) {
						$(".testbutton").click(function(e) {
							var rel = $(this).attr("rel");
							var func = "$api."+rel+"(";
							$('input[rel="'+rel+'"]').each(function(i, e) {
								func += '"'+($(this)[0].value)+'"';
								func += ", ";
							});
							func += "json_result";
							func += ");";
							$('#ape_result').html(func);
							console.log(func);
							eval(func);
						});
					});
					function json_result(e) {
						if(typeof e.ClassData == "object")
							$('#ape_result').text(JSON.stringify(e.ClassData));
						else
							$('#ape_result').text(e.ClassData);
					}
					</script>
script;
			}
		}
		
		if (!Director::is_cli()) {
			echo "</div>";
			$renderer->writeFooter();
		}
	}
}