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

$wgVBResourceBoilerplate = array(
	'localBasePath' =>  __DIR__,
	'remoteExtPath' => 'VectorBeta',
);

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

/**
 * Enable Winter experiment.
 */
$wgVectorBetaWinter = false;

$wgAutoloadClasses['VectorBetaHooks'] = __DIR__ . '/VectorBeta.hooks.php';

$wgExtensionMessagesFiles['VectorBeta'] = __DIR__ . '/VectorBeta.i18n.php';

$wgResourceModules = array_merge( $wgResourceModules, array(
	'skins.vector.beta' => $wgVBResourceBoilerplate + array(
		'styles' => array(
			'less/styles.less',
		),
	),

	'skins.vector.header.beta' => $wgVBResourceBoilerplate + array(
		'styles' => array(
			'less/header.less',
		),
		// Other ensures this loads after the Vector skin styles
		'group' => 'other',
	),

	'skins.vector.headerjs.beta' => $wgVBResourceBoilerplate + array(
		'dependencies' => array(
			'jquery.throttle-debounce',
		),
		'scripts' => array(
			'javascripts/header.js',
		),
		'styles' => array(
			'less/search-suggestions.less',
		),
	),
) );


// FIXME: Move these into array_merge above
$wgResourceModules['skins.vector.compactPersonalBar.trackClick'] = $wgVBResourceBoilerplate + array(
	'dependencies' => array(
		'mediawiki.user',
		'ext.eventLogging',
		'skins.vector.compactPersonalBar.schema',
	),
	'scripts' => array(
		'resources/compactPersonalBar/trackClick.js',
	),
	'position' => 'top',
);

$wgResourceModules['skins.vector.compactPersonalBar.defaultTracking'] = $wgVBResourceBoilerplate + array(
	'dependencies' => array(
		'skins.vector.compactPersonalBar.trackClick',
	),
	'scripts' => array(
		'resources/compactPersonalBar/defaultTracking.js',
	),
	'position' => 'top',
);

$wgResourceModules['skins.vector.compactPersonalBar'] = $wgVBResourceBoilerplate + array(
	'dependencies' => array(
		'skins.vector.compactPersonalBar.trackClick',
	),
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
$wgHooks['BeforePageDisplay'][] = 'VectorBetaHooks::onBeforePageDisplay';
$wgHooks['SkinVectorStyleModules'][] = 'VectorBetaHooks::skinVectorStyleModules';
$wgHooks['BeforePageDisplay'][] = 'VectorBetaHooks::onBeforePageDisplay';
$wgHooks['ResourceLoaderRegisterModules'][] = 'VectorBetaHooks::onResourceLoaderRegisterModules';
