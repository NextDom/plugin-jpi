<?php
/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
sendVarToJS('id', init('id'));
?>
<legend>Nom</legend>
<div>
    <input class="form-control" style="width: 100%; display : inline-block;" id="cmdname" placeholder="Nom de la commande">
</div>
<br>
<!--<legend>Catégorie</legend>
<div id="idcategory"></div>
<br>-->
<legend>Actions</legend>
<div id="idactions"></div>
<br>
<legend>Paramètres obligatoires</legend>
<form id="form-parameters">
    <div id="idparameters"></div>
</form>
<br>
<legend>Paramètres optionnels</legend>
<form id="form-options">
    <div id="idoptions" ></div>
</form>
<br>
<div align="right">
    <a id="eqSave" class="btn btn-success btn"><i class="fa fa-check-circle"></i>Sauvegarder</a>
</div>
<script>
    $('#eqSave').on('click', function () {
        var cmdname = $("#cmdname").val();
        var cmdaction = $("#idactions option:selected").val();
        var cmdparameters = $("#form-parameters ").serialize();
        var cmdoptions = $("#form-options").find(":input").filter(function () {
            return $.trim(this.value).length > 0
        }).serialize();

      if(cmdname == "") {
      alert("Merci de renseigner un nom de commande !");
    }
        $.ajax({// fonction permettant de faire de l'ajax
            type: "POST", // méthode de transmission des données au fichier php
            url: "plugins/JPI/core/ajax/JPI.ajax.php", // url du fichier php
            data: {
                action: "AddCommand",
                id: id,
                name: cmdname,
                command: cmdaction,
                parameters: cmdparameters,
                options: cmdoptions,

            },
            dataType: 'json',
            error: function (request, status, error) {
                handleAjaxError(request, status, error);
            },
            success: function (data) { // si l'appel a bien fonctionné
                if (data.state != 'ok') {
                    $('#div_alert').showAlert({message: data.result, level: 'danger'});
                    return;
                }
                $('#div_alert').showAlert({message: '{{Sauvegarde réalisée avec succès}}', level: 'success'});
                $('#md_modal').dialog("close")
                $('.li_eqLogic[data-eqLogic_id=' + $('.eqLogicAttr[data-l1key=id]').value() + ']').click();
            }
        });

    });

    $(document).ready(function () {
        $.ajax({
            type: "POST",
            url: "plugins/JPI/core/ajax/JPI.ajax.php",
            data: {
                action: "getjpiActions",
                ip: $('.eqLogicAttr[data-l1key=configuration][data-l2key=jpiIp]').value(),
            },

            dataType: 'json',
            error: function (request, status, error) {
                console.log("Erreur lors de la demande");
            },
            async: false,
            success: function (data) {
                if (data.state == 'ok') {
                    var select = '';
                    var select = '<select id="idcategory" class="form-control">';

                    jsonresult = data.result.ACTIONS.CAT;
                    jsonresult2 = data.result.ACTIONS.ACTIONS;
                    select += '<option value="">- Séléctionner une commande -</option>';
                    $.each(jsonresult, function (cat, catVal) {
                        select += '<optgroup label="' + catVal[1] + '" id="' + cat + '"></optgroup>';
                        $.each(jsonresult2, function (action, actionVal) {
                            if (cat === actionVal.category)
                                select += '<option title="' + actionVal.description + '" value="' + action + '">' + action + '</option>';

                        });
                    });
                    select += '</select>';
                    $("#idactions").html(select);


                }
            }
        });
    });
    
          $('#idactions').on('change', function () {
            $("#idparameters").empty()
            $("#idoptions").empty()  

           
        var select = '';

        var cmdvalue = $('#idactions option:selected').value();

            $.each(jsonresult2[cmdvalue].params, function (key, val) {
                var title = '';
                if (val.required == true) {
                    if (val.type == "spinner") {
                        title += '<b>' + key + '</b>';
                        title += '<input id="parameters" type="number" class="form-control" name="' + key + '" min="' + val.check.min + '" max="' + val.check.max + '">';
                        $("#idparameters").append(title);
                    }
                    ;

                    if (val.type == "boolean") {
                        title += '<b>' + key + '</b>';
                        title += '<form>';
                        title += '<input id="parameters" type="radio" name="' + key + '" value="1">  Oui';
                        title += ' <input id="parameters" type="radio" name="' + key + '" value="0">  Non';
                        title += '</form>';
                        $("#idparameters").append(title);
                    }
                    ;

                    if (val.type == "text" || val.type == "textarea" || val.type == "basic") {
                        if (key === "message"){
                         title += '<FONT color="red"><b>Le champ message est à remplir dans les scénarios !<br> Ne pas oublier de séléctionner le type de commande action/message.</b></FONT>'; 
                         $("#idparameters").append(title);
                        } else{
                        title += '<b>' + key + '</b>';
                        title += '<input id="parameters" type="input" class="form-control" name="' + key + '" placeholder="' + val.description + '">';
                        $("#idparameters").append(title);
                    };
                    };

                    if (val.type == "select") {
                        if (isset(val.magic) && is_array(val.magic)) {
                            title += '<b>' + key + '</b>';
                            title += '<select id="parameters" class="form-control" name="' + key + '">';
                            title += '<option value="">- Selectionner une option -</option>';
                            $.each(val.magic, function (key, value) {
                                title += '<option value="' + key + '">' + value.title + '</option>';
                            });
                            $("#idparameters").append(title);
                        }
                    }
                    ;

                }
                ;



                if (val.required == false) {

                    if (val.type == "text" || val.type == "textarea" || val.type == "basic") {
                        title += '<b>' + key + '</b>';
                        title += '<input id="options" type="input" class="form-control" name="' + key + '" value="' + val.defaultValue + '" placeholder="' + val.description + '">'
                        $("#idoptions").append(title);
                    }
                    ;

                    if (val.type == "boolean") {
                        title += '<b>' + key + '</b>';
                        title += '<form>';
                     if (val.defaultValue === 1) { 
                        title += '<input id="options" type="radio" name="' + key + '" value="1" checked>  Oui';
                        title += ' <input id="options" type="radio" name="' + key + '" value="0">  Non';
                    } else {
                        title += '<input id="options" type="radio" name="' + key + '" value="1"  >  Oui';
                        title += ' <input id="options" type="radio" name="' + key + '" value="0" checked>  Non';    
                    };
                        title += '</form>';
                        $("#idoptions").append(title);
                    }
                    ;
                    if (val.type == "select") {
                        if (isset(val.magic) && is_array(val.magic)) {
                            title += '<b>' + key + '</b>';
                            title += '<select id="options" class="form-control" name="' + key + '">';
                            title += '<option value="'+ val.defaultValue +'">'+ val.defaultValue +'</option>';
                            title += '<option value="">- Selectionner une option -</option>';
                            $.each(val.magic, function (key, value) {
                                title += '<option value="' + key + '">' + value.title + '</option>';
                            });
                            $("#idoptions").append(title);
                        }
                    }
                    ;
                    if (val.type == "spinner") {
                        title += '<b>' + key + '</b>';
                        title += '<input id="idoptions" type="number" class="form-control" name="' + key + '" min="' + val.check.min + '" max="' + val.check.max + '" step="' + val.check.step +  '" value="' + val.defaultValue + '">';
                        $("#idoptions").append(title);
                    };
                }
                ;

            });
            if (cmdvalue === "tts") {

                var title2 = '';
                $.ajax({
                    type: "POST",
                    url: "plugins/JPI/core/ajax/JPI.ajax.php",
                    data: {
                        action: "getjpiVoice",
                        ip: $('.eqLogicAttr[data-l1key=configuration][data-l2key=jpiIp]').value(),
                        port: $('.eqLogicAttr[data-l1key=configuration][data-l2key=jpiPort]').value()
                    },
                    dataType: 'json',
                    error: function (request, status, error) {
                        console.log("Erreur lors de la demande");
                    },
                    error: function (request, status, error) {
                        handleAjaxError(request, status, error);
                    },
                    async: false,
                    success: function (data2) {
                        if (data2.state !== 'ok') {
                            $('#div_alert').showAlert({
                                message: data.result,
                                level: 'danger'
                            });
                            return;
                        }
                        title2 += '<b>voice</b>';
                        title2 += '<select id="options" class="form-control" name="voice">';
                        title2 += '<option value="">- Séléctionner une voix -</option>';
                        $.each(data2.result, function (key, value) {
                            title2 += '<option value="' + key + '">' + value + '</option>';
                        });
                        title2 += '</select>';
                        $("#idoptions").append(title2);

                    }
                });
            }
            ;


            if (cmdvalue === "launchApp" || cmdvalue === "killApp") {

                var title2 = '';
                $.ajax({
                    type: "POST",
                    url: "plugins/JPI/core/ajax/JPI.ajax.php",
                    data: {
                        action: "getjpiApp",
                        ip: $('.eqLogicAttr[data-l1key=configuration][data-l2key=jpiIp]').value(),
                        port: $('.eqLogicAttr[data-l1key=configuration][data-l2key=jpiPort]').value()
                    },
                    dataType: 'json',
                    error: function (request, status, error) {
                        console.log("Erreur lors de la demande");
                    },
                    error: function (request, status, error) {
                        handleAjaxError(request, status, error);
                    },
                    async: false,
                    success: function (data2) {
                        if (data2.state !== 'ok') {
                            $('#div_alert').showAlert({
                                message: data.result,
                                level: 'danger'
                            });
                            return;
                        }

                        title2 += '<b>packageName</b>';
                        title2 += '<select id="parameters" class="form-control" name="packageName">';
                        title2 += '<option value="">- Séléctionner une application -</option>';
                        $.each(data2.result, function (key, value) {
                            title2 += '<option value="' + key + '">' + value + '</option>';
                        });
                        title2 += '</select>';
                        $("#idparameters").append(title2);

                    }
                });
            }
            ;








        });

    


</script>

<?php
include_file('desktop', 'JPI', 'js', 'JPI');
?>
<?php
include_file('core', 'plugin.template', 'js');
?>    