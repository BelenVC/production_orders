<div style="text-align:center; clear:both">
    <div class="acciones-masivas">
            <span class="texto-acciones-masivas">{l s='Massive acctions' mod='production_orders'}
                            <select class="select-acciones-masivas" name="massive_change" id="massive_change">
                                <option value="">Elige Acción</option>
                                <option value="1">{l s='Cut Foam' mod='production_orders'}</option>
                                <option value="4">{l s='Cut Case' mod='production_orders'}</option>
								<option value="5">{l s='Cut Sofa' mod='production_orders'}</option>
								<option value="2">{l s='Cut Upholstered' mod='production_orders'}</option>
								<option value="7">{l s='Cut and sewn mattresses' mod='production_orders'}</option>
								<option value="6">{l s='Sewn sofa' mod='production_orders'}</option>
								<option value="3">{l s='Glued' mod='production_orders'}</option>
								<option value="8">{l s='Welding' mod='production_orders'}</option>
                            </select>
							<input type="hidden" name="section" id="section" value="{$option}">
                        <button class="boton-masivas btn btn-default btn-disk" id="exec-acciones-masivas">{l s='Execute massives' mod='production_orders'}</button>
            </span>
    </div>
</div>