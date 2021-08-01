<?php
$args = [
    'taxonomy' => ['label', 'labels-newspapers'],
    'hide_empty' => false,
];

$terms = get_terms( $args );
?>

<div class="terms__list owl-carousel">
    <?php foreach( $terms as $term ) : ?>
        <div class="term__item">
            <a href="<?php echo get_term_link( $term->term_id ) ?>" class="term__img">
                <img src="<?php echo get_field( 'label_logo', $term ) ?>"
                     alt="<?php echo $term->slug ?>">
            </a>
        </div>
    <?php endforeach; ?>
</div>
