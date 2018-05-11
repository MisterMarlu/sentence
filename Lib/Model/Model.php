<?php
/**
 * @created 02.08.2017
 * @author lbraun
 * @licence MIT
 */

namespace Lib\Model;


use Lib\Collection;

class Model {
    protected static $table;

    protected static $instance = null;

    public function __construct( $args = [] ) {
        foreach ( $args as $key => $value ) {
            $this->__set( $key, $value );
        }

        static::$instance = $this;

        return $this;
    }

    public function __get( $prop ) {
        return $this->{$prop} ?? null;
    }

    public function __set( $prop, $value ) {
        $this->{$prop} = $value;
    }

    private static function getInstance( $args = [] ) {
        if ( static::$instance ) {
            return static::$instance;
        }

        return new self( $args );
    }

    public static function save( $args = [] ) {
        $self = self::getInstance( $args );
        $id = Collection::save( $self );

        return self::find( $id );
    }

    public static function find( $id ) {
        $args = Collection::find( $id, static::$table );

        return new self( $args );
    }

    public static function where( $column, $operator, $value = '' ) {
        if ( empty( $value ) ) {
            $value = $operator;
            $operator = '=';
        }

        $result = Collection::where( static::$table, $column, $operator, $value );
        $sentences = [];

        foreach ( $result as $data ) {
            $sentences[] = self::find( $data['id'] );
        }

        return $sentences;
    }

    public static function all() {
        $result = Collection::all( static::$table );
        $sentences = [];

        foreach ( $result as $data ) {
            $sentences[] = self::find( $data['id'] );
        }

        return $sentences;
    }
}