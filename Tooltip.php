<?php

if ( function_exists( 'wfLoadExtension' ) ) {
	wfLoadExtension( 'Tooltip' );
	// Keep i18n globals so mergeMessageFileList.php doesn't break
	$wgMessagesDirs['Tooltip'] = __DIR__ . '/i18n';
	$wgExtensionMessagesFiles['TooltipMagic'] = __DIR__ . '/Tooltip.i18n.magic.php';
	wfWarn(
		'Deprecated PHP entry point used for the Tooltip extension. ' .
		'Please use wfLoadExtension() instead, ' .
		'see https://www.mediawiki.org/wiki/Special:MyLanguage/Manual:Extension_registration for more details.'
	);
	return;
} else {
	die( 'This version of the Tooltip extension requires MediaWiki 1.35+' );
}
