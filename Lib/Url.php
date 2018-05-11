<?php
/**
 * @created 23.05.2017
 * @author lbraun
 * @licence MIT
 */

namespace Lib;


class Url {
    public static function get() {
        $http = $_SERVER['HTTPS'] ?? 'http';
        $host = $_SERVER['HTTP_HOST'];
        $uri = $_SERVER['REQUEST_URI'];

        return $http . '://' . $host . $uri;
    }

    public static function currentPage() {
        if ( empty( $_SERVER['QUERY_STRING'] ) ) {
            return 'index';
        }

        $page = explode( '&', $_SERVER['QUERY_STRING'] );

        return $page[0];
    }

    public static function currentPageArgs() {
        $page = explode( '&', $_SERVER['QUERY_STRING'] );
        array_splice( $page, 0, 1 );
        $args = [];

        foreach ( $page as $key => $value ) {
            $tmp = explode( '=', $value );
            $args[$tmp[0]] = $tmp[1];
        }

        return [ $args ] ?? [];
    }
}