{if isset($estadosFab)}
	
	<div class="row">
        <div class="col-lg-12">

            <div class="panel">
                <div class="panel-heading">
					<img src="{$path}logo1.gif" alt="" title="" />FÁBRICA
                </div>
				
				<!-- Tab nav -->
                <ul id="esp_dhl_tabla_seguimiento" class="nav nav-tabs">
                    <li class="active">
                        <a href="#fabrica"><i class="icon-time"></i> {l s='Fábrica' mod='production_orders'}</a>
                    </li>
                    <li>
                        <a href="#opciones"><i class="icon-file-text"></i> {l s='Opciones' mod='production_orders'}</a>
                    </li>
                </ul>
				
				<!-- Tab content -->
                <div class="tab-content panel">
                    <!-- Pestaña Fabrica-->
                    <div id="fabrica" class="tab-pane active">
                        <!-- History of status -->
                        {if isset($estadosFab)}
                            <div class="table-responsive">
                                <table id="factory_table" class="table history-status row-margin-bottom">
                                    <thead>
                                        <tr>
                                            <th>
                                                <span class="title_box ">{l s='Estado' mod='production_orders'}</span>
                                            </th>
                                            <th>
                                                <span class="title_box ">{l s='Empleado' mod='production_orders'}</span>
                                            </th>
                                            <th>
                                                <span class="title_box ">{l s='Fecha' mod='production_orders'}</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {foreach from=$estadosFab item=estado} 
                                            <tr>
                                                <td>{$estado['factory_state']|stripslashes}</td>
                                                <td>{$estado['firstname']|stripslashes} {$estado['lastname']|stripslashes}</td>
                                                <td>{dateFormat date=$estado['date_upd'] full=true}</td>
                                            </tr>
                                        {/foreach}
                                    </tbody>
                                </table>
                            </div>
                        {/if}
					</div>	
						
					<!-- Pestaña Opciones -->
                    <div id="opciones" class="tab-pane">
                        <div class="table-responsive">
						
						</div>
                    </div> <!-- / Pestaña Opciones -->
                </div>
			</div>
        </div>
    </div>	
{/if}
