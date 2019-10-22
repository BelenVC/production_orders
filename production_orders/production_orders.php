<?php

class production_orders extends Module{
	
	function __construct(){
        $this->name = 'production_orders';
        $this->tab =  'administration';
        $this->author = 'Belen';
        $this->version = '1.1';//css  
        parent::__construct();
    
        $this->displayName = $this->l('Production Orders');
        $this->description = $this->l('Module that controls the production of our companys orders.');
		$this->tabClassName = 'ProductionOrders';
        $this->confirmUninstall = $this->l('If you uninstall, settings will be lost. Are you sure?');

    }

	public function install(){
		if(parent::install()==false)
			return false;
		//Para crear una pestaña en el menú del backoffice
		if (!$id_tab) {
			$tab = new Tab();
			$tab->class_name = $this->tabClassName;
			$tab->id_parent = Tab::getIdFromClassName($this->tabParentName);
			$tab->module = $this->name;
			$languages = Language::getLanguages();
			foreach ($languages as $language)
		        $tab->name[$language['id_lang']] = 'Producción de Pedidos';
	    	$tab->add();
		}
		
		if (!$this->createDatabases()) {
            $this->uninstall();
            $this->_errors[] = $this->l('Error to create Data Base');
            return false;
        }
		return true;
	}
	
	public function createDatabases(){
		include(dirname(__FILE__) . '/sql/install.php');
		return true;
	}
	
	public function uninstall(){
		if (parent::uninstall() == false) 
			return false;
		//Para eliminar la pestaña del menú del backoffice
		$id_tab = Tab::getIdFromClassName($this->tabClassName);
		if ($id_tab) {
	      $tab = new Tab($id_tab);
	      $tab->delete();
	    }
		return true;
	}
	
	public function getPath()
    {
        return $this->_path;
    }
	
	public function hookBackOfficeHeader()
    {
            $this->context->controller->addJquery();
            $this->context->controller->addJS($this->_path.'views/js/backproduction.js');
            $this->context->controller->addCSS($this->_path.'views/css/nvn.css');
    }
	
	public function hookAdminOrder($params)
	{
		
		try {
			$order = new Order((int)$params['id_order']);
			$this->context->smarty->assign('path', $this->_path);
			$sql = 'SELECT * FROM ' . _DB_PREFIX_ . 'factory_state_order fso LEFT JOIN ' . _DB_PREFIX_ . 'factory_states fs ON fso.id_factory_state=fs.id_factory_state LEFT JOIN ' . _DB_PREFIX_ . 'employee e ON fso.id_employee=e.id_employee WHERE id_order =' . $order->id;
			$result = db::getInstance()->ExecuteS($sql);
			if (!empty($result)) {
				$this->context->smarty->assign('estadosFab', $result);
			}	
			
			//return $this->display(__FILE__, 'views/templates/orderDetailAdmin.tpl');
		}catch(Exception $e){
			//return $this->displayError("Se ha producido un error al procesar la solicitud." . $e);
		}			
		
	}
	
}
?>