<?php
class GroupsController extends AppController {
	var $helpers = array ('Html','Form');
	var $name = 'Groups';

	function login() {
    //Auth Magic
	}
 
	function logout() {
    //Leave empty for now.
	}

	function beforeFilter() {
		parent::beforeFilter(); 
		//$this->Auth->allow(array('*'));
		$this->Auth->allowedActions = array('index');
	}

	function index() {
		$this->set('groups', $this->Group->find('all'));
	}
	
	function add() {
		if (!empty($this->data)) {
			if ($this->Group->save($this->data)) {
				$this->Session->setFlash('Your post has been saved.');
				$this->redirect(array('action' => 'index'));
			}
		}
	}

	//The rest of the code below helps to build the aco table which contains
	//the permissions on access control objects
	// http://book.cakephp.org/view/1080/Layouts#!/view/1549/An-Automated-tool-for-creating-ACOs
	function build_acl() {
	
		set_time_limit(1000);
		
		if (!Configure::read('debug')) {
			return $this->_stop();
		}
		$log = array();

		$aco =& $this->Acl->Aco;
		$root = $aco->node('controllers');
		if (!$root) {
			$aco->create(array('parent_id' => null, 'model' => null, 'alias' => 'controllers'));
			$root = $aco->save();
			$root['Aco']['id'] = $aco->id; 
			$log[] = 'Created Aco node for controllers';
		} else {
			$root = $root[0];
		}   

		App::import('Core', 'File');
		$Controllers = App::objects('controller');
		$appIndex = array_search('App', $Controllers);
		if ($appIndex !== false ) {
			unset($Controllers[$appIndex]);
		}
		$baseMethods = get_class_methods('Controller');
		$baseMethods[] = 'build_acl';

		$Plugins = $this->_getPluginControllerNames();
		$Controllers = array_merge($Controllers, $Plugins);

		// look at each controller in app/controllers
		foreach ($Controllers as $ctrlName) {
			$methods = $this->_getClassMethods($this->_getPluginControllerPath($ctrlName));

			// Do all Plugins First
			if ($this->_isPlugin($ctrlName)){
				$pluginNode = $aco->node('controllers/'.$this->_getPluginName($ctrlName));
				if (!$pluginNode) {
					$aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => $this->_getPluginName($ctrlName)));
					$pluginNode = $aco->save();
					$pluginNode['Aco']['id'] = $aco->id;
					$log[] = 'Created Aco node for ' . $this->_getPluginName($ctrlName) . ' Plugin';
				}
			}
			// find / make controller node
			$controllerNode = $aco->node('controllers/'.$ctrlName);
			if (!$controllerNode) {
				if ($this->_isPlugin($ctrlName)){
					$pluginNode = $aco->node('controllers/' . $this->_getPluginName($ctrlName));
					$aco->create(array('parent_id' => $pluginNode['0']['Aco']['id'], 'model' => null, 'alias' => $this->_getPluginControllerName($ctrlName)));
					$controllerNode = $aco->save();
					$controllerNode['Aco']['id'] = $aco->id;
					$log[] = 'Created Aco node for ' . $this->_getPluginControllerName($ctrlName) . ' ' . $this->_getPluginName($ctrlName) . ' Plugin Controller';
				} else {
					$aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => $ctrlName));
					$controllerNode = $aco->save();
					$controllerNode['Aco']['id'] = $aco->id;
					$log[] = 'Created Aco node for ' . $ctrlName;
				}
			} else {
				$controllerNode = $controllerNode[0];
			}

			//clean the methods. to remove those in Controller and private actions.
			foreach ($methods as $k => $method) {
				if (strpos($method, '_', 0) === 0) {
					unset($methods[$k]);
					continue;
				}
				if (in_array($method, $baseMethods)) {
					unset($methods[$k]);
					continue;
				}
				$methodNode = $aco->node('controllers/'.$ctrlName.'/'.$method);
				if (!$methodNode) {
					$aco->create(array('parent_id' => $controllerNode['Aco']['id'], 'model' => null, 'alias' => $method));
					$methodNode = $aco->save();
					$log[] = 'Created Aco node for '. $method;
				}
			}
		}
		if(count($log)>0) {
			debug($log);
		}
	}

	function _getClassMethods($ctrlName = null) {
		App::import('Controller', $ctrlName);
		if (strlen(strstr($ctrlName, '.')) > 0) {
			// plugin's controller
			$num = strpos($ctrlName, '.');
			$ctrlName = substr($ctrlName, $num+1);
		}
		$ctrlclass = $ctrlName . 'Controller';
		$methods = get_class_methods($ctrlclass);

		// Add scaffold defaults if scaffolds are being used
		$properties = get_class_vars($ctrlclass);
		if (array_key_exists('scaffold',$properties)) {
			if($properties['scaffold'] == 'admin') {
				$methods = array_merge($methods, array('admin_add', 'admin_edit', 'admin_index', 'admin_view', 'admin_delete'));
			} else {
				$methods = array_merge($methods, array('add', 'edit', 'index', 'view', 'delete'));
			}
		}
		return $methods;
	}

	function _isPlugin($ctrlName = null) {
		$arr = String::tokenize($ctrlName, '/');
		if (count($arr) > 1) {
			return true;
		} else {
			return false;
		}
	}

	function _getPluginControllerPath($ctrlName = null) {
		$arr = String::tokenize($ctrlName, '/');
		if (count($arr) == 2) {
			return $arr[0] . '.' . $arr[1];
		} else {
			return $arr[0];
		}
	}

	function _getPluginName($ctrlName = null) {
		$arr = String::tokenize($ctrlName, '/');
		if (count($arr) == 2) {
			return $arr[0];
		} else {
			return false;
		}
	}

	function _getPluginControllerName($ctrlName = null) {
		$arr = String::tokenize($ctrlName, '/');
		if (count($arr) == 2) {
			return $arr[1];
		} else {
			return false;
		}
	}

/**
 * Get the names of the plugin controllers ...
 * 
 * This function will get an array of the plugin controller names, and
 * also makes sure the controllers are available for us to get the 
 * method names by doing an App::import for each plugin controller.
 *
 * @return array of plugin names.
 *
 */
	function _getPluginControllerNames() {
		App::import('Core', 'File', 'Folder');
		$paths = Configure::getInstance();
		$folder =& new Folder();
		$folder->cd(APP . 'plugins');

		// Get the list of plugins
		$Plugins = $folder->read();
		$Plugins = $Plugins[0];
		$arr = array();

		// Loop through the plugins
		foreach($Plugins as $pluginName) {
			// Change directory to the plugin
			$didCD = $folder->cd(APP . 'plugins'. DS . $pluginName . DS . 'controllers');
			// Get a list of the files that have a file name that ends
			// with controller.php
			$files = $folder->findRecursive('.*_controller\.php');

			// Loop through the controllers we found in the plugins directory
			foreach($files as $fileName) {
				// Get the base file name
				$file = basename($fileName);

				// Get the controller name
				$file = Inflector::camelize(substr($file, 0, strlen($file)-strlen('_controller.php')));
				if (!preg_match('/^'. Inflector::humanize($pluginName). 'App/', $file)) {
					if (!App::import('Controller', $pluginName.'.'.$file)) {
						debug('Error importing '.$file.' for plugin '.$pluginName);
					} else {
						/// Now prepend the Plugin name ...
						// This is required to allow us to fetch the method names.
						$arr[] = Inflector::humanize($pluginName) . "/" . $file;
					}
				}
			}
		}
		return $arr;
	}

	
}
?>