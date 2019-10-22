/**
* 2007-2017 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2017 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*
* Don't forget to prefix your containers with your own identifier
* to avoid any conflicts with others containers.
*/


$(document).ready(function () {


    $('input[name="productionAll"]').on('click', function (){
        $('input[type="checkbox"]').prop('checked', $(this).prop('checked'));
    })



	$('input[name="productionBox[]"]').on('click', function (){
		var porId = $(this).attr("id");
		var valor = document.getElementById(porId).value;
		var filas = document.getElementsByClassName("noborder");
		if (document.getElementById(porId).checked == true){
			for(i=0; i < filas.length; i++){
				if (filas[i].value == valor){
					filas[i].checked=true;
				}
			}
		}else{
			for(i=0; i < filas.length; i++){
				if (filas[i].value == valor){
					filas[i].checked=false;
				}
			}
		}		
		
    })
});


