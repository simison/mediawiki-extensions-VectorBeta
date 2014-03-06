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
		global $wgExtensionAssetsPath, $wgVectorBetaPersonalBar;

		$screenshotFileName = '/VectorBeta/typography-beta.svg';
		$language = RequestContext::getMain()->getLanguage();
		$dir = $language->getDir();
		if ( $language->isRtl() ) {
			if ( in_array( $language->getCode(), array( 'he', 'yi' ) ) ) {
				$screenshotFileName = '/VectorBeta/typography-beta-hebr.svg';
			} else {
				$screenshotFileName = '/VectorBeta/typography-beta-arab.svg';
			}
		}

		$prefs['betafeatures-vector-typography-update'] = array(
			'label-message' => 'vector-beta-feature-typography-message',
			'desc-message' => 'vector-beta-feature-typography-description',
			'info-link' => 'https://www.mediawiki.org/wiki/Typography_refresh',
			'discussion-link' => 'https://www.mediawiki.org/wiki/Talk:Typography_refresh',
			'screenshot' => $wgExtensionAssetsPath . $screenshotFileName,
			'requirements' => array(
				'skins' => array( 'vector' ),
			),
		);

		if ( $wgVectorBetaPersonalBar ) {
			$prefs['betafeatures-vector-compact-personal-bar'] = array(
				'label-message' => 'vector-beta-feature-compact-personal-bar-message',
				'desc-message' => 'vector-beta-feature-compact-personal-bar-description',
				'info-link' => 'https://www.mediawiki.org/wiki/Compact_Personal_Bar',
				'discussion-link' => 'https://www.mediawiki.org/wiki/Talk:Compact_Personal_Bar',
				'screenshot' => "$wgExtensionAssetsPath/VectorBeta/compactPersonalBar-$dir.svg",
				'requirements' => array(
					'skins' => array( 'vector' ),
				),
			);
		}

		return true;
	}

	/**
	 * Handler for SkinVectorStyleModules
	 * @param Skin $skin
	 * @param array $modules
	 * @return bool
	 */
	static function skinVectorStyleModules( $skin, &$modules ) {
		if ( class_exists( 'BetaFeatures')
			&& BetaFeatures::isFeatureEnabled( $skin->getUser(), 'betafeatures-vector-typography-update' )
		) {
			$index = array_search( 'skins.vector.styles', $modules );
			if ( $index !== false ) {
				array_splice( $modules, $index, 1 );
			}
			$modules[] = 'skins.vector.beta';
		} elseif ( !class_exists( 'BetaFeatures' ) ) {
			wfDebugLog( 'VectorBeta', 'The BetaFeatures extension is not installed' );
		}
		return true;
	}

	/**
	 * Handler for BeforePageDisplay
	 * @param OutputPage $out
	 * @param Skin $skin
	 * @return bool
	 */
	static function onBeforePageDisplay( &$out, &$skin ) {
		if ( class_exists( 'BetaFeatures' ) ) {
			$out->addModules( array(
				'skins.vector.compactPersonalBar.defaultTracking',
			) );
			if ( BetaFeatures::isFeatureEnabled( $out->getSkin()->getUser(), 'betafeatures-vector-compact-personal-bar' ) ) {
				$out->addModules( array(
					'skins.vector.compactPersonalBar',
				) );
			}
		} else {
			wfDebugLog( 'VectorBeta', 'The BetaFeatures extension is not installed' );
		}

		return true;
	}

	/**
	 * ResourceLoaderRegisterModules hook handler
	 * Registering our EventLogging schema modules
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/ResourceLoaderRegisterModules
	 *
	 * @param ResourceLoader &$resourceLoader The ResourceLoader object
	 * @return bool Always true
	 */
	public static function onResourceLoaderRegisterModules( ResourceLoader &$resourceLoader ) {
		global $wgResourceModules;

		if ( class_exists( 'ResourceLoaderSchemaModule' ) ) {
			$wgResourceModules[ 'skins.vector.compactPersonalBar.schema' ] = array(
				'class'  => 'ResourceLoaderSchemaModule',
				'schema' => 'PersonalBar',
				'revision' => 7829128,
			);
		}

		return true;
	}

}
