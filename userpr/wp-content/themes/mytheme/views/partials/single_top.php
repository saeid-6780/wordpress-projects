<?php
/*if (isset($_POST['basket_quantity'])) {
    $quantity=intval($_POST['basket_quantity']);
    //product_basket::add($_POST['product_id'],$quantity);
    do_action('add_to_basket',$_POST['product_id'],$quantity);
    var_dump($_SESSION['basket']);
}*/
?>

<?php
if (have_posts()){
while (have_posts()) {
    the_post();
global $this_product;
$this_product=get_the_ID();
$product_cat=get_the_terms(get_the_ID(),'product_category')[0];
$product_cat_link=get_term_link($product_cat->term_id);
    ?>


    <div class="content remodal-bg">
        <div class="single-wl3">
            <div class="container">

	            <p class="product-nav">
	<span>
		<a data-productnavlink="<?= get_home_url(); ?>" class="product-nav-link">همه دسته ها</a>
	</span>
	<span>
		<i class="fa fa-angle-left"></i>
		<a data-productnavlink="<?= $product_cat_link; ?>" class="product-nav-link"><?= $product_cat->name; ?></a>
	</span>
	            </p>

                <div class="single-grids">
                    <div class="col-md-10 single-grid">
                        <div clas="single-top">
                            <div class="single-left">
                                <div id="product-slider" class="flexslider">
                                    <ul class="slides">
                                        <?php
                                        $pro_gallery_images=get_post_meta(get_the_ID(),'product_slider',true);
                                        foreach($pro_gallery_images as $key=>$pro_gallery_image){
	                                        $pro_gallery_image1=str_replace('.jpg','',$pro_gallery_image);
	                                        $pro_color_hex=explode('_',$pro_gallery_image1)[1];

                                            ?>
                                            <li data-thumb="<?= $pro_gallery_image ?>">
                                                <div id="slider-id-<?= $pro_color_hex; ?>" class="thumb-image"><img src="<?= $pro_gallery_image ?>" data-imagezoom="true" class="img-responsive"></div>
                                            </li>
                                            <?php
                                        }
                                        ?>

                                    </ul>
                                </div>
                            </div>
                            <div class="single-right simpleCart_shelfItem">
                                <h4><?= get_the_title(); ?></h4>
                                <script>
                                    window.REMODAL_GLOBALS = {
                                        NAMESPACE: 'remodal',
                                        DEFAULTS: {
                                            hashTracking: false,
                                            closeOnEscape:false,
                                            closeOnOutsideClick:false
                                        }
                                    };
                                </script>
                                <?php
                                wp_register_script('mousetrc',THEME_URL.'/assets/js/mousetrcscripts.js','jquery',false,false);
                                wp_enqueue_script('mousetrc');

                                $smtdb=new general_smtdb();
                                $user_rate=$smtdb->search_product_rate(get_the_ID());

                                $ajaxdata=[];
                                $ajaxdata['ajax_url']=admin_url().'admin-ajax.php';
                                $ajaxdata['product_id']=get_the_ID();
                                $ajaxdata['user_set_rate']=$user_rate;
                                $ajaxdata['page_type']=2;
                                wp_localize_script('mousetrc','ajaxdata',$ajaxdata);

                                /////

                                /*$email_modal_close_num=general_lottery::get_close_num();
                                //var_dump($email_modal_close_num);
                                if ($email_modal_close_num<=3) {
                                    if ($smtdb->search_email() == null) {
                                        wp_localize_script('mousetrc', 'email_collected', [
                                            'stat' => 0,
                                            'close_num' => $email_modal_close_num
                                        ]);
                                        general_lottery::add_close_num();
                                    }
                                }*/
                                //unset($_SESSION['close_email']['close_num']);
                                /////
                                $av_rate=get_post_meta(get_the_ID(),'product_rate',true)
                                ?>
                                <div id="product-rater" class="block">
                                    <div data-start-value="<?= $av_rate['avrate']/5 ?>" data-productid="<?= get_the_ID(); ?>" class="starbox small  autoupdate"></div>
                                    <div class="rate-description">
                                        <p class="average-rate-description">میانگین امتیازات این محصول: <?=round($av_rate['avrate'],2)  ?></p>
                                    </div>
                                </div>
                                <p id="product-price" class="price item_price"><?= product_product::price(get_the_ID())['org_price'] ?> تومان</p>
                                <div id="product-introduction" class="description">
                                    <p style="direction: rtl;"><span>بررسی اجمالی: </span> <?= get_the_excerpt() ?></p>
                                </div>
                                <?php
                                if (strlen(get_the_excerpt())>820) {
                                    ?>
                                    <p id="product-introduction-show-more">نمایش بیشتر</p>
                                    <?php
                                }
                                ?>
                                <form class="basket-quantity-form" action="" method="post">
                                <div class="color-quality">
                                    <h6>تعداد</h6>
                                    <div id="set-basket-quantity" class="quantity">
                                        <div class="quantity-select">
                                            <div class="entry value-minus1">&nbsp;</div>
                                            <div class="entry value1"><span>1</span></div>
                                            <input class="basket-quantity" name="basket_quantity" type="hidden" >
                                            <input class="" name="product_id" type="hidden" value="<?= get_the_ID(); ?>">
                                            <div class="entry value-plus1 active">&nbsp;</div>
                                        </div>
                                    </div>
                                </div>

                                <div id="add-to-basket" class="women">
                                    <a  data-text="Add To Cart" class="my-cart-b item_add">
افزودن به سبد خرید</a>
                                </div>
                                </form>

                                <script>
                                    $('.value-plus1').on('click', function () {
                                        var divUpd = $(this).parent().find('.value1'), newVal = parseInt(divUpd.text(), 10) + 1;
                                        divUpd.text(newVal);
                                    });

                                    $('.value-minus1').on('click', function () {
                                        var divUpd = $(this).parent().find('.value1'), newVal = parseInt(divUpd.text(), 10) - 1;
                                        if (newVal >= 1){
                                            divUpd.text(newVal);
                                        }
                                    });
                                    $('.my-cart-b.item_add').click(function () {
                                        var divUpd1 = $(this).parents('.basket-quantity-form').find('.entry.value1');
                                        newVal1 = parseInt(divUpd1.text(), 10);
                                        $(this).parents('.basket-quantity-form').find('input.basket-quantity').val(newVal1);
                                        $('.basket-quantity-form').submit();
                                    })
                                </script>

	                            <?php
	                            $pro_colors=get_post_meta(get_the_ID(),'product_colors',true);
	                            if (!empty($pro_colors)) {
		                            ?>
		                            <div class="social-icon product-colors">

			                            <h5 id="product-colors-title">رنگ های این محصول</h5>
			                            <?php
			                            foreach ( $pro_colors as $pro_color ) {
				                            ?>
				                            <a class="product-color" data-color="<?= $pro_color; ?>"><i class="icon"
				                                                                                        style="background-color: #<?= $pro_color; ?>;"></i></a>
				                            <?php
			                            }
			                            ?>

			                            <!--<a id="yellow-color" "><i class="icon1" style="background-color: yellow;"></i></a>
										<a id="blue-color" href="#"><i class="icon2" style="background-color: blue;"></i></a>
										<a id="green-color" href="#"><i class="icon3" style="background-color: green;"></i></a>
										<a id="orange-color" href="#"><i class="icon3" style="background-color: orange;"></i></a>
										<a id="black-color" href="#"><i class="icon3" style="background-color: black;"></i></a>-->
		                            </div>
		                            <?php
	                            }
	                            ?>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>

                    <script>
$('.product-color').click(function () {
	var colorHex=$(this).attr('data-color');
	console.log(colorHex);

    var index = $('#slider-id-'+colorHex).index(); console.log(index);  // will give you 2
    $('#product-slider').flexslider(index);
	index.
})
                    </script>

                    <div class="col-md-3 single-grid1">

                    </div>

                    <div class="clearfix"></div>
                </div>
                <div id="product-description" class="product-w3agile">
                    <h3 style="direction: rtl !important;" class="tittle1">توضیحات محصول</h3>
                    <div class="product-grids col-xs-12">

                        <div class="col-xs-12 product-grid1">
                            <div class="tab-wl3">
                                <div class="bs-example bs-example-tabs" role="tabpanel" data-example-id="togglable-tabs">
                                    <!--<ul  id="myTab" class="nav nav-tabs left-tab" >
                                        <li id="reviews" style="float: right !important;width: 170px;text-align: center" role="presentation" class=""><a href="#home" id="home-tab"  aria-controls="home" aria-expanded="true">نقد و بررسی</a></li>

                                        <li id="comments" style="float: right !important;width: 170px;text-align: center" role="presentation"><a href="#reviews-main" id="reviews-tab"  >نظرات کاربران (<?/*= get_comment_count(get_the_ID())['approved']; */?>) </a></li>


                                    </ul>-->

                                    <div id="myTabContent" >
                                        <div  id="home" >
                                            <div id="product-content" class="descr">
                                                <?php

                                                $product_content=get_the_content();
                                                $product_highlight=get_post_meta(get_the_ID(),'product_highlight',true);
                                                if (($product_content!='') || !ctype_space($product_highlight)){
                                                    ?>
                                                    <div id="product-describtion-section" class="card">
                                                    <div class="card-header">
                                                    <h3 class="mb-0">
                                                        <i class="fa fa-plus"></i>
                                                        <button id="product_description_button" class="btn btn-info btn-block collapsed showing-list" data-toggle="collapse" data-target="#product_description" aria-expanded="true" aria-controls="collapseOne">مشاهده نقد و بررسی تخصصی</button>
                                                    </h3>
                                                    </div>
                                                    <div class="collapse show" aria-labelledby="headingOne" data-parent="#product-content">
                                                    <div id="product_description" class="card-body collapse">
	                                                    <p><?= $product_content; ?></p>
	                                                    <?php
	                                                    if (!ctype_space($product_highlight)){
		                                                    ?>
		                                                    <p class="quote">
			                                                    <?= $product_highlight; ?>
		                                                    </p>

		                                                    <?php
	                                                    }
	                                                    ?>
                                                    </div>

                                                    </div>

                                                    </div>
                                                <?php
                                                }
                                                ?>
                                                <section id="product-attributes_section" class="section-fanni card">
                                                    <div class="card-header">
                                                        <h3  class="mb-0">
                                                            <i class="fa fa-plus"></i>
                                                            <button id="product_attributes_button" class="btn btn-info btn-block collapsed showing-list" data-toggle="collapse" data-target="#product_attributes" aria-expanded="true" aria-controls="collapseOne">مشاهده مشخصات محصول</button>
                                                        </h3>
                                                    </div>
                                                    <div  class="collapse show" aria-labelledby="headingOne" data-parent="#product-content">
                                                        <div id="product_attributes" class="card-body collapse">
                                                     <?php
                                                    $product_attr_lists=product_attributes::get_product_attr_list(get_the_ID());
                                                    foreach ($product_attr_lists as $product_attr_list){
                                                    ?>
                                                    <h4>
                                                        <?= $product_attr_list['name'] ?>
                                                    </h4>
                                                        <?php
                                                        foreach ($product_attr_list['children'] as $children){
                                                        ?>
                                                    <div class="row">
                                                        <div class="right">
                                                            <?= $children['name'] ?>
                                                        </div>
                                                        <div class="left">
                                                            <?= $children['value'] ?>
                                                        </div>
                                                    </div>

<?php }}?>
                                                        </div>
                                                        </div>
                                                </section>

                                            </div>
                                        </div>

                                    </div>



    <?php
}
    wp_reset_postdata();
}?>

                                    <div id="rating-modal-warning" class="remodal rating-modal" data-remodal-id="rate-modal">
                                        <button data-remodal-action="close" class="remodal-close"></button>
                                        <h4>اخطار! می خواهید خارج شوید؟</h4>
                                        <p>متاسفانه هنوز علاقه مندی خودتان به این محصول را ثبت نکرده‌اید. لطفا در همین قسمت میزان ترجیح خود به این محصول را مشخص نمایید.
                                        </p>
                                        <br>
                                        <div id="product-rater-modal" class="block">
                                            <div data-start-value="<?= $av_rate['avrate']/5 ?>" data-productid="<?= get_the_ID(); ?>" class="starbox small  autoupdate"></div>
                                            <div class="rate-description">
                                                <p class="average-rate-description">میانگین امتیازات این محصول: <?= round($av_rate['avrate'],2); ?></p>
                                            </div>
                                        </div>
                                        <!--<button data-remodal-action="cancel" class="remodal-cancel">Cancel</button>-->
                                        <button id="product-set-rate-modal" data-remodal-action="confirm" class="remodal-confirm">ثبت کردم</button>
                                    </div>
