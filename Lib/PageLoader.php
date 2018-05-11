<?php
/**
 * @created 23.05.2017
 * @author lbraun
 * @licence MIT
 */

namespace Lib;


use Lib\Controller\SentenceController;

class PageLoader {

    private static $instance = null;

    public function __construct() {
        self::$instance = $this;
    }

    private static function getInstance() {
        if ( self::$instance ) {
            return self::$instance;
        }

        return new self();
    }

    private function getRoot() {
        $root = explode( '/', $_SERVER['SCRIPT_NAME'] );
        $root[count( $root ) - 1] = '';

        return implode( '/', $root );
    }

    public static function getHeader() {
        $title = Url::currentPage() == 'index' ? '' : Url::currentPage() . ' | ';
        $root = self::getInstance()->getRoot();

        $args = [
            'title' => ucwords( $title ),
            'root' => $root,
        ];

        return self::getInstance()->renderTemplate( 'header', $args );
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public static function getContent() {
        return self::getInstance()->renderPage();
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    private function renderPage() {
        $page = Url::currentPage();
        $args = Url::currentPageArgs();
        $methodName = 'get' . ucfirst( $page ) . 'Args';

        if ( !method_exists( $this, $methodName ) ) {
            throw new \Exception( 'Page "' . $page . '" doesn\'t exists.' );
        }

        $args = call_user_func_array( [ $this, $methodName ], $args );

        return $this->renderTemplate( $page, $args );
    }

    private function getArchiveArgs( $args = [] ) {
        $elements = SentenceController::getAll();

        $args = [
            'root' => self::getInstance()->getRoot(),
            'elements' => $elements,
        ];

        return $args;
    }

    private function getIndexArgs( $args = [] ) {
        $sentence = SentenceController::getLast();

        $args = [
            'date' => date( 'd.m.Y', $sentence->date ),
            'quotation' => nl2br( $sentence->sentence ),
            'workmate' => $sentence->workmate,
        ];

        return $args;
    }

    private function getPageArgs( $args ) {
        $sentence = SentenceController::get( $args['id'] );

        $args = [
            'date' => date( 'd.m.Y', $sentence->date ),
            'quotation' => nl2br( $sentence->sentence ),
            'workmate' => $sentence->workmate,
        ];

        return $args;
    }

    private function renderTemplate( $template, $args = [] ) {
        $templateFile = 'templates/' . $template . '.php';

        return Template::load( $templateFile, $args );
    }
}