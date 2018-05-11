<div class="headline border-radius-top">
    <h1>Archive</h1>
</div>
<div class="content border-radius-bottom">
    <ul>
        <?php foreach ( $elements as $element ): ?>
            <li>
                <a href="<?php echo $root; ?>?page&id=<?php echo $element->id; ?>"><?php echo date( 'Y-m-d', $element->date ); ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
