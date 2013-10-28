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

class VectorBetaHooks {
	static function getPreferences( $user, &$prefs ) {
		global $wgExtensionAssetsPath;

		$prefs['betafeatures-vector-typography-update'] = array(
			'label-message' => 'vector-beta-feature-typography-message',
			'desc-message' => 'vector-beta-feature-typography-description',
			'info-link' => 'https://www.mediawiki.org/wiki/Typography_Update',
			'discussion-link' => 'https://www.mediawiki.org/wiki/Talk:Typography_Update',
			'screenshot' => $wgExtensionAssetsPath . '/VectorBeta/typography-beta.svg',
			'requirements' => array(
				'skins' => array( 'vector' ),
			),
		);

		return true;
	}

	static function skinVectorStyleModules( $skin, &$modules ) {
		if ( BetaFeatures::isFeatureEnabled( $skin->getUser(), 'betafeatures-vector-typography-update' ) ) {
			$index = array_search( 'skins.vector', $modules );
			if ( $index !== false ) {
				array_splice( $modules, $index, 1 );
			}
			$modules[] = 'skins.vector.beta';
		}

		return true;
	}
}
