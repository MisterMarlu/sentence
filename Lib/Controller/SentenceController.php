<?php
/**
 * @created 23.05.2017
 * @author lbraun
 * @licence MIT
 */

namespace Lib\Controller;

use Lib\Model\Sentence;

class SentenceController {
    public static function getLast() {
        $sentences = Sentence::all();
        $id = 1;

        foreach ( $sentences as $sentence ) {
            $id = $sentence->id;
        }

        return Sentence::find( $id );
    }

    public static function getAll() {
        return Sentence::all();
    }

    public static function get( $id ) {
        return Sentence::find( $id );
    }
}