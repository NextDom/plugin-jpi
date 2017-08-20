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

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

class JPI extends eqLogic {

    public static function AddCommand($id, $name, $command, $parameters, $options) {
        log::add('JPI', 'DEBUG', 'Création de la commande : ' . $name . ' - Commande :' . $command . ' - Paramètres : ' . $parameters . ' - Options' . $options);
        $JPI = eqLogic::byId($id);
        $JPICmd = $JPI->getCmd(null, $name);
        if (!is_object($JPICmd)) {
            $JPICmd = new JPICmd();
            $JPICmd->setName($name);
            $JPICmd->setEqLogic_id($id);
            $JPICmd->setType('action');
            $JPICmd->setSubType('other');
        }
        $JPICmd->setConfiguration('jpiAction', $command);
        $JPICmd->setConfiguration('jpiParametres', $parameters);
        $JPICmd->setConfiguration('jpiOptions', $options);
        $JPICmd->save();
    }

    public static function updateCommand($id, $cmdid, $name, $command, $parameters, $options) {
        log::add('JPI', 'DEBUG', 'Mise à jour de la commande : ' . $name . ' ' . $command . ' ' . $parameters . ' ' . $options);
        $JPI = eqLogic::byId($id);

        $JPICmd = $JPI->getCmd(null, $name);
        $JPICmd = new JPICmd();
        $JPICmd->setName($name);
        $JPICmd->setid($cmdid);
        $JPICmd->setEqLogic_id($id);
        $JPICmd->setType('action');
        $JPICmd->setSubType('other');
        $JPICmd->setConfiguration('jpiAction', $command);
        $JPICmd->setConfiguration('jpiParametres', $parameters);
        $JPICmd->setConfiguration('jpiOptions', $options);
        $JPICmd->save();
    }

    public static function getjpiVoice($ip, $port) {
        $JPICmd_json = dirname(__FILE__) . '/../config/' . $ip . '_voice.json';
        if (!file_exists($JPICmd_json)) {
            $url = 'http://' . $ip . ':' . $port . '/?action=getVoices&json=1';
            log::add('JPI', 'INFO', 'Refresh des voix envoyée à l\'équipement JPI : ' . $url);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, "$url");
+           curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
            $response = curl_exec($ch);
            curl_close($ch);
            file_put_contents($JPICmd_json, $response);
            log::add('JPI', 'DEBUG', 'Valeurs récupérées: ' . $response);
        } else {
            return json_decode(file_get_contents($JPICmd_json), true);
        }
    }

    public static function getjpiApp($ip, $port) {
        $app_json = dirname(__FILE__) . '/../config/' . $ip . '_app.json';
        if (!file_exists($app_json)) {
            $url = 'http://' . $ip . ':' . $port . '/?action=getPackagesNames&json=1';
            log::add('JPI', 'INFO', 'Refresh des applications envoyée à l\'équipement JPI : ' . $url);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, "$url");
+           curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
            $response = curl_exec($ch);
            curl_close($ch);
            file_put_contents($app_json, $response);
            log::add('JPI', 'DEBUG', 'Valeurs récupérées: ' . $response);
        } else {
            return json_decode(file_get_contents($app_json), true);
        }
    }

    public static function getjpiActions($ip, $port) {
        $cmd_json = dirname(__FILE__) . '/../config/' . $ip . '_cmd.json';
        if (!file_exists($cmd_json)) {
            $url = 'http://' . $ip . ':' . $port . '/?action=__NET_CMD__&__FROM_MAIN_APP__=true&net=action&action_ex=_GET_ACTIONS_JSON_';
            log::add('JPI', 'INFO', 'Refresh des commandes envoyée l\'équipement JPI: ' . $url);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, "$url");
+           curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
            $response = curl_exec($ch);
            curl_close($ch);
            file_put_contents($cmd_json, $response);
            log::add('JPI', 'DEBUG', 'Valeurs récupérées: ' . $response);
        } else {
            return json_decode(file_get_contents($cmd_json), true);
        }
    }

    public static function autoDetectModule($ip, $port) {
        $files = glob(dirname(__FILE__) . '/../config/' . $ip . '*.json');
        foreach ($files as $file) {
            unlink($file);
        }
        log::add('JPI', 'INFO', 'Refresh de la configuration en cours');
        self::getjpiActions($ip, $port);
        self::getjpiApp($ip, $port);
        self::getjpiVoice($ip, $port);
    }

    public function cron($_eqlogic_id = null) {
        $frequence = config::byKey('frequence', 'JPI');
        if ($frequence == '1min') {
            self::executeinfo();
        }
    }

    public function cron5($_eqlogic_id = null) {
        $frequence = config::byKey('frequence', 'JPI');
        if ($frequence == '5min') {
            self::executeinfo();
        }
    }

    public function cron15($_eqlogic_id = null) {
        $frequence = config::byKey('frequence', 'JPI');
        if ($frequence == '15min') {
            self::executeinfo();
        }
    }

    public function cron30($_eqlogic_id = null) {
        $frequence = config::byKey('frequence', 'JPI');
        if ($frequence == '30min') {
            self::executeinfo();
        }
    }

    public function cronHourly($_eqlogic_id = null) {
        $frequence = config::byKey('frequence', 'JPI');
        if ($frequence == '60min') {
            self::executeinfo();
        }
    }

    public static function executeinfo() {

//        if ($_eqlogic_id !== null) {
//            $eqLogics = array(
//                eqLogic::byId($_eqlogic_id)
//            );
//        } else {
            $eqLogics = eqLogic::byType('JPI');
        //}
        foreach ($eqLogics as $JPI) {
            if ($JPI->getIsEnable() == 1) {
                foreach ($JPI->getCmd('info') as $cmd) {
                    if ($cmd->getConfiguration('jpiAction') !== '') {
                        $url = 'http://' . $JPI->getConfiguration('jpiIp') . ':' . $JPI->getConfiguration('jpiPort') . '/?action=' . $cmd->getConfiguration('jpiAction') . '&__JPIPLUG=1';
                        log::add('JPI', 'DEBUG', 'Récupération des informations : ' . $url);
                        $value = file_get_contents($url);
                        log::add('JPI', 'DEBUG', 'Résultat : ' . $value);
                        $cmd->event($value);
                    }
                }
                $JPI->refreshWidget();
            }
        }
    }

    public static function executerequest($action) {
        log::add('JPI', 'info', 'Commande envoyée au device JPI : ' . $action);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $action);
+       curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
+       //curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        $response = curl_exec($ch);
        curl_close($ch);

        if (preg_match("/\bok\b/i", $response) || preg_match("/\bstorage\b/i", $response)) {
            log::add('JPI', 'INFO', 'Réponse JPI pour la requête demandée : ' . $response);
            return $response;
        } else {
            log::add('JPI', 'INFO', 'Réponse JPI pour la requête demandée : KO');
            return $response;
        }
    }

    public function postUpdate() {

        $JPICmd = $this->getCmd(null, 'infovolume');
        if (!is_object($JPICmd)) {
            $JPICmd = new JPICmd();
            $JPICmd->setName(__('Info volume media', __FILE__));
            $JPICmd->setEqLogic_id($this->getId());
            $JPICmd->setLogicalId('infovolume');
            $JPICmd->setType('info');
            $JPICmd->setSubType('numeric');
        }
        $JPICmd->setConfiguration('jpiAction', 'getVolume');
        $JPICmd->setConfiguration('type', 'cmdwiget');
        $JPICmd->save();

        $JPICmd = $this->getCmd(null, 'setvolumemedia');
        if (!is_object($JPICmd)) {
            $JPICmd = new JPICmd();
            $JPICmd->setName(__('Configuration du volume media', __FILE__));
            $JPICmd->setEqLogic_id($this->getId());
            $JPICmd->setLogicalId('setvolumemedia');
            $JPICmd->setType('action');
            $JPICmd->setSubType('slider');
        }
        $JPICmd->setConfiguration('minValue', 0);
        $JPICmd->setConfiguration('maxValue', 100);
        $JPICmd->setConfiguration('type', 'cmdwiget');
        $JPICmd->save();

        $JPICmd = $this->getCmd(null, 'mute');
        if (!is_object($JPICmd)) {
            $JPICmd = new JPICmd();
            $JPICmd->setName(__('Mute', __FILE__));
            $JPICmd->setEqLogic_id($this->getId());
            $JPICmd->setLogicalId('mute');
            $JPICmd->setType('action');
            $JPICmd->setSubType('other');
        }
        $JPICmd->setConfiguration('jpiAction', 'muteAll');
        $JPICmd->setConfiguration('type', 'cmdwiget');
        $JPICmd->save();

        $JPICmd = $this->getCmd(null, 'infobatterie');
        if (!is_object($JPICmd)) {
            $JPICmd = new JPICmd();
            $JPICmd->setName(__('Niveau de la batterie', __FILE__));
            $JPICmd->setEqLogic_id($this->getId());
            $JPICmd->setLogicalId('infobatterie');
            $JPICmd->setType('info');
            $JPICmd->setSubType('numeric');
            $JPICmd->setUnite('%');
            $JPICmd->setDisplay('generic_type', 'infobatterie');
        }
        $JPICmd->setConfiguration('jpiAction', 'getBattLevel');
        $JPICmd->setConfiguration('type', 'cmdwiget');
        $JPICmd->save();

        $JPICmd = $this->getCmd(null, 'infosms');
        if (!is_object($JPICmd)) {
            $JPICmd = new JPICmd();
            $JPICmd->setName(__('Nombre de SMS envoyés', __FILE__));
            $JPICmd->setEqLogic_id($this->getId());
            $JPICmd->setLogicalId('infosms');
            $JPICmd->setType('info');
            $JPICmd->setSubType('numeric');
            $JPICmd->setDisplay('generic_type', 'infosms');
        }
        $JPICmd->setConfiguration('type', 'cmdwiget');
        $JPICmd->setConfiguration('jpiAction', 'getSmsCounter');
        $JPICmd->save();

        $JPICmd = $this->getCmd(null, 'infosentsms');
        if (!is_object($JPICmd)) {
            $JPICmd = new JPICmd();
            $JPICmd->setName(__('Statut SMS', __FILE__));
            $JPICmd->setEqLogic_id($this->getId());
            $JPICmd->setLogicalId('infosentsms');
            $JPICmd->setType('info');
            $JPICmd->setSubType('numeric');
            $JPICmd->setDisplay('generic_type', 'infosentsms');
        }
        $JPICmd->setConfiguration('type', 'cmdwiget');
        $JPICmd->save();

        $JPICmd = $this->getCmd(null, 'pause');
        if (!is_object($JPICmd)) {
            $JPICmd = new JPICmd();
            //$JPICmd->setIsVisible(1);
            $JPICmd->setName(__('Pause', __FILE__));
            $JPICmd->setEqLogic_id($this->getId());
            $JPICmd->setLogicalId('pause');
            $JPICmd->setType('action');
            $JPICmd->setSubType('other');
        }
        $JPICmd->setConfiguration('type', 'cmdwiget');
        $JPICmd->setConfiguration('jpiAction', 'pause');
        $JPICmd->save();

        $JPICmd = $this->getCmd(null, 'play');
        if (!is_object($JPICmd)) {
            $JPICmd = new JPICmd();
            $JPICmd->setName(__('Play', __FILE__));
            $JPICmd->setEqLogic_id($this->getId());
            $JPICmd->setLogicalId('play');
            $JPICmd->setType('action');
            $JPICmd->setSubType('other');
        }
        $JPICmd->setConfiguration('type', 'cmdwiget');
        $JPICmd->setConfiguration('jpiAction', 'play');
        $JPICmd->save();

        $JPICmd = $this->getCmd(null, 'infosignal');
        if (!is_object($JPICmd)) {
            $JPICmd = new JPICmd();
            $JPICmd->setName(__('Puissance du signal', __FILE__));
            $JPICmd->setEqLogic_id($this->getId());
            $JPICmd->setLogicalId('infosignal');
            $JPICmd->setType('info');
            $JPICmd->setSubType('numeric');
            $JPICmd->setUnite('%');
            $JPICmd->setDisplay('generic_type', 'infosignal');
        }
        $JPICmd->setConfiguration('type', 'cmdwiget');
        $JPICmd->setConfiguration('jpiAction', 'getWifiStrength');
        $JPICmd->save();

        $JPICmd = $this->getCmd(null, 'preset1');
        if (!is_object($JPICmd)) {
            $JPICmd = new JPICmd();
            $JPICmd->setName(__('Preset1 media', __FILE__));
            $JPICmd->setEqLogic_id($this->getId());
            $JPICmd->setLogicalId('preset1');
            $JPICmd->setType('action');
            $JPICmd->setSubType('other');
        }
        $JPICmd->setConfiguration('type', 'cmdwiget');
        $JPICmd->save();

        $JPICmd = $this->getCmd(null, 'preset2');
        if (!is_object($JPICmd)) {
            $JPICmd = new JPICmd();
            $JPICmd->setName(__('Preset2 media', __FILE__));
            $JPICmd->setEqLogic_id($this->getId());
            $JPICmd->setLogicalId('preset2');
            $JPICmd->setType('action');
            $JPICmd->setSubType('other');
        }
        $JPICmd->setConfiguration('type', 'cmdwiget');
        $JPICmd->save();

        $JPICmd = $this->getCmd(null, 'preset3');
        if (!is_object($JPICmd)) {
            $JPICmd = new JPICmd();
            $JPICmd->setName(__('Preset3 media', __FILE__));
            $JPICmd->setEqLogic_id($this->getId());
            $JPICmd->setLogicalId('preset3');
            $JPICmd->setType('action');
            $JPICmd->setSubType('other');
        }
        $JPICmd->setConfiguration('type', 'cmdwiget');
        $JPICmd->save();

        $JPICmd = $this->getCmd(null, 'preset4');
        if (!is_object($JPICmd)) {
            $JPICmd = new JPICmd();
            $JPICmd->setName(__('Preset4 media', __FILE__));
            $JPICmd->setEqLogic_id($this->getId());
            $JPICmd->setLogicalId('preset4');
            $JPICmd->setType('action');
            $JPICmd->setSubType('other');
        }
        $JPICmd->setConfiguration('type', 'cmdwiget');
        $JPICmd->save();

        $JPICmd = $this->getCmd(null, 'next');
        if (!is_object($JPICmd)) {
            $JPICmd = new JPICmd();
            $JPICmd->setName(__('Next', __FILE__));
            $JPICmd->setEqLogic_id($this->getId());
            $JPICmd->setLogicalId('next');
            $JPICmd->setType('action');
            $JPICmd->setSubType('other');
        }
        $JPICmd->setConfiguration('type', 'cmdwiget');
        $JPICmd->setConfiguration('jpiAction', 'next');
        $JPICmd->save();

        $JPICmd = $this->getCmd(null, 'refresh');
        if (!is_object($JPICmd)) {
            $JPICmd = new JPICmd();
            $JPICmd->setName(__('Rafraichir', __FILE__));
            $JPICmd->setEqLogic_id($this->getId());
            $JPICmd->setLogicalId('refresh');
            $JPICmd->setType('action');
            $JPICmd->setSubType('other');
        }
        $JPICmd->setConfiguration('type', 'cmdwiget');
        $JPICmd->save();

        $JPICmd = $this->getCmd(null, 'stop');
        if (!is_object($JPICmd)) {
            $JPICmd = new JPICmd();
            $JPICmd->setName(__('Stop', __FILE__));
            $JPICmd->setEqLogic_id($this->getId());
            $JPICmd->setLogicalId('stop');
            $JPICmd->setType('action');
            $JPICmd->setSubType('other');
        }
        $JPICmd->setConfiguration('type', 'cmdwiget');
        $JPICmd->setConfiguration('jpiAction', 'stop');
        $JPICmd->save();

        $JPICmd = $this->getCmd(null, 'unmute');
        if (!is_object($JPICmd)) {
            $JPICmd = new JPICmd();
            $JPICmd->setName(__('Unmute', __FILE__));
            $JPICmd->setEqLogic_id($this->getId());
            $JPICmd->setLogicalId('unmute');
            $JPICmd->setType('action');
            $JPICmd->setSubType('other');
        }
        $JPICmd->setConfiguration('type', 'cmdwiget');
        $JPICmd->setConfiguration('jpiAction', 'unmuteAll');
        $JPICmd->save();

        $JPICmd = $this->getCmd(null, 'infoversion');
        if (!is_object($JPICmd)) {
            $JPICmd = new JPICmd();
            $JPICmd->setName(__('Version du moteur', __FILE__));
            $JPICmd->setEqLogic_id($this->getId());
            $JPICmd->setLogicalId('infoversion');
            $JPICmd->setType('info');
            $JPICmd->setSubType('string');
            $JPICmd->setDisplay('generic_type', 'infoversion');
        }
        $JPICmd->setConfiguration('type', 'cmdwiget');
        $JPICmd->setConfiguration('jpiAction', 'getVersion');
        $JPICmd->save();

        $JPICmd = $this->getCmd(null, 'voice');
        if (!is_object($JPICmd)) {
            $JPICmd = new JPICmd();
            $JPICmd->setName(__('Reconnaissance vocale', __FILE__));
            $JPICmd->setEqLogic_id($this->getId());
            $JPICmd->setLogicalId('voice');
            $JPICmd->setType('action');
            $JPICmd->setSubType('other');
        }
        $JPICmd->setConfiguration('type', 'cmdwiget');
        $JPICmd->setConfiguration('jpiAction', 'voiceCmd');
        $JPICmd->save();
    }

    public function preRemove() {
        
    }

    public function postRemove() {
        $ip = $this->getConfiguration('jpiIp');
        $files = glob(dirname(__FILE__) . '/../config/' . $ip . '*.json');
        log::add('JPI', 'INFO', 'Supression des fichiers de configuration pour l\'équipement JPI ' . $ip);
        foreach ($files as $file) {
            unlink($file);
        }
    }

    public function toHtml($_version = 'dashboard') {
        $replace = $this->preToHtml($_version);
        if (!is_array($replace)) {
            return $replace;
        }
        $JPICmd = jeedom::versionAlias($_version);
        foreach ($this->getCmd('info') as $cmd) {
            $replace['#' . $cmd->getLogicalId() . '#'] = $cmd->execCmd();
            $replace['#' . $cmd->getLogicalId() . '_id#'] = $cmd->getId();
            if ($cmd->getIsHistorized() == 1) {
                $replace['#' . $cmd->getLogicalId() . '_history#'] = 'history cursor';
            }
        }

        foreach ($this->getCmd('action') as $cmd) {
            $replace['#cmd_' . $cmd->getLogicalId() . '_id#'] = $cmd->getId();
        }
        return $this->postToHtml($_version, template_replace($replace, getTemplate('core', $JPICmd, 'JPI', 'JPI')));
    }

}

class JPICmd extends cmd {

    public function preSave() {
        if ($this->getConfiguration('jpiAction') == 'toast') {
            $this->setDisplay('message_placeholder', __('Toast', __FILE__));
            $this->setDisplay('title_disable', 1);
        }
        if ($this->getConfiguration('jpiAction') == 'notification') {
            $this->setDisplay('title_placeholder', __('Header', __FILE__));
            $this->setDisplay('message_placeholder', __('Message', __FILE__));
        }

        if ($this->getConfiguration('jpiAction') == 'tts') {
            $this->setDisplay('title_placeholder', __('Volume', __FILE__));
        }
        if ($this->getConfiguration('jpiAction') == 'sendSms') {
            $this->setDisplay('title_disable', 1);
        }

        if ($this->getConfiguration('jpiAction') == 'userLog') {
            $this->setDisplay('title_disable', 1);
        }
    }

    public function execute($_options = null) {
        $eqLogic = $this->getEqLogic();
        switch ($this->getLogicalId()) {

            case 'preset1':
                $action = 'http://' . $eqLogic->getConfiguration('jpiIp') . ':' . $eqLogic->getConfiguration('jpiPort') . '/?action=play&media=' . $eqLogic->getConfiguration('jpiPreset1') . '&__JPIPLUG=1';
                $eqLogic->executerequest($action);
                break;

            case 'preset2':
                $action = 'http://' . $eqLogic->getConfiguration('jpiIp') . ':' . $eqLogic->getConfiguration('jpiPort') . '/?action=play&media=' . $eqLogic->getConfiguration('jpiPreset2') . '&__JPIPLUG=1';
                $eqLogic->executerequest($action);
                break;

            case 'preset3':
                $action = 'http://' . $eqLogic->getConfiguration('jpiIp') . ':' . $eqLogic->getConfiguration('jpiPort') . '/?action=play&media=' . $eqLogic->getConfiguration('jpiPreset3') . '&__JPIPLUG=1';
                $eqLogic->executerequest($action);
                break;

            case 'preset4':
                $action = 'http://' . $eqLogic->getConfiguration('jpiIp') . ':' . $eqLogic->getConfiguration('jpiPort') . '/?action=play&media=' . $eqLogic->getConfiguration('jpiPreset4') . '&__JPIPLUG=1';
                $eqLogic->executerequest($action);
                break;

            case 'setvolumemedia':
                $vol = $_options['slider'];
                $action = 'http://' . $eqLogic->getConfiguration('jpiIp') . ':' . $eqLogic->getConfiguration('jpiPort') . '/?action=setVolume&volume=' . $vol . '&__JPIPLUG=1';
                $eqLogic->executerequest($action);
                break;

            case 'refresh':
                $eqLogic->executeinfo($eqLogic->getId());
                break;
        }

        if ($this->getConfiguration('jpiAction') == 'sendSms') {

            if (isset($_options['answer'])) {
                $_options['message'] .= ' (' . implode(';', $_options['answer']) . ')';
            }
            $values = array();
            if (isset($_options['message']) && $_options['message'] != '') {
                $message = trim($_options['message']);
            } else {
                $message = trim($_options['title'] . ' ' . $_options['message']);
            }

            $action = 'http://' . $eqLogic->getConfiguration('jpiIp') . ':' . $eqLogic->getConfiguration('jpiPort') . '/?action=sendSms' . '&' . $this->getConfiguration('jpiParametres') . '&message=' . urlencode($message) . '&' . $this->getConfiguration('jpiOptions') . '&__JPIPLUG=1';
            $response = $eqLogic->executerequest($action);
            if (!preg_match("/\bok\b/i", $response)) {
                $cmd = $eqLogic->getCmd(null, 'infosentsms');
                $cmd->event("0");
            } else {
                $cmd = $eqLogic->getCmd(null, 'infosentsms');
                $cmd->event("1");
            }
            $eqLogic->executeinfo($eqLogic->getId());
        } elseif ($this->getConfiguration('jpiAction') == 'tts') {
            $action = 'http://' . $eqLogic->getConfiguration('jpiIp') . ':' . $eqLogic->getConfiguration('jpiPort') . '/?action=tts&message=' . urlencode($_options['message']) . $this->getConfiguration('jpiParametres') . '&' . $this->getConfiguration('jpiOptions') . '&__JPIPLUG=1';
            $eqLogic->executerequest($action);
        } else {
            if ($this->getConfiguration('jpiAction') !== '') {
                $action = 'http://' . $eqLogic->getConfiguration('jpiIp') . ':' . $eqLogic->getConfiguration('jpiPort') . '/?action=' . $this->getConfiguration('jpiAction') . $this->getConfiguration('jpiParametres') . '&' . $this->getConfiguration('jpiOptions') . '&__JPIPLUG=1';
                $eqLogic->executerequest($action);
            }
        }
    }

}

?> 