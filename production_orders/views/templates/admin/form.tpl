		    
<form id= "production_orders" action={$request_uri} method="post">
	<p>&nbsp;</p>	  
	<fieldset>
		<div class="col-lg-2"> 
			<a class="buttongdescargarpedidos" href="{$request_uri|escape:'htmlall':'UTF-8'}&exportlist=1&section_selected={$section}&select_estados={serialize($ostat)}" target="_blank">{l s='Download Orders' mod='production_orders'}</a>
		</div>	 		
	</fieldset> 
	<p>&nbsp;</p>	  
</form>
