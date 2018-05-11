<?php
/**
 * @created 02.08.2017
 * @author lbraun
 * @licence MIT
 */

/**
 * @param $key
 * @return mixed
 */
function config( $key = '' ) {
    $path = 'config/%s';
    $config = [];

    if ( strlen( $key ) < 1 ) {
        $dir = scandir( 'config' );
        foreach ( $dir as $file ) {
            if ( !is_file( $dir . '/' . $file ) ) {
                continue;
            }

            $key = str_replace( '.php', '', $file );
            $value = require basePath( sprintf( '%s/%s', $dir, $file ) );
            $config[$key] = $value;
        }

        return $config;
    }

    if ( strpos( $key, '.' ) === false ) {
        $key = sprintf( '%s.%s', $key, 'php' );
        $filePath = basePath( sprintf( $path, $key ) );

        if ( is_file( $filePath ) ) {
            $config = require $filePath;
        }

        return $config;
    }

    $keyArray = explode( '.', $key );
    $file = sprintf( '%s.%s', $keyArray[0], 'php' );
    $tmpKey = $keyArray[1];
    $filePath = basePath( sprintf( $path, $file ) );

    if ( is_file( $filePath ) ) {
        $config = require $filePath;
    }

    return isset( $config[$tmpKey] ) ? $config[$tmpKey] : null;
}

/**
 * @param string $path
 * @return string
 */
function basePath( $path = '' ) {
    $basePath = __DIR__;

    return $basePath . ( $path ? '/' . $path : $path );
}