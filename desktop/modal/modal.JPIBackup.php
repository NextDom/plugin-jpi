<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
if (init('id') == '') {
	throw new Exception(__('L\'id ne peut etre vide', __FILE__));
}
$id = init('id');
$jpi = JPI::byId($id);

if (!is_object($jpi)) {
	throw new Exception(__('L\'équipement est introuvable : ', __FILE__) . init('id'));
}
if ($jpi->getEqType_name() != 'JPI') {
	throw new Exception(__('Cet équipement n\'est pas de type JPI : ', __FILE__) . $jpi->getEqType_name());
}
$dir = calculPath(config::byKey('backupdDir', 'JPI')) . '/'.$id;

$files = glob($dir . '/*.json');
?>
<?php
echo '<a class="btn btn-success  pull-right" target="_blank" href="core/php/downloadFile.php?pathfile=' . urlencode($dir . '/*') . '" ><i class="fa fa-download"></i> {{Tout télécharger}}</a>';
?>
<?php
$i = 0;
	echo  '<br>';
foreach ($files as $file) {
	echo '<div>';
	echo '<a class="btn btn-xs btn-success" target="_blank"  href="core/php/downloadFile.php?pathfile=' . urlencode($file) . '" ><i class="fa fa-download"></i> {{Télécharger}}</a> ';
	echo  '<pre>';
  	echo basename($file);
 	echo '</pre>';
	echo '</div>';
}
?>
