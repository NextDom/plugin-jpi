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

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';
include_file('core', 'authentification', 'php');
if (!isConnect()) {
include_file('desktop', '404', 'php');
die();
}
?>

<form class="form-horizontal">
<fieldset>
        <div class="row">
        <label class="col-lg-3 text-center control-label">{{Fréquence de recuperation des données}}</label>
        <div class="col-lg-3 text-center">
            <select id="frequence" class="configKey form-control"  data-l1key="frequence" >
                <option value="1min">{{Toutes les 1 minutes (Conseillé uniquement pour le test)}}</option>
                <option value="5min">{{Toutes les 5 minutes}}</option>
                <option value="15min">{{Toutes les 15 minutes}}</option>
                <option value="30min">{{Toutes les 30 minutes}}</option>
                <option value="60min">{{Toutes les heures}}</option>
            </select>
        </div>
      <label class="col-lg-3 text-center control-label">{{Lien PAW}}</label>
     <div class="col-lg-3 text-center"><img src="plugins/JPI/resources/PAW.png" height="200" width="200" /></div>
    </div>
<div class="row">
    <br>
</div>
    <div class="row">
        <label class="col-lg-3 text-center control-label">{{Point de restauration à garder}}</label>
          <div class="col-lg-3 text-center ">
            <select id="frequence" class="configKey form-control"  data-l1key="retention" >
                <option value="none" >{{Désactivé}}</option>
                <option value="2">{{2}}</option>
                <option value="3">{{3}}</option>
                <option value="4">{{4}}</option>
                <option value="5">{{5}}</option>
                <option value="6">{{6}}</option>
                <option value="7">{{7}}</option>
                <option value="8">{{8}}</option>
                <option value="9">{{9}}</option>
                <option value="10">{{10}}</option>
                <option value="11">{{11}}</option>
                <option value="12">{{12}}</option>
                <option value="13">{{13}}</option>
                <option value="14">{{14}}</option>
            </select>
        </div>
        <label class="col-lg-3 text-center control-label">{{Lien JPI}}</label>
    <div class="col-lg-3 text-center"><img src="plugins/JPI/resources/APK.png" height="200" width="200" /></div>
    </div>
    </fieldset>
</form>
