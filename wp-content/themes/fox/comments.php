<?php
if ( post_password_required() )
	return;
	
if ( !comments_open() )
	return;	
?>

<div id="comments" class="comments-area single-section single-component">

	<?php // You can start editing here -- including this comment! ?>

	<?php if ( have_comments() ) : ?>
    
    <h2 class="comments-title single-heading">
        <span>
			<?php
				printf( _n( '1 Comment', '%s Comments', get_comments_number(), 'wi' ),
					number_format_i18n( get_comments_number() ) );
			?>
		</span>
    </h2><!-- .comments-title -->
    
    <?php fox_comment_nav( 'above' ); ?>

    <ol class="commentlist">
        
        <?php 
        $args = [
            'avatar_size' => 120,
        ];
        wp_list_comments( $args ); ?>
        
    </ol><!-- .commentlist -->
    
    <?php fox_comment_nav( 'below' ); ?>
    
    <?php
    /* If there are no comments and comments are closed, let's leave a note.
     * But we only want the note on posts and pages that had comments in the first place.
     */
    if ( ! comments_open() && get_comments_number() ) : ?>
    
    <p class="nocomments"><?php _e( 'Comments are closed.' , 'wi' ); ?></p>
    
    <?php endif; ?>

	<?php endif; // have_comments() ?>	
	<?php
    $commenter = wp_get_current_commenter();
    $req = get_theme_mod( 'require_name_email' );
    $asterisk = ( $req ? ' *' : '');
    $aria_req = ( $req ? " aria-required='true'" : '' );

    $fields =  array(
        'author' =>
        '<p class="comment-form-author">' .
        '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
        '" size="30"' . $aria_req . ' placeholder="'. fox_word( 'name' ) .$asterisk.'" /></p>',

        'email' =>
        '<p class="comment-form-email">' .
        '<input id="email" name="email" type="email" value="' . esc_attr(  $commenter['comment_author_email'] ) .
        '" size="30"' . $aria_req . ' placeholder="'. fox_word( 'email' ) .$asterisk.'" /></p>',

        'url' =>
        '<p class="comment-form-url">' .
        '<input id="url" name="url" type="url" value="' . esc_attr( $commenter['comment_author_url'] ) .
        '" size="30" placeholder="'. fox_word( 'website' ) .'" /></p>',
    );

    $comment_field  =  '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" placeholder="'.fox_word( 'write_comment' ).'">' .
    '</textarea></p>';

	$args = array(
        'comment_notes_before'  =>  '<p class="comment-notes">' . esc_html__( 'Your email address will not be published.','wi' ) . '</p>',
		'comment_notes_after'	=>	'',
        'fields'                =>  $fields,
        'comment_field'         =>  $comment_field,
        'title_reply_before'    => '<h3 id="reply-title" class="comment-reply-title single-heading"><i class="feather-edit-2"></i>',
        
        'title_reply'          => fox_word( 'title_reply' ),
        'title_reply_to'          => fox_word( 'title_reply_to' ),
        'label_submit' => fox_word( 'post_comment' ),
        'cancel_reply_link'    => fox_word( 'cancel_reply' ),
	);

	?>
	<?php comment_form($args); ?>

</div><!-- #comments .comments-area -->