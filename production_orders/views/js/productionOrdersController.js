$(document).ready(function () {
    var numCheck =  $(".order").find("input[type=checkbox]").length;
	console.log('numCheck: '. numCheck);
    if(numCheck==0) {
        $(".order tbody tr").each(function(){
            var id = $(this).find("td").first().html().trim();

            if($(this).find("td").length>1) {
                $(this).prepend("<td class='row-selector text-center'><input type='checkbox' name='productionBox[]' id='" + id + "' value='" + id + "' class='noborder'></td>");
            }
        });

        $(".order thead tr").prepend("<th class='center fixed-width-xs'><input type='checkbox' name='productionAll' value='0' class='noborder'></th>");
    }
    $("#exec-acciones-masivas").on('click', function(){
        $("#form-order").append("<input type='hidden' name='massive_action' value='"+$("#massive_change").val()+"'>");
		$("#form-order").append("<input type='hidden' name='section_selected' value='"+$("#section").val()+"'>");
        $("#form-order").submit();
    });
	$("#exec-elegir-seccion").on('click', function(){
        //$("#form-order").append("<input type='hidden' name='section_selected' value='"+$("#section_change").val()+"'>");
        $("#form-order1").submit();
    });
});