    <?php
    if (!isConnect('admin')) {
        throw new \Exception('{{401 - Accès non autorisé}}');
    }
    $plugin   = plugin::byId('JPI');
    sendVarToJS('eqType', $plugin->getId());
    $eqLogics = eqLogic::byType($plugin->getId());
    ?>

    <div class="row row-overflow">
        <div class="col-lg-2 col-md-3 col-sm-4">
            <div class="bs-sidebar">
                <ul id="ul_eqLogic" class="nav nav-list bs-sidenav">
                    <a class="btn btn-default eqLogicAction" style="width : 100%;margin-top : 5px;margin-bottom: 5px;" data-action="add"><i class="fa fa-plus-circle"></i> {{Ajouter un équipement}}</a>
                    <li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
                    <?php
                    foreach ($eqLogics as $eqLogic) {
                        $opacity = ($eqLogic->getIsEnable()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
                        echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $eqLogic->getId() . '"  style="' . $opacity . '"><a>' . $eqLogic->getHumanName(true) . '</a></li>';
                    }
                    ?>
                </ul>
            </div>
        </div>

        <div class="col-lg-10 col-md-9 col-sm-8 eqLogicThumbnailDisplay" style="border-left: solid 1px #EEE; padding-left: 25px;">
            <legend><i class="fa fa-cog"></i> {{Gestion}}</legend>
            <div class="eqLogicThumbnailContainer">
                <div class="cursor eqLogicAction" data-action="add" style="text-align: center; background-color : #ffffff; height : 120px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
                    <i class="fa fa-plus-circle" style="font-size : 6em;color:#94ca02;"></i>
                    <br>
                    <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#94ca02">Ajouter</span>
                </div>
                <div class="cursor eqLogicAction" data-action="gotoPluginConf" style="text-align: center; background-color : #ffffff; height : 120px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;">
                    <i class="fa fa-wrench" style="font-size : 6em;color:#767676;"></i>
                    <br>
                    <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#767676">{{Configuration}}</span>
                </div>
            </div>
            <legend><i class="fa fa-table"></i> {{Mes périphériques}}</legend>
            <div class="eqLogicThumbnailContainer">
                <?php
                foreach ($eqLogics as $eqLogic) {
                    $opacity = ($eqLogic->getIsEnable()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
                    echo '<div class="eqLogicDisplayCard cursor" data-eqLogic_id="' . $eqLogic->getId() . '" style="text-align: center; background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;' . $opacity . '" >';
                    echo '<img src="' . $plugin->getPathImgIcon() . '" height="105" width="95" />';
                    echo "<br>";
                    echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;">' . $eqLogic->getHumanName(true, true) . '</span>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>

        <div class="col-lg-10 col-md-9 col-sm-8 eqLogic" style="border-left: solid 1px #EEE; padding-left: 25px;display: none;">
            <a class="btn btn-success eqLogicAction pull-right" data-action="save"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
            <a class="btn btn-danger eqLogicAction pull-right" data-action="remove"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>
            <a class="btn btn-default eqLogicAction pull-right" data-action="configure"><i class="fa fa-cogs"></i> {{Configuration avancée}}</a>
            <a class="btn btn-warning  pull-right" id="bt_autoDetectDevice"><i class="fa fa-search"></i>  {{Rafraichir équipement JPI}}</a>
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation"><a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay"><i class="fa fa-arrow-circle-left"></i></a></li>
                <li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-tachometer"></i> {{Equipement}}</a></li>
                <li role="presentation"><a href="#commandtabCmdWidget" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-list-alt"></i> {{Commandes système}}</a></li>
                <li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-list-alt"></i> {{Commandes}}</a></li>
            </ul>
            <div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
                <div role="tabpanel" class="tab-pane active" id="eqlogictab">
                    <br/>
                    <div class="row">
                        <div class="col-sm-7">
                            <form class="form-horizontal">
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">{{Nom équipement JPI}}</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
                                            <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom équipement JPI}}" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label"></label>
                                        <div class="col-sm-6">
                                            <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
                                            <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" >{{Objet parent}}</label>
                                        <div class="col-sm-6">
                                            <select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
                                                <option value="">{{Aucun}}</option>
                                                <?php
                                                foreach (jeeObject::all() as $jeeObject) {
                                                    echo '<option value="' . $jeeObject->getId() . '">' . $jeeObject->getName() . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">{{Connexion}}</label>
                                        <div class="col-sm-4">
                                            <select class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="jpiProto">
                                                <option value="http">http</option>
                                                <option value="https">https</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">{{Adresse IP}}</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="jpiIp" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">{{Port}}</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="jpiPort" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">{{Preset média 1}}</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="jpiPreset1" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">{{Preset média 2}}</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="jpiPreset2" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">{{Preset média 3}}</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="jpiPreset3" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">{{Preset média 4}}</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="jpiPreset4" />
                                        </div>
                                    </div>

                                </div>
                            </fieldset>
                        </form>

                        <?php
             if (network::getUserLocation() == 'internal') {
                 echo '<div class="col-sm-5">';
                 echo '  <form class="form-horizontal">';
                 echo '     <fieldset>';
                 echo '       <div class="cursor" id="bt_Device">';
                 echo '          <center>';
                 echo '            <i class="fa fa-mobile" style="font-size : 5em;color:#767676;"></i>';
                 echo '       </center>';
                 echo '      <span style="font-size : 1.1em;position:relative;word-break: break-all;white-space: pre-wrap;word-wrap: break-word"><center>{{Lien vers équipement JPI}}</center></span>';
                 echo '  </div>';
                 echo ' </form>';
                 echo ' </div> ';

                 echo '    <div class="col-sm-5">';
                  echo '   <form class="form-horizontal">';
                 echo '    <fieldset>';
                  echo '   <div class="cursor" id="bt_Backup">';
                  echo '   <center>';
                  echo '   <i class="fa fa-archive" style="font-size : 5em;color:#767676;"></i>';
                  echo '   </center>';
                  echo '   <span style="font-size : 1.1em;position:relative;word-break: break-all;white-space: pre-wrap;word-wrap: break-word"><center>{{Sauvegardes}}</center></span>';
                  echo '   </div>';
                  echo '   </form>';
                  echo '   </div>';
              }
             ?>
                    </div>

                </div>

                <div role="tabpanel" class="tab-pane" id="commandtabCmdWidget">
                    <table id="table_cmdWidget" class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th style="width: 50px;"></th>
                                <th style="width: 150px;">{{Nom}}</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>

                <div role="tabpanel" class="tab-pane" id="commandtab">
                    <a class="btn btn-success pull-right" id="bt_Assistant" ><i class="fa fa-plus-circle"></i> {{Assistant de commande JPI}}</a>
                    <a class="btn btn-success cmdAction pull-right" data-action="add"><i class="fa fa-plus-circle"></i> {{Ajouter une commande JPI}}</a><br/><br/>
                    <table id="table_cmd" class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th style="width: 65px;"></th>
                                <th style="width: 10px;"></th>
                                <th style="width: 100px;">{{Nom}}</th>
                                <th style="width: 100px;">{{Actions}}</th>
                                <th style="width: 280px;">{{Paramètres}}</th>
                                <th style="width: 280px;">{{Options}}</th>
								<th style="width: 10px;">{{Retenter}}</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
    </div>

    <?php
    include_file('desktop', 'JPI', 'js', 'JPI');

    include_file('core', 'plugin.template', 'js');
