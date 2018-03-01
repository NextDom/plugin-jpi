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

try {
    require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
    include_file('core', 'authentification', 'php');

    if (!isConnect('admin')) {
        throw new \Exception(__('401 - Accès non autorisé', __FILE__));
    }

    if (init('action') == 'getjpiVoice') {
        $ip   = (init('ip'));
        $port = (init('port'));
        ajax::success(JPI::getjpiVoice($ip, $port));
    }

    if (init('action') == 'getjpiApp') {
        $ip   = (init('ip'));
        $port = (init('port'));
        ajax::success(JPI::getjpiApp($ip, $port));
    }

    if (init('action') == 'getjpiActions') {
        $ip   = (init('ip'));
        $port = (init('port'));
        ajax::success(JPI::getjpiActions($ip, $port));
    }

    if (init('action') == 'autoDetectModule') {
        $ip   = (init('ip'));
        $port = (init('port'));
        ajax::success(JPI::autoDetectModule($ip, $port));
    }

    if (init('action') == 'AddCommand') {
        $id         = (init('id'));
        $name       = (init('name'));
        $command    = (init('command'));
        $parameters = (init('parameters'));
        $options    = (init('options'));
        ajax::success(JPI::AddCommand($id, $name, $command, $parameters, $options));
    }

    if (init('action') == 'updateCommand') {
        $cmdid      = (init('cmdid'));
        $id         = (init('id'));
        $name       = (init('name'));
        $command    = (init('command'));
        $parameters = (init('parameters'));
        $options    = (init('options'));
        ajax::success(JPI::updateCommand($id, $cmdid, $name, $command, $parameters, $options));
    }

    throw new \Exception(__('Aucune méthode correspondante à : ', __FILE__) . init('action'));
    /*     * *********Catch exeption*************** */
} catch (\Exception $e) {
    ajax::error(displayExeption($e), $e->getCode());
}
 
