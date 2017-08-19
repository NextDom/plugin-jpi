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

$('#bt_Device').on('click', function () {
    $('#md_modal').dialog({
        title: "Configuration de votre équipement JPI",
        MaxWidth: 800,
        MaxHeight: 800
    });
    $('#md_modal').load('index.php?v=d&plugin=JPI&modal=modal.JPI&id=' + $('.eqLogicAttr[data-l1key=id]').value()).dialog('open');
});

 $("body").undelegate('.bt_addInAction', 'click').delegate('.bt_addInAction', 'click', function () {
    $('#md_modal').dialog({
        title: "Assistant de modification de commande JPI",
        MaxWidth: 800,
        MaxHeight: 800
    });
    $('#md_modal').load('index.php?v=d&plugin=JPI&modal=modal.JPIMod&cmd_id=' + $(this).closest('.cmd').attr('data-cmd_id')).dialog('open');
});

$('#bt_Assistant').on('click', function () {
    $('#md_modal').dialog({
        title: "Assistant d\'ajout de commande JPI",
        MaxWidth: 800,
        MaxHeight: 800
    });
    $('#md_modal').load('index.php?v=d&plugin=JPI&modal=modal.JPIAdd&id=' + $('.eqLogicAttr[data-l1key=id]').value()).dialog('open');
});

$('#bt_autoDetectDevice').on('click', function () {
    bootbox.confirm('{{<br><b>Etes-vous sûr de vouloir rafraîchir votre équipement JPI ?</b><br> Cela va supprimer les fichiers de configuration existants et la récupération des nouvelles informations peut prendre un certain temps. Veuillez patienter juqu\'au message de fin de traitement.}}', function (result) {
        if (result) {
            $.ajax({
                type: "POST",
                url: "plugins/JPI/core/ajax/JPI.ajax.php",
                data: {
                    action: "autoDetectModule",
                    ip: $('.eqLogicAttr[data-l1key=configuration][data-l2key=jpiIp]').value(),
                    port: $('.eqLogicAttr[data-l1key=configuration][data-l2key=jpiPort]').value()
                },
                dataType: 'json',
                global: false,
                error: function (request, status, error) {
                    handleAjaxError(request, status, error);
                },
                success: function (data) {
                    if (data.state !== 'ok') {
                        $('#div_alert').showAlert({message: data.result, level: 'danger'});
                        return;
                    }
                    $('#div_alert').showAlert({message: '{{Opération réalisée avec succès}}', level: 'success'});
                    $('.li_eqLogic[data-eqLogic_id=' + $('.eqLogicAttr[data-l1key=id]').value() + ']').click();
                }
            });
        }
    });
});

$("#table_cmd").sortable({
    axis: "y",
    cursor: "move",
    items: ".cmd",
    placeholder: "ui-state-highlight",
    tolerance: "intersect",
    forcePlaceholderSize: true
});

function addCmdToTable(_cmd) {
    if (!isset(_cmd)) {
        var _cmd = {configuration: {}};
    }
    if (!isset(_cmd.configuration)) {
        _cmd.configuration = {};
    }

    var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
    
        if (_cmd.configuration.type == 'cmdwiget') {
        tr += '<td>';
        if (is_numeric(_cmd.id)) {
            tr += '<a class="btn btn-default btn-xs cmdAction expertModeVisible" data-action="configure"><i class="fa fa-cogs"></i></a> ';
            tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fa fa-rss"></i> {{Tester}}</a>';           
            tr += '<i class="fa fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i></td>';
        }

        tr += '</td>';

        tr += '<td>';
        tr += '<input class="cmdAttr form-control input-sm" data-l1key="name" placeholder="{{Nom}}">';
        tr += '<input class="cmdAttr" data-l1key="id" style="display:none;" />';
        tr += '</td>';


    $('#table_cmdWidget tbody').append(tr);
    $('#table_cmdWidget tbody tr:last').setValues(_cmd, '.cmdAttr');

    }


    
    
    if (_cmd.configuration.type !== 'cmdwiget') {
        tr += '<td>';
        if (is_numeric(_cmd.id)) {
            tr += '<a class="btn btn-default btn-xs cmdAction expertModeVisible tooltips" title="Configuration de la commade" data-action="configure"><i class="fa fa-cogs"></i></a> ';
            tr += '<a class="btn btn-default btn-xs cmdAction tooltips" title="Test de la commande" data-action="test"><i class="fa fa-rss"></i></a>';
            tr += '<a class="btn btn-default btn-xs bt_addInAction tooltips" title="Modification de la commande"><i class="fa fa-wrench "></i></a> ';
            tr += '<i class="fa fa-minus-circle pull-right cmdAction cursor" title="Supprimer de la commande" data-action="remove"></i></td>';
        }

        tr += '</td>';

        tr += '<td class="expertModeVisible">';
        tr += '<span class="type" type="' + init(_cmd.type) + '">' + jeedom.cmd.availableType() + '</span>';
        tr += '<span class="subType" subType="' + init(_cmd.subType) + '"></span>';
        tr += '</td>';

        tr += '<td>';
        tr += '<input class="cmdAttr form-control input-sm" data-l1key="name" placeholder="{{Nom}}">';
        tr += '<input class="cmdAttr" data-l1key="id" style="display:none;" />';
        tr += '</td>';

        tr += '<td>';
        tr += '<input class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="jpiAction" placeholder="{{Action}}">';
        tr += '</td>';

        tr += '<td>';
        tr += '<input class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="jpiParametres" placeholder="{{Paramètres}}">';
        tr += '</td>';

        tr += '<td>';
        tr += '<input class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="jpiOptions" placeholder="{{Options}}">';
        tr += '</td>';
    $('#table_cmd tbody').append(tr);
    $('#table_cmd tbody tr:last').setValues(_cmd, '.cmdAttr');
    if (isset(_cmd.type)) {
        $('#table_cmd tbody tr:last .cmdAttr[data-l1key=type]').value(init(_cmd.type));
    }
    jeedom.cmd.changeType($('#table_cmd tbody tr:last'), init(_cmd.subType));        
    }

    tr += '</tr>';


}