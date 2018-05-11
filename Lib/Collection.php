<?php
/**
 * @created 02.08.2017
 * @author lbraun
 * @licence MIT
 */

namespace Lib;


use Lib\Model\Model;

class Collection {
    private $connection = null;

    private $select = false;

    private $selectMultiple = false;

    public function __construct() {
        if ( !$this->connection ) {
            $this->setConnection();
        }

        return $this;
    }

    private function setConnection() {
        $config = \config( 'database' );
        $dsn = sprintf( '%s:host=%s;dbname=%s', $config['driver'], $config['host'], $config['name'] );
        $this->connection = new \PDO( $dsn, $config['username'], $config['password'] );
    }

    /**
     * @param Model $model
     * @return int
     */
    public static function save( $model ) {
        return $model->__get( 'id' ) ? self::update( $model ) : self::insert( $model );
    }

    public static function find( $id, $table ) {
        $self = new self();

        $self->select = true;
        $query = 'SELECT * FROM %s WHERE id=%d;';
        $properties = [
            $table,
            $id,
        ];

        return $self->execute( $query, $properties );
    }

    public static function all( $table ) {
        $self = new self();

        $self->selectMultiple = true;
        $query = 'SELECT * FROM %s;';
        $properties = [
            $table,
        ];

        return $self->execute( $query, $properties );
    }

    public static function where( $table, $column, $operator, $value ) {
        $self = new self();

        $self->select = true;
        $query = 'SELECT * FROM %s WHERE %s%s%s;';
        $properties = [
            $table,
            $column,
            $operator,
            $value,
        ];

        return $self->execute( $query, $properties );
    }

    /**
     * @param Model $model
     * @return int
     */
    private static function update( $model ) {
        $self = new self();

        $modelProps = get_object_vars( $model );
        $id = $modelProps['id'];
        unset( $modelProps['id'] );

        $values = [];
        foreach ( $modelProps as $key => $value ) {
            $values[] = sprintf( '%s=%s', $key, $value );
        }

        $query = 'UPDATE %s SET %s WHERE id=%d;';
        $properties = [
            $model->__get( 'table' ),
            implode( ', ', $values ),
            $id,
        ];

        $result = $self->execute( $query, $properties );
        var_dump($result);

        return 1;
    }

    /**
     * @param Model $model
     * @return int
     */
    private static function insert( $model ) {
        $self = new self();

        $modelProps = get_object_vars( $model );
        $columns = [];
        $values = [];

        foreach ( $modelProps as $key => $value ) {
            $columns[] = $key;
            $values[] = $value;
        }

        $query = 'INSERT INTO %s (%s) VALUES (%s);';
        $properties = [
            $model->__get( 'table' ),
            implode( ', ', $columns ),
            implode( ', ', $values ),
        ];

        $result = $self->execute( $query, $properties );
        var_dump($result);

        return 1;
    }

    private function execute( $queryString, $properties = [] ) {
        $query = vsprintf( $queryString, $properties );

        if ( !$this->select && !$this->selectMultiple ) {
            $statement = $this->connection->prepare( $query );

            return $statement->execute();
        }

        $statement = $this->connection->query( $query );

        return $this->select ? $statement->fetch(\PDO::FETCH_ASSOC) : $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
}