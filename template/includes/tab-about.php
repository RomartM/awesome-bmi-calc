<?php
// Get post types
$args       = array(
    'public' => true,
);
$post_types = get_post_types( $args, 'objects' );
?>

<select id="post_types">
    <?php foreach ( $post_types as $post_type_obj ):
        $labels = get_post_type_labels( $post_type_obj );
        ?>
        <option value="<?php echo esc_attr( $post_type_obj->name ); ?>"><?php echo esc_html( $labels->name ); ?></option>
    <?php endforeach; ?>
</select>

<div class="posts">
  <ul id="posts_data">

  </ul>
</div>
