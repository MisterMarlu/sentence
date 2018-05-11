<?php
/**
 * @created 23.05.2017
 * @author lbraun
 * @licence MIT
 */

namespace Lib;


class Template {

    /**
     * @param string $templateFile
     * @param array $templateVariables
     * @return mixed
     */
    public static function load( string $templateFile, array $templateVariables = [] ) {
        extract( $GLOBALS );
        extract( $templateVariables );
        unset( $templateVariables );

        return include $templateFile;
    }
}