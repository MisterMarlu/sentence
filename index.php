<?php
require_once 'helper.php';
require_once 'vendor/autoload.php';

use Lib\PageLoader;
use Lib\Template;


//require_once 'autoload.php';

//$sentenceInfo = [
//    'sentence' => 'Das war wohl nichts',
//    'workmate' => 'Luca',
//    'date' => time(),
//];
//$sentence = new \Model\Sentence();
//$sentence->save( $sentenceInfo );

PageLoader::getHeader();

try {
    PageLoader::getContent();
} catch ( Exception $e ) {
    echo $e->getMessage();
}

Template::load( 'templates/footer.php' );
?>