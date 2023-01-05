
<?php

if (post_password_required())

    return;

?>
<div  class="" id="reviews-main" a>
    <div class="descr ">
        <div class="card">
            <div class="card-header">
        <h4 class="mb-0">
            <i class="fa fa-plus"></i>
            <button id="product_reviews_button" class="btn btn-block collapsed showing-list" data-toggle="collapse" data-target="#product_reviews" aria-expanded="true" aria-controls="collapseOne">
                مشاهده نظرات کاربران
            </button>

        </h4>
            </div>
        <!--<form action="#" method="post">-->
            <div class="collapse show" aria-labelledby="headingOne" data-parent="#reviews-main">
                <div class="card-body collapse" id="product_reviews">
    <?php if (have_comments()) : ?>

<!--        <h3 class="comment-title"><?php /*echo get_comments_number(); */?> دیدگاه برای این مطلب ثبت شده است</h3>
-->
        <div id="comment-section" class="reviews-top reviews-bottom">
            <!--<ol>-->
            <?php

            $comment_features=product_comments::get_comment_post_features(get_the_ID());
            $comments=get_comments(['post_id'=>get_the_ID()]);
            foreach ($comment_features as $comment_feature){
                $has_comment=0;
                foreach ($comments as $comment){
                    $this_comment_features=get_comment_meta($comment->comment_ID,'features',true);
                    //var_dump($this_comment_features);
                    //var_dump($comment_feature['id']);
                    if (in_array($comment_feature['id'],$this_comment_features)){
                        $has_comment=1;
                        break;
                    }
                }

                if ($has_comment==1) {
                    ?>
                    <div class="card">
                        <div class="card-header">
                            <h4 class="mb-0">
                                <i class="fa fa-plus review-subject"></i>
                                <button id="product_reviews_button_<?= $comment_feature['id'] ?>"
                                        class="btn btn-block collapsed showing-list" data-toggle="collapse"
                                        data-target="#product_reviews_<?= $comment_feature['id'] ?>"
                                        aria-expanded="true" aria-controls="collapseOne">
                                    نمایش نظرات مربوط به <?= $comment_feature['name'] ?>
                                </button>

                            </h4>
                        </div>
                        <div class="collapse show" aria-labelledby="headingOne" data-parent="#comment-section">
                            <div class="card-body collapse" id="product_reviews_<?= $comment_feature['id'] ?>">
                                <?php wp_list_comments( array( 'callback'                   => 'comments_callback',
                                                               'current_comment_feature_id' => $comment_feature['id']
                                ) ); ?>
                            </div>
                        </div>
                    </div>
                    <!--</ol>--><!-- .commentlist -->
                    <?php
                }
            }
            ?>
        </div>


        <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : // are there comments to navigate through  ?>

            <div class="box comment-pagination">

                <?php paginate_comments_links(); ?>

            </div>

        <?php endif; // check for comment navigation  ?>

        <?php

        /*$data=['ajax_url'=>admin_url().'admin-ajax.php'];
        wp_localize_script('mousetrc','data',$data);*/
        /* If there are no comments and comments are closed, let's leave a note.

         * But we only want the note on posts and pages that had comments in the first place.

         */

        if (!comments_open() && get_comments_number()) :

            ?>

            <p class="nocomments">نظری ثبت نشده است</p>

        <?php endif; ?>
            </div>
        </div>
</div>
</div>

    <?php endif; // have_comments()  ?>
        <div class="reviews-bottom">
    <?php

    $commenter=  wp_get_current_commenter();

    $req=  get_option('require_name_email');

    $aria_req=($req?"aria-required='true'":'');

    $comment_features=product_comments::get_comment_post_features(get_the_ID());
    $features_list='';
    foreach ($comment_features as$comment_feature){
        $features_list.='<li onclick="add_features(this)" onmouseleave="feature_hover_out(this)" onmouseenter="feature_hover_in(this)" data-id="'.$comment_feature['id'].'" data-attr-id="1"><span class="square"></span>'.$comment_feature['name'].'</li>';
    }

    $fields =  array(

        'features'=>'<p>مشخص کنید که نظر شما در مورد کدام یک از ویژگی های محصول است</p><div class="row features-selected" ></div><ul class="filter-top col-sm-4"><li onmouseenter="filter_hover_in(this)" onmouseleave="filter_hover_out(this)" class="col-sm-3"><img src="'. theme_asset::image('down-arrow.png').'"><span class="title">انتخاب ویژگی</span><div class="options "><ul>'.$features_list.'</ul></div></li></ul>',

        'author' => '<div class="form-group row-grid">' . '<label for="author">' . __( 'نام:' ) . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) .

            '<input class="" id="author" name="author" type="text" onfocus="this.value = \'\';"  value="' . esc_attr( $commenter['comment_author'] ) . '" placeholder="نام شما :" ' . $aria_req . ' /></div>',

        'email'  => '<div class="form-group row-grid"><label for="email">' . __( 'ایمیل شما:' ) . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) .

            '<input class="" id="email" name="email" type="email" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" placeholder="ایمیل شما:" ' . $aria_req . ' /></div>');
    //var_dump($fields['features']);

    //var_dump($comidddd);
    $comments_args=array(

        'fields'=>$fields,

        'title_reply'=>'افزودن نظر',

        'label_submit'=>'ارسال نظر',

        'comment_notes_before'=>'<p>ایمیل شما منتشر نخواهد شد</p>',

        'comment_notes_after'=>'<p class="comment-notes-after"></p>'

    );

    comment_form($comments_args); ?>

        </div>

        <!--</form>-->
    </div>
</div><!-- #comments .comments-area -->

<?php

function comments_callback($comment,$args, $depth){
if (in_array($args['current_comment_feature_id'],get_comment_meta(get_comment_ID(),'features',true))){
    $GLOBALS['comment'] = $comment;
    switch($comment->comment_type):
        case 'pingback' :
        case 'trackback' :
            break;
        default :
            // Proceed with normal comments.
            global $post;
            ?>
        <div id="comment-<?php comment_ID(); ?>" <?php /*comment_class(); */
        ?> class="reviews-right col-lg-12">
            <div>
                <?php if ( '0' == $comment->comment_approved ) : ?>
                    <p class="bg-danger comment-awaiting-moderation">این دیدگاه پس از بررسی ادمین منتشر خواهد شد</p>
                <?php endif; ?>
                <ul>
                    <li><a id="autho-comment-<?php comment_ID(); ?>" href=""><?= get_comment_author_link(); ?></a></li>
                </ul>
                <?php comment_text(); ?>
                <?php comment_reply_link( array_merge( $args, array(
                    'reply_text' => '<span id="reply-to-' . get_comment_ID() . '" class="reply"><button onclick="" type="button" class="btn btn-info">پاسخ به دیدگاه</button></span>',
                    'depth'      => $depth,
                    'max_depth'  => $args['max_depth']
                ) ) ); ?>
                <div class="btn-group likedislike" data-toggle="buttons" data-commentID="<?php comment_ID(); ?>">
                    <?php
                    $rates = product_comments::get_like_dislike( get_comment_ID() );
                    ?>
                    <label id="like-<?php comment_ID(); ?>" class="btn btn-primary dislike">
                        <input type="radio" name="options" id="option2" autocomplete="off"><img
                            src="<?= theme_asset::image( 'dislike.png' ) ?>">
                        <span><?= $rates['dislike'] ?></span>
                    </label>

                    <label id="dislike-<?php comment_ID(); ?>" class="btn btn-primary  like">
                        <input type="radio" name="options" id="option1" autocomplete="off" checked> <img
                            src="<?= theme_asset::image( 'like.png' ) ?>">
                        <span><?= $rates['like'] ?></span>
                    </label>
                </div>
                <div id="reply-to-comment-<?php comment_ID(); ?>">
                    <div class="reply">
                    </div><!-- .reply -->
                </div><!-- #comment-## -->
            </div>
            </div>
            <?php
            break;
    endswitch; // end comment_type check
}
}

?>