## Overview ##
APE - Asynchronous Proxy Engine
APE is a SilverStripe CMS 3.0 Module (2.x wasn't tested yet)
APE aims to improve the workflow implementing asynchronous communication from the frontend templates to backend functions 
inside a class.

APE will create javascript proxies for defined functions in a extended module and provide these javascript functions to the 
frontend. Extending your SilverStripe module class with the APE extensions enables you to directly call the functions from 
your class using the auto-generated js proxy.

## Installation ##

* Download APE and put it into the SilverStripe root (./ss_root/ape/)
* Extend your custom SS-module class with the APEExtension
* run /dev/build


## Tutorial ##

Edit /mysite/_config.php
...
Object::add_extension('Page', 'APEExtension');
...

Edit /mysite/Page.php and export your first function HelloWorld($arg0, $arg1)

    <?php
    class Page extends SiteTree {

    	public static $ape_export = array (
		    'HelloWorld'
	    );
	
	    public function HelloWorld($firstname, $lastname) {
    		return "Hello $lastname, $firstname";
	    }
	
    	...
	
Call ./apecfg/build to rebuld the javascript files