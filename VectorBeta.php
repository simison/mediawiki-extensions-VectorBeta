<?php
/*
 * This file is part of the MediaWiki extension VectorBeta.
 *
 * VectorBeta is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * VectorBeta is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with VectorBeta.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @file
 * @ingroup extensions
 */

$localBasePath = dirname( __DIR__ ) . '/VectorBeta';
$remoteExtPath = 'VectorBeta';

$wgExtensionCredits['betafeatures'][] = array(
	'author' => array( 'Jon Robson', 'Trevor Parscal', 'Juliusz Gonera' ),
	'descriptionmsg' => 'vectorbeta-desc',
	'name' => 'VectorBeta',
	'path' => __FILE__,
	'url' => 'https://www.mediawiki.org/wiki/Extension:VectorBeta',
);

/**
 * Enable Compact Personal Bar.
 */
$wgVectorBetaPersonalBar = false;

$wgResourceModules = array_merge( $wgResourceModules, array(
	'skins.vector.beta' => array(
		'styles' => array(
			'less/styles.less',
		),
		'remoteExtPath' => $remoteExtPath,
		'localBasePath' => $localBasePath,
	),
) );

$wgAutoloadClasses['VectorBetaHooks'] = __DIR__ . '/VectorBeta.hooks.php';

$wgExtensionMessagesFiles['VectorBeta'] = __DIR__ . '/VectorBeta.i18n.php';

$wgVBResourceBoilerplate = array(
	'localBasePath' =>  __DIR__,
	'remoteExtPath' => 'VectorBeta',
);

$wgResourceModules['skins.vector.compactPersonalBar'] = $wgVBResourceBoilerplate + array(
	'styles' => array(
		'resources/compactPersonalBar/compactPersonalBar.less',
	),
	'scripts' => array(
		'resources/compactPersonalBar/compactPersonalBar.js',
	),
	'messages' => array(
		'notifications',
		'privacy',
		'privacypage',
		'help',
		'helppage',
	),
	'position' => 'top',
);

$wgHooks['GetBetaFeaturePreferences'][] = 'VectorBetaHooks::getPreferences';
$wgHooks['SkinVectorStyleModules'][] = 'VectorBetaHooks::skinVectorStyleModules';
$wgHooks['BeforePageDisplay'][] = 'VectorBetaHooks::onBeforePageDisplay';
