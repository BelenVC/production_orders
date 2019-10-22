<?php

ob_start();
class ProductionOrdersController extends ModuleAdminController{
	
	public function __construct(){
		
		$module = Module::getInstanceByName('production_orders');
		$this-> bootstrap = true;
		$this->module = $module;

		$this->addJQuery();
        $this->addJS($module->getPath() . 'views/js/productionOrdersController.js');

		$this->name='ProductionOrders';
		$this->table = 'order';
        //$this->identifier = "id_order";
        //$this->_defaultOrderWay = "DESC";
        $this->lang = false;
        $this->module = $module;
        $this->addRowAction('view');
        $this->addRowAction('delete');
        $this->explicitSelect = true;
        $this->allow_export = true;
        $this->deleted = false;
        $this->context = Context::getContext();
        //$this->show_toolbar = false;
        //$this->page_header_toolbar_btn = array();

		
		
		$statuses = $this->getOrderState2((int)$this->context->language->id);
        foreach ($statuses as $status) {
            $this->statuses_array[$status['id_order_state']] = $status['name'];
        }
		
		$this->fields_list = array(
            'id_order' => array(
                'title' => $this->module->l('ID'),
                'align' => 'text-center',
                'class' => 'fixed-width-xs',
				'filter_key' => 'a!id_order'
            ),
            'date_add' => array(
                'title' => $this->module->l('Fecha'),
                'align' => 'left',
                'orderby' => true,
                'type' => 'datetime',
                'search' => true
            ),
            'Cliente' => array(
                'title' => $this->module->l('Cliente'),
                'align' => 'left',
                'orderby' => true,
                'search' => true
            ),
			'CantPS' => array(
				'title' => $this->module->l('Cant.PS'),
				'align' => 'center'
			),
			/*'product_name' => array(
				'title' => $this->module->l('Product PS-MgP'),
				'align' => 'left',
				'orderby' => true,
				'search' => true
			),*/
			'Atributos' => array(
				'title' => $this->module->l('Atributos'),
				'align' => 'left',
				'orderby' => true,
				'search' => true,
				'havingFilter' => true
			),
			'Grosor' => array(
				'title' => $this->module->l('Grosor'),
				'align' => 'center',
				'orderby' => true,
				'search' => true
			),
			'Ancho' => array(
				'title' => $this->module->l('Ancho'),
				'align' => 'center',
				'orderby' => true,
				'search' => true
			),
			'Largo' => array(
				'title' => $this->module->l('Largo'),
				'align' => 'center',
				'orderby' => true,
				'search' => true
			),
			'Mensaje_del_cliente' => array(
				'title' => $this->module->l('Mensaje Cli.'),
				'align' => 'left',
				'orderby' => true,
				'search' => true
			),
			'CantMg' => array(
				'title' => $this->module->l('Cant.MgP'),
				'align' => 'center',
				'orderby' => true,
				'search' => true
			),
			'Tran' => array(
				'title' => $this->module->l('Tran.'),
				'align' => 'left',
				'orderby' => true,
				'search' => true
			),
			'osname' => array(
                'title' => $this->module->l('Estado'),
				'align' => 'left',
				'orderby' => true,
				'search' => false
                /*'type' => 'select',
                'color' => 'color',
                'list' => $this->statuses_array,
                'filter_key' => 's!id_order_state',
                'filter_type' => 'int',
                'order_key' => 'osname'*/
            ),
			'statusF' => array(
				'title' => $this->module->l('Último est. Fab.'),
				'align' => 'left',
				'orderby' => true,
				'search' => false
			)
        );


        $this->_join .= '
			LEFT JOIN `' . _DB_PREFIX_ . 'customer` c ON (c.id_customer = a.id_customer)
			LEFT JOIN `' . _DB_PREFIX_ . 'order_state_lang` s ON s.id_order_state = a.current_state AND s.id_lang=' . (int)Context::getContext()->language->id . ' 
			LEFT JOIN `' . _DB_PREFIX_ . 'carrier` ca ON ca.id_carrier = a.id_carrier
			LEFT JOIN `' . _DB_PREFIX_ . 'state` st ON st.id_state = a.current_state
			LEFT JOIN `' . _DB_PREFIX_ . 'order_detail` od ON od.id_order = a.id_order
			LEFT JOIN `' . _DB_PREFIX_ . 'megaproductcart` mpc ON mpc.id_cart=a.id_cart AND mpc.id_product=od.product_id
			LEFT JOIN `' . _DB_PREFIX_ . 'customer_thread` ct ON (ct.id_customer = c.id_customer AND ct.id_order = a.id_order)
			LEFT JOIN `' . _DB_PREFIX_ . 'factory_state_order` fso ON (a.id_order = fso.id_order)
			LEFT JOIN `' . _DB_PREFIX_ . 'factory_states` fs ON (fso.id_factory_state = fs.id_factory_state)'; 
 
        $this->_select .= 'concat(c.firstname, " ", c.lastname) as Cliente,s.id_order_state as id_order_state1,s.name as osname,a.date_add,od.product_quantity as CantPS,
			od.product_name,CONCAT(od.product_name," ",if(_splitear(mpc.attributes,"-")IS NOT NULL,_splitear(mpc.attributes,"-"),"")) as Atributos,mpc.quantity as CantMg,truncate(mpc.length,2) as Grosor,truncate(mpc.width,2) as Ancho,
			truncate(mpc.height,2) as Largo,ca.name as Tran,LEFT(_unir_mensajes(ct.id_customer_thread),60) as Mensaje_del_cliente,
			(SELECT fs.factory_state FROM ps_factory_states fs INNER JOIN ps_factory_state_order fso ON fs.id_factory_state=fso.id_factory_state 
			WHERE fso.id_order=a.id_order ORDER BY date_upd desc LIMIT 1) as statusF'; 
			//,IF(fs.id_factory_state IS NOT NULL,fs.id_factory_state,0) AS id_factory_state
		$this->_where .= ' AND (s.name LIKE "Carga%" OR s.name LIKE "Produccion%") ';
		$this->_orderBy = 'a.id_order';
        $this->_orderWay = 'ASC';
        $this->_use_found_rows = true;
		$this->_default_pagination = 1000;
		
		
		$this->bulk_actions = array();

        parent::__construct();
	}
	
			
	 public function renderView(){

        Tools::redirectAdmin(Context::getContext()->link->getAdminLink('AdminOrders',Tools::getAdminTokenLite('AdminOrders')).'&vieworder&id_order='.Tools::getValue('id_order'));
    }
	
	public function renderList(){
		$smarty = $this->context->smarty;
		$html = ''; 
		$option=0;
		$ostat = array();
		$filtro_estados='';
		
		$sections = $this->getFactorySection();
		foreach ($sections as $section) {
            $this->sections_array[$section['id_factory_section']] = $section['factory_section'];
        }
		$states = $this->getOrderState2((int)$this->context->language->id);
		
				
			
		if (Tools::getValue('section_selected') || (Tools::getValue('section_selected') && Tools::getValue('exportlist')==1)) {
			$option = Tools::getValue('section_selected');
			if(Tools::getIsset('select_estados')){ 
				foreach (Tools::getValue('select_estados') as $selectedOption){
					$ostat[] = (int)$selectedOption;
				}
				for ($i=0;$i<count($ostat);$i++){
					if($i==0){
						$this->_where .= 'AND (s.id_order_state='.$ostat[$i];
						$filtro_estados .= 'AND (o.current_state='.$ostat[$i];
					}else{
						$this->_where .= ' OR s.id_order_state='.$ostat[$i];
						$filtro_estados .= 'OR (o.current_state='.$ostat[$i];
					}	
					if($i==(count($ostat)-1)){	
						$this->_where .= ')';
						$filtro_estados .= ')';
					}					
				}
				
				
				if (count($ostat)==0){
					$ostat=unserialize(Tools::getValue('select_estados'));
					for ($i=0;$i<count($ostat);$i++){
						if($i==0){
							$filtro_estados .= 'AND (o.current_state='.$ostat[$i];
						}else{
							$filtro_estados .= 'OR (o.current_state='.$ostat[$i];
						}	
						if($i==(count($ostat)-1)){	
							$filtro_estados .= ')';
						}					
					}
				}
            } 
			$filterNoInIds = '';				
			if (Tools::getValue('section_selected')==1) {
				//ESPUMAS
				//$this->_where .= ' HAVING id_factory_state<>1';
				$sql='SELECT DISTINCT fso.id_order FROM '._DB_PREFIX_.'factory_state_order fso,'._DB_PREFIX_.'orders o WHERE fso.id_order=o.id_order '.$filtro_estados.' AND id_factory_state=1';
				if($resNoId = Db::getInstance()->ExecuteS($sql)){
					$filterNoInIds = ' AND a.id_order NOT IN(';	
					foreach($resNoId as $Ides){
						$filterNoInIds .= $Ides['id_order'].',';  
					}
					$filterNoInIds = substr($filterNoInIds,0,strlen($filterNoInIds)-1).')';
				}
				$this->_where .= $filterNoInIds;
								
				//palabras claves para espumas
				$this->_where .= ' GROUP BY Atributos, Grosor, Ancho, Largo';
				$this->_where .= ' HAVING (Atributos like "%espuma%" or Atributos like "%viscoelastica a medida%" or Atributos like "%topper%" 
					or Atributos like "%cama para perro%" or Atributos like "%deluxe%" or Atributos like "%colchon viscoelastico%" or Atributos like "%colchon cuna%" 
					or Atributos like "%sofa palet%" or Atributos like "%colchon venus%" or Atributos like "%colchon laura%" or Atributos like "%a medida%" 
					or Atributos like "%colchon viscolastico%" or Atributos like "%visco dream%" or Atributos like "%viscolastica%" 
					or Atributos like "%colchon viscoelastica%" or Atributos like "%sofa cama%" or Atributos like "%sofa-cama%" or Atributos like "%d.25%" 
					or Atributos like "%d.23%" or Atributos like "%d.20%"	or Atributos like "%hr30%" or Atributos like "%hr35%" or Atributos like "%hr 30%"
					or Atributos like "%hr 35%" or Atributos like "%palet%" or Atributos like "%cambiador%" or Atributos like "%pleable%" 
					or Atributos like "%cojin de asiento%" or Atributos like "%asiento%" or Atributos like "%cojin viscoelastica%" or Atributos like "%colchoneta%"
					or Atributos like "%cilindro%" or Atributos like "%redondo%" or Atributos like "%visco 5%" or Atributos like "%especial%" or Atributos like "%colchon de perro%")';
				//palabras a excluir para espumas
				$this->_where .= ' AND Atributos not like "%base%" AND Atributos not like "%cabecero%" AND Atributos  not like "%canapé%" 
					AND Atributos not like "%suite desenfundable%" AND Atributos not like "%antiescaras%"  
					AND Atributos not like "%articulada%" AND Atributos not like "%picado%" AND Atributos not like "%almohada%" 
					AND Atributos not like "%espuma para tapizar%" AND Atributos not like "%patas a medida metalicas%"';
			
			}elseif (Tools::getValue('section_selected')==2){	
				//Tapizado
				$this->_where .= ' GROUP BY Atributos, Grosor, Ancho, Largo';
				$this->_where .= ' HAVING (Atributos like "%cabecero%" or Atributos like "%canape%" or Atributos like "%base%" or Atributos like "%baul%" 
					or Atributos like "%monaco%" or Atributos like "%tapa%" or Atributos like "%tapizar%")';	
			}elseif (Tools::getValue('section_selected')==3){	
				//Soldadura
				//$this->_where .= ' HAVING id_factory_state<>8';
				//$sql='SELECT DISTINCT id_order FROM '._DB_PREFIX_.'factory_state_order WHERE id_factory_state=8;';
				$sql='SELECT DISTINCT fso.id_order FROM '._DB_PREFIX_.'factory_state_order fso,'._DB_PREFIX_.'orders o WHERE fso.id_order=o.id_order '.$filtro_estados.' AND id_factory_state=8';
				if($resNoId = Db::getInstance()->ExecuteS($sql)){
					$filterNoInIds = ' AND a.id_order NOT IN(';	
					foreach($resNoId as $Ides){
						$filterNoInIds .= $Ides['id_order'].',';  
					}
					$filterNoInIds = substr($filterNoInIds,0,strlen($filterNoInIds)-1).')';
				}
				$this->_where .= $filterNoInIds;
				$this->_where .= ' GROUP BY Atributos, Grosor, Ancho, Largo';
				$this->_where .= ' HAVING (Atributos like "%canape%" or Atributos like "%base%" or Atributos like "%patas a medida%" or Atributos like "%tapa%")';
			}elseif (Tools::getValue('section_selected')==4){
				//Corte Sofá
				//$this->_where .= ' HAVING id_factory_state<>5';	
				//$sql='SELECT DISTINCT id_order FROM '._DB_PREFIX_.'factory_state_order WHERE id_factory_state=5;';
				$sql='SELECT DISTINCT fso.id_order FROM '._DB_PREFIX_.'factory_state_order fso,'._DB_PREFIX_.'orders o WHERE fso.id_order=o.id_order '.$filtro_estados.' AND id_factory_state=5';
				if($resNoId = Db::getInstance()->ExecuteS($sql)){
					$filterNoInIds = ' AND a.id_order NOT IN(';	
					foreach($resNoId as $Ides){
						$filterNoInIds .= $Ides['id_order'].',';  
					}
					$filterNoInIds = substr($filterNoInIds,0,strlen($filterNoInIds)-1).')';
				}
				$this->_where .= $filterNoInIds;
				$this->_where .= ' GROUP BY Atributos, Grosor, Ancho, Largo';
				$this->_where .= ' HAVING (Atributos like "%sofa%" or Atributos like "%cojin%" or Atributos like "%puf%" or Atributos like "%puff%" or Atributos like "%pouf%")';
			}elseif (Tools::getValue('section_selected')==5){
				//Corte Funda
				//$this->_where .= ' HAVING id_factory_state<>4';		
				//$sql='SELECT DISTINCT id_order FROM '._DB_PREFIX_.'factory_state_order WHERE id_factory_state=4;';
				$sql='SELECT DISTINCT fso.id_order FROM '._DB_PREFIX_.'factory_state_order fso,'._DB_PREFIX_.'orders o WHERE fso.id_order=o.id_order '.$filtro_estados.' AND id_factory_state=4';
				if($resNoId = Db::getInstance()->ExecuteS($sql)){
					$filterNoInIds = ' AND a.id_order NOT IN(';	
					foreach($resNoId as $Ides){
						$filterNoInIds .= $Ides['id_order'].',';  
					}
					$filterNoInIds = substr($filterNoInIds,0,strlen($filterNoInIds)-1).')';
				}
				$this->_where .= $filterNoInIds;
				$this->_where .= ' GROUP BY Atributos, Grosor, Ancho, Largo';
				$this->_where .= ' HAVING (Atributos like "%esparta%" or Atributos like "%loneta%" or Atributos like "%tela%" or Atributos like "%rollo%" 
					or Atributos like "%chenilla%" or Atributos like "%plegable%" or Atributos like "%palet%" or Atributos like "%deluxe%" or Atributos like "%variado%" 
					or Atributos like "%tela chocolate%" or Atributos like "%tela cuadros%" or Atributos like "%contract%" or Atributos like "%plus%" 
					or Atributos like "%lino%" or Atributos like "%pachtwork%" or Atributos like "%patchwork%" or Atributos like "%impermeable%" 
					or Atributos like "%rustika%" or Atributos like "%exterior%" or Atributos like "%puff cama%" or Atributos like "%polipiel normal%" 
					or Atributos like "%premium%" or Atributos like "%perro%")';
				//palabras a excluir para corte funda
				$this->_where .= ' AND (Atributos not like "%colchon plegable con asa y cierre%" and Atributos not like "%loneta premium gris%")'; 	
			}elseif (Tools::getValue('section_selected')==6){
				//Costura
				//$this->_where .= ' HAVING id_factory_state<>5 AND id_factory_state<>7 AND id_factory_state<>2';		
				$sql='SELECT DISTINCT id_order FROM '._DB_PREFIX_.'factory_state_order WHERE id_factory_state=5 OR id_factory_state=7 OR id_factory_state=2;';
				$sql='SELECT DISTINCT fso.id_order FROM '._DB_PREFIX_.'factory_state_order fso,'._DB_PREFIX_.'orders o WHERE fso.id_order=o.id_order '.$filtro_estados.' AND (id_factory_state=5 OR id_factory_state=7 OR id_factory_state=2)';
				if($resNoId = Db::getInstance()->ExecuteS($sql)){
					$filterNoInIds = ' AND a.id_order NOT IN(';	
					foreach($resNoId as $Ides){
						$filterNoInIds .= $Ides['id_order'].',';  
					}
					$filterNoInIds = substr($filterNoInIds,0,strlen($filterNoInIds)-1).')';
				}
				$this->_where .= $filterNoInIds;
				//palabras claves para costuras
				$this->_where .= ' GROUP BY Atributos, Grosor, Ancho, Largo';
				$this->_where .= ' HAVING (Atributos like "%cabecero%" or Atributos like "%canape%" or Atributos like "%baul%" or Atributos like "%monaco%" 
					or Atributos like "%colchon%" or Atributos like "%funda%" or Atributos like "%strecht%" or Atributos like "%topper%" or Atributos like "%powerloom%" 
					or Atributos like "%polipiel%" or Atributos like "%cuna%" or Atributos like "%cambiadores%" or Atributos like "%harrier%" or Atributos like "%tela%")';
				//palabras a excluir para costuras
				$this->_where .= ' AND (Atributos not like "%cabecero y piecero%" AND Atributos  not like "%100% impermeable%" 
					AND Atributos not like "%polipiel impermeable%" AND Atributos not like "%sin funda%" AND Atributos not like "%loneta%" AND Atributos not like "%sin colchon%" 
					AND Atributos not like "%hidraulico%" AND Atributos not like "%deluxe%")';	
			}elseif (Tools::getValue('section_selected')==7){
				//Pegado
				//$this->_where .= ' HAVING id_factory_state<>3';	
				//$sql='SELECT DISTINCT id_order FROM '._DB_PREFIX_.'factory_state_order WHERE id_factory_state=3;';
				$sql='SELECT DISTINCT fso.id_order FROM '._DB_PREFIX_.'factory_state_order fso,'._DB_PREFIX_.'orders o WHERE fso.id_order=o.id_order '.$filtro_estados.' AND id_factory_state=3';
				if($resNoId = Db::getInstance()->ExecuteS($sql)){
					$filterNoInIds = ' AND a.id_order NOT IN(';	
					foreach($resNoId as $Ides){
						$filterNoInIds .= $Ides['id_order'].',';  
					}
					$filterNoInIds = substr($filterNoInIds,0,strlen($filterNoInIds)-1).')';
				}
				$this->_where .= $filterNoInIds;
				//palabras claves para pegado
				$this->_where .= ' GROUP BY Atributos, Grosor, Ancho, Largo';
				$this->_where .= ' HAVING (Atributos like "%visco%" or Atributos like "%viscoelastica%" or Atributos like "%extreme%" or Atributos like "%pegado%" 
					or Atributos like "%10+5%")';
				//palabras a excluir para costuras					
				$this->_where .= ' AND (Atributos not like "%picado%" AND Atributos not like "%copo%" AND Atributos not like "%plancha%"  
					AND Atributos not like "%topper%"  AND Atributos not like "%viscoelastica a medida%" AND Atributos not like "%cojin%" 
					AND Atributos not like "%sin viscoelastica%" AND Atributos not like "%almohada%")';	
				$this->_where .= ' AND id_factory_state<>2';	//Para que no salgan dobles los pedidos que ya tienen el cortado espuma y el cortado tapizado				
			}elseif (Tools::getValue('section_selected')==8){
				//Express y Vencimientos
				$this->_where .= ' GROUP BY Atributos, Grosor, Ancho, Largo';
				$this->_where .= ' HAVING (Mensaje_del_cliente like "%vto%" OR Tran like "%ex%")';		
			}elseif (Tools::getValue('section_selected')==9){
				$this->_where .= ' GROUP BY Atributos, Grosor, Ancho, Largo';
				$this->_where .= ' HAVING Mensaje_del_cliente not like "%vto%" AND Tran not like "%ex%"';		
				//Sin express y sin vencimientos
			}elseif (Tools::getValue('section_selected')==10){
				//Todos
				$this->_where .= '';		
			}		
		}	
					
		if (Tools::getValue('massive_action')) {
			//echo 'HOLA<br/>';
			//echo 'Lo que devuelve: '.Tools::getValue('productionBox').'<br/>';
            //$orders = Tools::getValue('productionBox');
			if (is_array($_POST['productionBox'])) {
				
				$orders = $_POST['productionBox'];

				$this->changeStateOrders($orders, Tools::getValue('massive_action'));
				$option= Tools::getValue('section_selected');
			}else{
				echo "no es un array";
			}
        }		

		if (Tools::getValue('exportlist')==1) {
			//echo 'hola';
			include(_PS_MODULE_DIR_ . 'production_orders/classes/Exportlist.php');
			Exportlist::export($option, $ostat, $this->_where, $this->_join, $this->_filter, $this->_orderBy, $this->_orderWay);	
		}	
		
		$this->context->smarty->assign(
            array(
				'request_uri' => $_SERVER['REQUEST_URI'],
                'sectiones' => $sections,
				'option' => $option,
				'states' => $states,
				'ostat' => $ostat
            )); 
			
		$html .= $smarty->fetch(_PS_MODULE_DIR_ . 'production_orders/views/templates/admin/sections.tpl');
		
		
		$this->context->smarty->assign(
            array(
           
				'url' => Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.'modules/production_orders/',
				'section' =>$option,
				'request_uri' => $_SERVER['REQUEST_URI'],
				'ostat' => $ostat
            ));
		$html .= $smarty->fetch(_PS_MODULE_DIR_ . 'production_orders/views/templates/admin/form.tpl');			
			

		$html .= parent::renderList();
		
				
		$this->context->smarty->assign(
            array(
				'option' => $option
			));	
		
        $html .= $smarty->fetch(_PS_MODULE_DIR_ . 'production_orders/views/templates/admin/massives.tpl');;

        return $html;
	}
	
	
	
	// Codigo Alejandro
	public function organizadorEspumas($objPHPExcel,$result,$info_col){
		// $info_col es la cabecera
		
		echo '<script>';
		echo 'console.log(" -- '.count($info_col).'");';
		echo '</script>';
		
		
		$info_col[0] = str_replace("a", "A", $cadena);
		
		
		// Ahora debes clasificar las filas en "rows" en funcion de la densidad, probamos con arraylists
        // Se clasifican las filas en funcion de la columna 6 que es donde se almacena si el producto tiene densidad 20, 25, etc
		$d20 = array();
		$d23 = array();
		$d25 = array();
		$d30 = array();
		$d35 = array();
		$otros = array();
		
		$articulos_por_pedido = array();
		$id_pedido = array();
			
		// Preparamos los String que definen las expresiones regulares para extraer informacion de los campos de atributos de la base de datos
		$x_exp = "[0-9]*\\s*x\\s*[0-9]*\\s*x\\s*[0-9]*\\s*cm";
		$letras_exp = "A:\\s*[0-9]*\\s*cm-\\s*B:\\s*[0-9]*\\s*cm-\\s*C:\\s*[0-9]*\\s*cm-";
		$comerciales_exp = "ANCHO:\\s*[0-9]*\\s*CM\\s*X\\s*LARGO:\\s*[0-9]*\\s*CM\\s*X\\s*GROSOR TOTAL:\\s*[0-9]*\\s*CM";
		
		//Iniciamos la clasificacion por densidad
		$cont = 0;
		$old_order = "000000";
		
		foreach ($result as $fila) {
			echo '<script>';
			echo 'console.log(" - '.$fila['Atributos'].'");';
			echo '</script>';
		}
		
		/*$res = '';
		
		foreach ($result as $fila) {
			foreach ($fila as $columna) {
				$res = $res .' - '. $columna;
			}
			echo '<script>';
			echo 'console.log(" -- '.$res.'");';
			echo '</script>';
			$res = '';
		}*/
		
	}
	
		
	public function changeStateOrders($orders, $state){
		 if(!isset($orders) || !is_array($orders))
            $orders = array();

		//echo 'Numero de pedidos marcados: '.count($orders);
        foreach ($orders as $id_order) {
			
            $success = (int)$this->setCurrentStateFactory($id_order, $state);

            if($success){
                $changes[] = $success;
            }
			
			
			
        }

     //   return $changes;
	}
		
    public function setCurrentStateFactory($id_order, $id_factory_state, $id_employee = 0)
    {
        if (empty($id_order) || empty($id_factory_state)) {
            return false;
        }
		$res = Db::getInstance()->getRow('
			SELECT max(`id_factory_state_order`) as id
			FROM `'._DB_PREFIX_.'factory_state_order`');
		
		/*echo '<br/>
			Insert into '._DB_PREFIX_.'factory_state_order values(
			"'.$res['id'].'","'.$id_factory_state.'","'.$id_order.'","'.$id_employee.'","'.date("Y-m-d H:i:s").'")';*/
		Db::getInstance()->execute('
			Insert into '._DB_PREFIX_.'factory_state_order values(
			"'.($res['id']+1).'","'.$id_factory_state.'","'.$id_order.'","'.$id_employee.'","'.date("Y-m-d H:i:s").'")');					
    }
	
	public function getOrderState($order){
		$lng = (int)(Configuration::get('PS_LANG_DEFAULT'));
		//17-cortado sofás; 18-cosidos sofás; 29-cortado tapizado; 49-cortado y cosido colchones; 207-cortado espuma; 276-cortado fundas; 296-coldadura; 299-pegado
       $res = Db::getInstance()->ExecuteS("SELECT  `id_order_state`,`name` FROM `"._DB_PREFIX_."order_state_lang` WHERE `id_lang` = ".$lng." AND (NAME LIKE 'Carga%' OR NAME LIKE 'Produccion%')");
	   $selord = explode(',',$order);
        $sout = '';
          foreach ($res as $row){
          $sel = '';
          if(in_array($row['id_order_state'], $selord)) {$sel = 'selected="selected"';};
           $sout .= '<option value="'.(string)($row['id_order_state']).'"'.$sel.'>'.(string)($row['name']).'</option>'; 
          }
    return $sout;
	}
	
	public function getOrderState2($id_lang){
		$cache_id = 'OrderState::getOrderStates_'.(int)$id_lang;
		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT *
			FROM `'._DB_PREFIX_.'order_state` os
			LEFT JOIN `'._DB_PREFIX_.'order_state_lang` osl ON (os.`id_order_state` = osl.`id_order_state` AND osl.`id_lang` = '.(int)$id_lang.')
			WHERE (NAME LIKE "Carga%" OR NAME LIKE "Produccion%") AND deleted = 0
			ORDER BY `name` DESC');
            Cache::store($cache_id, $result);
        return $result;
	}
	
	public function getFactorySection(){
		
		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('SELECT *
			FROM `'._DB_PREFIX_.'factory_section`
			WHERE deleted = 0
			ORDER BY `id_factory_section` ASC');
		return $result;
	}
	
	
}
?>