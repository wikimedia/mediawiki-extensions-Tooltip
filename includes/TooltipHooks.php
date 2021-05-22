<?php

class TooltipHooks {
	/**
	 * @param Parser $parser
	 */
	public static function wfToolTipRegisterParserHooks( $parser ) {
		$parser->setHook( 'tooltip', [ __CLASS__, 'renderToolTip' ] );
		$parser->setFunctionHook( 'tooltip', [ __CLASS__, 'wfTooltipParserFunctionRender' ] );
	}

	/**
	 * @param OutputPage $out
	 */
	public static function wfTooltipBeforePageDisplay( $out ) {
		$out->addModules( 'ext.tooltip' );
	}

	/**
	 * @param Parser $parser
	 * @param string $basetext
	 * @param string $tooltiptext
	 * @param int $x
	 * @param int $y
	 * @return array
	 */
	public static function wfTooltipParserFunctionRender(
		$parser, $basetext = '', $tooltiptext = '', $x = 0, $y = 0
	) {
		$output = self::renderToolTip( $tooltiptext, [
			'text' => $basetext,
			'x' => $x,
			'y' => $y,
		], $parser );

		return [
			$output,
			'nowiki' => false,
			'noparse' => true,
			'isHTML' => false,
		];
	}

	/**
	 * @param string $input
	 * @param array $argv
	 * @param Parser $parser
	 * @return string
	 */
	public static function renderToolTip( $input, $argv, $parser ) {
		$text = 'see tooltip';
		$xoffset = 0;
		$yoffset = 0;
		foreach ( $argv as $key => $value ) {
			switch ( $key ) {
				case 'text':
					$text = $value;
					break;
				case 'x':
					$xoffset = intval( $value );
					break;
				case 'y':
					$yoffset = intval( $value );
					break;
				default:
					wfDebug( __METHOD__ . ": Requested '$key ==> $value'\n" );
					break;
			}
		}

		$output = '';

		if ( $input != '' ) {
			$tooltipid = uniqid( 'tooltipid' );
			$parentid = uniqid( 'parentid' );
			$output .= "<span id='$tooltipid' class='xstooltip'>" .
				// @phan-suppress-next-line PhanUndeclaredMethod
				$parser->unstrip( $parser->recursiveTagParse( $input ), $parser->mStripState ) .
				"</span>";
			// phpcs:ignore Generic.Files.LineLength.TooLong
			$output .= "<span id='$parentid' class='xstooltip_body' onmouseover=\"xstooltip_show('$tooltipid', '$parentid', $xoffset, $yoffset);\" onmouseout=\"xstooltip_hide('$tooltipid');\">" .
				// @phan-suppress-next-line PhanUndeclaredMethod
				$parser->unstrip( $parser->recursiveTagParse( $text ), $parser->mStripState ) .
				"</span>";
		}

		return $output;
	}
}
