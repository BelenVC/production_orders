<?

class Exportlist{
	
	private function stripAccents($string){//**** Quitar acentos *****
		echo '777HOLA'. $string.'</br>';
		$tofind = "ÀÁÂÄÅàáâäÒÓÔÖòóôöÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ";
        $replac = "AAAAAaaaaOOOOooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn";
        return utf8_encode(strtr(utf8_decode($string), utf8_decode($tofind), $replac));
	}
	
	public function export($seccion, $estados, $where, $join, $filter, $orderBy, $orderWay){
		
		echo count($estados)."<br/>";
		$where2='';
		if (count($estados)>0){
			for ($i=0;$i<count($estados);$i++){
				if($i==0){
					$where2 .= 'AND (s.id_order_state='.$estados[$i];
				}else{
					$where2 .= ' OR s.id_order_state='.$estados[$i];
				}						
			}
			$where2 .= ')';
		}
		
		$sql = "SELECT a.id_order as Pedido,
		DATE_FORMAT(a.date_add, '%d/%m/%Y %H:%i:%s') as Fecha,
		concat(c.firstname, ' ', c.lastname) as Cliente,
		mpc.quantity as CantMg,
		'' as NombreP,
		CONCAT(od.product_name,' ',if(_splitear(mpc.attributes,'-')IS NOT NULL,_splitear(mpc.attributes,'-'),'')) as Atributos,
		truncate(mpc.length,2) as Grosor,
		truncate(mpc.width,2) as Ancho,
		truncate(mpc.height,2) as Largo,
		'' as NotaPrivada,
		If(a.payment='Amazon MarketPlace',CONCAT('AMAZON=>> ',_unir_mensajes(ct.id_customer_thread)),_unir_mensajes(ct.id_customer_thread)) as Mensaje_del_cliente,
		s.name as Estado,
		od.product_quantity as CantPS,
		ca.name as Tran,
		IF(fs.id_factory_state IS NOT NULL,fs.id_factory_state,0) AS id_factory_state,
		s.id_order_state
		FROM ps_orders a ".$join." WHERE 1 ".$filter." ".$where2." ".$where. " ORDER BY ".$orderBy." ".$orderWay; 
		echo $sql.'<br/>';
		$result = Db::getInstance()->ExecuteS($sql);
		$cabecera = Db::getInstance()->getRow($sql);
		
		
		
		$info_col = array_keys($cabecera);
		
		if(!empty($result)) {
			
			include(_PS_MODULE_DIR_ . 'production_orders/classes/PHPExcel.php');
			$objPHPExcel = new PHPExcel();
			$objPHPExcel->getProperties()
				->setCreator("Ventadecolchones.com")
				->setLastModifiedBy("Ventadecolchones.com")
				->setTitle("Excel Download List orders");
			//$objPHPExcel->removeSheetByIndex(0);
			$listxlsx = $objPHPExcel->createSheet();
			$listxlsx->setTitle('Todo');
			$objPHPExcel->setActiveSheetIndex(0);
			$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.25);
			$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.05);
			$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.05);
			$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.25);
			$objPHPExcel->getActiveSheet()->getPageMargins()->setHeader(0);
			$objPHPExcel->getActiveSheet()->getPageMargins()->setFooter(0);
			$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
			$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
			$objPHPExcel->getActiveSheet()->getStyle('H2:H4364')->getAlignment()->setWrapText(true); 
			$objPHPExcel->getActiveSheet()->getStyle('M2:M4364')->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&C&H Producción de pedidos');
			$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B Fecha de impresión: '.date('d-m-Y'). ' ' .date('H').':'.date('i').':'.date('s'));
			
			if($seccion==99){//1-Espumas
				$objPHPExcel = $this->organizadorEspumas($objPHPExcel,$result,$info_col);
			}else{
			
			
				$i=0; //columna 
				$j=1; //fila
				$info_columna = array_keys($cabecera);
				foreach ($info_columna as $title){
					//imprimo la cabecera		
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i+2,$j,$title);
					$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i+2,$j)->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i+2,$j)->getFont()->getColor()->setARGB('003030CF');
					$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i+2,$j)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);  
					$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i+2,$j)->getFill()->getStartColor()->setARGB('00FBF4A4');
					$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(0)->setWidth(4);
					$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(1)->setWidth(4);
					$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(2)->setWidth(6);
					$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(3)->setWidth(3);
					$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(4)->setWidth(3);
					$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(5)->setWidth(3);
					$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(6)->setWidth(0);
					$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(7)->setWidth(53);
					$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(8)->setWidth(4);
					$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(9)->setWidth(4);
					$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(10)->setWidth(4);
					$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(11)->setWidth(0);
					$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(12)->setWidth(43);
					$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(13)->setWidth(4);
					$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(14)->setWidth(3);
					$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(15)->setWidth(4);
					$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(16)->setWidth(0);
					$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(17)->setWidth(0);
					$i++;
				}
				$j=$j+1;
				$i=2;
				$ultimo='';
				
				foreach ($result as $row){
					//imprimo los datos
					foreach ($info_columna as $title){
						if ($title=="Pedido"){
							if($seccion!=1){ //Para que la separacion entre pedido no se haga si estamos en espumas. Controlado para que la programacion de Alejandro no de error.
								if ($ultimo==$row[$title]){
									$ultimo=$row[$title];
								}else{
									if ($j!=2){
										$objPHPExcel->getActiveSheet()->getRowDimension($j)->setRowHeight(3);
										$objPHPExcel->getActiveSheet()->getStyle('A'.$j.':P'.$j)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('00000000');
										$j++;
									}	
									$ultimo=$row[$title];	
								}
							}	
							
							//echo 'HOLA'. $row[$title].'</br>';
							$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i,$j,$row[$title]);							
						}elseif ($title=="Atributos"){
							$advertencia = '';
							//echo $title.'0: '. $row[$title].'</br>';
							$aux = utf8_encode(strtr(utf8_decode($row[$title]), utf8_decode("ÀÁÂÄÅàáâäÒÓÔÖòóôöÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ"), "AAAAAaaaaOOOOooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn"));
							//echo $title.'1: '. $row[$title].'</br>';
							if (strpos(strtolower($aux),'oferton') || strpos(strtolower($aux),'cod')){
								$advertencia='OJO! NO HACER,ARTÍCULO DE OUTLET. ';
							}else{
								if(strpos(strtolower($aux),'exposicion')){
									$advertencia='OJO! NO HACER,ARTÍCULO DE EXPOSICIÓN. ';
								}else{
									if(strpos(strtolower($aux),'topper plegable')){
										$advertencia='OJO! TOPPER PLEGABLE. ';
									}else{		
										$kvmdf = str_replace(' ','',strtolower($aux));
										$sq = "SELECT ppl.name, psa.quantity FROM ps_product_lang ppl,ps_product pp, ps_stock_available psa WHERE ppl.id_product=pp.id_product AND pp.id_product=psa.id_product AND ppl.id_lang=1 AND REPLACE(REPLACE(ppl.name,' ',''),'€','?') LIKE '%". $kvmdf ."%' AND psa.quantity>=0 AND pp.id_category_default=108 AND pp.active=1 ORDER BY ppl.id_product desc";
										$resadv = Db::getInstance()->ExecuteS($sq);
										if (count($resadv)>0){
											$advertencia='ARTÍCULO HECHO. MIRAR STOCK. ';
										}
									}	
								}								
							}
							//echo $title.'1: '. $row[$title].'</br>';
							if (strpos(strtolower($aux),'si (+30')){
								$advertencia=$advertencia .'OJO! CORTE SEGUN CROQUIS!. ';
							}
							//echo $title.'2: '. $row[$title].'</br>';
							$kvmdf = str_replace(' ','',strtolower($aux));
							if (strpos($kvmdf,'viscoelasticoimpermeable')){
								$advertencia=$advertencia .'OJO! COLCHÓN CON FUNDA IMPERMEABLE!. ';
							}					
							//echo 'HOLA'. $row[$title].'</br>';
							$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i,$j,$advertencia . " " . $row[$title]);
						}elseif ($title=="Estado"){
							if ($row[$title]=="Producción CS"){
								$kv="CS ";
							}elseif($row[$title]=="Producción Recogida en Fábrica"){
								$kv = 'OJO!! RECOGIDA EN FÁBRICA =>> ' . $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(12, $j)->getFormattedValue();
								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12,$j,$kv);
								$kv="RF";
							}
							//echo 'HOLA'. $row[$title].'</br>';
							$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i,$j,$kv);
						}elseif ($title=="Tran"){
							if ($row[$title]=='SEUR' or $row[$title]=='Envío Estándar'){
								$kv="S";
							}
							if ($row[$title]=='SEUR - EXPRESS' or $row[$title]=='Envío EXPRESS'){
								$kv="EX"; 
								$objPHPExcel->getActiveSheet()->getStyle('C'.$j.':P'.$j)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('00bdbdbd');
							}
							if ($row[$title]=='Subida y montaje de los artículos'){
								$kv="AB"; 
							}
							if ($row[$title]=='Envío con subida al domicilio mediante empresa especializada'){
								$kv="AB"; 
							}
							if ($row[$title]=='Envío con subida y montaje mediante empresa especializada'){
								$kv="AB"; 
							}
							if ($row[$title]=='Medios Propios Sofás.'){
								$kv="MP"; 
							}
							if ($row[$title]=='Medios Propios.'){
								$kv="MP";
							}
							if ($row[$title]=='SEUR con Subida al domicilio' or $row[$title]=='Envío con Subida al domicilio'){
								$kv = 'MOZO. Enviar por Seur =>> ' . $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(12, $j)->getFormattedValue();
								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12,$j,$kv);
								$kv="S";
							}
							if (strpos($row[$title], "Baleares")!==false){
								$kv = 'BALEARES =>> ' . $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(12, $j)->getFormattedValue();
								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12,$j,$kv);
								$kv = "S";
							}
							$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i,$j,$kv);
							//echo 'HOLA'. $row[$title].'</br>';
						}else{			
							$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i,$j,$row[$title]);
							//echo 'HOLA else '.$title.': '. $row[$title].'</br>';
						}
						$i++;					
					}
					$j++;
					$i=2;
				}
				$objPHPExcel->getActiveSheet()->getStyle('A1:P'.($j-1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				
				$border_style= array('borders' => array('allborders' => array('style' => 
						PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));
				$sheet = $objPHPExcel->getActiveSheet();
				$sheet->getStyle("A1:P".($j-1))->applyFromArray($border_style);
				
				//SEGUNDA HOJA
				
			
				if ($seccion==2){
					//Tapizado
					$listxlsx = $objPHPExcel->createSheet();
					$listxlsx->setTitle('Trabajos');
					$objPHPExcel->setActiveSheetIndex(1);
					$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.25);
					$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.05);
					$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.05);
					$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.25);
					$objPHPExcel->getActiveSheet()->getPageMargins()->setHeader(0);
					$objPHPExcel->getActiveSheet()->getPageMargins()->setFooter(0);
					$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
					$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
					$col = 1;
					$row = 1;
					$tapiceros = array('Florin','Pedro','Paka','Mihai','Arturo','Tono','Augusto','Dani','Gregorio');
					foreach($tapiceros as $nom){
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $nom);
						$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->getFont()->setBold(true);
						$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->getFont()->getColor()->setARGB('003030CF');
						$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);  
						$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->getFill()->getStartColor()->setARGB('00FBF4A4');	
						$row++;
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, 'Valor');
						$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->getFont()->setBold(true);
						$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->getFont()->getColor()->setARGB('003030CF');
						$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);  
						$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->getFill()->getStartColor()->setARGB('00FBF4A4');	
						$col++;
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, 'Cant.');
						$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->getFont()->setBold(true);
						$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->getFont()->getColor()->setARGB('003030CF');
						$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);  
						$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->getFill()->getStartColor()->setARGB('00FBF4A4');	
						
						$col++;
						$row=$row-1;
					}
					
					$row = 2;
					$tapizado=array('lisse largo','diamond largo','tablet largo','cube largo','niza largo','drago con aireadores','serena','extreme','paris','lisboa','lisse','diamond','tablet','cube','niza','ying yang','franklin','tavira','drago','aire');
					foreach($tapizado as $art){
						$row++;
						$objPHPExcel->getActiveSheet()->setCellValue('A'. $row, $art);
					}
					//Formulas
					$col = 1;
					foreach($tapiceros as $nom){
						$row = 2;
						foreach($tapizado as $art){
							$importe="1";
							switch ($art){
								case "lisse":
									$importe = "1,95";
									break;
								case "diamond":
									$importe = "16,65";
									break;	
								case "tablet":
									$importe = "3,75";
									break;
								case "cube":
									$importe = "6,15";
									break;
								case "niza":
									$importe = "2,7";
									break;
								case "ying yang":
									$importe = "3,6";
									break;
								case "franklin":
									$importe = "18";
									break;
								case "tavira":
									$importe = "6,15";
									break;
								case "lisse largo":
									$importe = "6,9";
									break;
								case "diamond largo":
									$importe = "20,4";
									break;
								case "tablet largo":
									$importe = "5,7";
									break;
								case "cube largo":
									$importe = "10,65";
									break;
								case "niza largo":
									$importe = "6,9";
									break;
								case "extreme":
									$importe = "25,5";
									break;
								case "paris":
									$importe = "3,75";
									break;
								case "lisboa":
									$importe = "15,15";
									break;	
								case "serena":
									$importe = "13,35";
									break;
								case "drago":
									$importe = "3,45";
									break;
								case "aire":
									$importe = "3,45";
									break;
								case "drago con aireadores":
									$importe = "5,4";
									break;	
								case "tapa":
									$importe = "3,9";
									break;
								case "Base":
									$importe= "3,83";
									
							}			
							 
							$row++;
							$formula= '=';
							//$formula= '=countifs(Todo!Z2:Todo!Z250;"'.$nom.'";Todo!H2:Todo!H250;"'.$art.'*")+CONTAR.SI.CONJUNTO(Todo!Z2:Todo!Z250;"'.$nom.'";Todo!H2:Todo!H250;"'.$art.'*";Todo!E2:Todo!E250;">1")+CONTAR.SI.CONJUNTO(Todo!Z2:Todo!Z250;"'.$nom.'";Todo!H2:Todo!H250;"'.$art.'*";Todo!E2:Todo!E250;">2")+CONTAR.SI.CONJUNTO(Todo!Z2:Todo!Z250;"'.$nom.'";Todo!H2:Todo!H250;"'.$art.'*";Todo!E2:Todo!E250;">3")';
							$formula= '&(CONTAR.SI.CONJUNTO(Todo!AA2:AA250;"'.$nom.'";Todo!Z2:Z250;"'.$art.'")+CONTAR.SI.CONJUNTO(Todo!AA2:AA250;"'.$nom.'";Todo!Z2:Z250;"'.$art.'";Todo!E2:E250;">1")+CONTAR.SI.CONJUNTO(Todo!AA2:AA250;"'.$nom.'";Todo!Z2:Z250;"'.$art.'";Todo!E2:E250;">2")+CONTAR.SI.CONJUNTO(Todo!AA2:AA250;"'.$nom.'";Todo!Z2:Z250;"'.$art.'";Todo!E2:E250;">3"))*'.$importe;
							$formulacant= '&(CONTAR.SI.CONJUNTO(Todo!AA2:AA250;"'.$nom.'";Todo!Z2:Z250;"'.$art.'")+CONTAR.SI.CONJUNTO(Todo!AA2:AA250;"'.$nom.'";Todo!Z2:Z250;"'.$art.'";Todo!E2:E250;">1")+CONTAR.SI.CONJUNTO(Todo!AA2:AA250;"'.$nom.'";Todo!Z2:Z250;"'.$art.'";Todo!E2:E250;">2")+CONTAR.SI.CONJUNTO(Todo!AA2:AA250;"'.$nom.'";Todo!Z2:Z250;"'.$art.'";Todo!E2:E250;">3"))';
							
							$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $formula);	
							$col=$col+1;
							$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $formulacant);
							$col = $col - 1;
						}	
						$col=$col+2;
					}
					$objPHPExcel->setActiveSheetIndex(0);
				}
			}	
			$onef = "_".time();
			$fname = "orders-".date("Y-m-d").$onef ;
			//ob_start();
			$directory = _PS_MODULE_DIR_.'production_orders/controllers/admin/download/';
			
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save($directory.$fname.'.xlsx');
			
			if (file_exists($directory.$fname.'.xlsx') && ($fp = Tools::file_get_contents($directory.$fname.'.xlsx')))
			{
				ob_end_clean();
				header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment; filename='.$fname.'.xlsx');
				header('Content-Transfer-Encoding: binary');
				header('Accept-Ranges: bytes');
	
				echo $fp;
				exit;
			}			
			
			$objPHPExcel->disconnectWorksheets();


			unset($objPHPExcel);
			
		}else{

			echo "No hay datos a exportar";

		}
	
	}
	
	
}

?>