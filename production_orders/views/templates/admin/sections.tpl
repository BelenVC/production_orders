<form name="form-order1" id="form-order1" method="POST" action="{$request_uri}">
<h1>Producción de pedidos - Fábrica</h1>
<div style="text-align:center; clear:both">
    <div class="acciones-masivas">
		<div class="secciones">
			<span class="texto-elegir-seccion">{l s='Sections' mod='production_orders'}:</span><br/>
			{*<select class="select-section-factory" name="section_change" id="section_change">*}
			<select class="select-section-factory" name="section_selected" id="section_selected">
				<option value="">Elige Sección de Trabajo</option>
				{foreach from=$sectiones item=section}
					<option value="{$section.id_factory_section}"{if $section.id_factory_section==$option}selected="true"{/if}>{$section.factory_section}</option>
				{/foreach}						
			</select>
		</div>
		<div class="estados">
			<select class="select-state-factory" multiple name="select_estados[]" id="select_estados">
				{foreach from=$states item=state}
					<option value="{$state.id_order_state}"{if in_array($state.id_order_state, $ostat)}selected="true"{/if}>{$state.name}</option>
				{/foreach}
			</select>
		</div>
		<br/>
        <button class="boton-elegir-seccion btn btn-default btn-disk" id="exec-elegir-seccion">{l s='Select Section Factory' mod='production_orders'}</button>
            
    </div>
</div>
</form>