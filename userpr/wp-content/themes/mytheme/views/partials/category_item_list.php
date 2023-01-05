<?php
/*if (isset($_POST['product_id'])) {
    //product_basket::add($_POST['product_id'],$quantity);
    do_action('add_to_basket',$_POST['product_id']);
    var_dump($_SESSION['basket']);
}*/
?>



<form class="basket-form" action="" method="post">
<div class="resp-tabs-container hor_1 product-agileinfon-grid1">
    <span id="select-item-header" class="active total" style="display:block;" ><strong>اقلام</strong> </span>

    <?php
        $queried_cat=get_queried_object();
        $categorys=get_terms([
        'taxonomy' => 'product_category',
        'parent ' => 0,
            'orderby'=>'id',
            'order'=>'ASC',
        'hide_empty' => false
    ]);
    if (!empty($categorys)) {
    foreach ($categorys as $category) {
        //var_dump($category);
    ?>




    <div <?php if ($category->term_id==$queried_cat->term_id){echo 'class="resp-tab-content-active"';} ?> >
        <div id="select_list_style_tab" class="bs-example bs-example-tabs" role="tabpanel" data-example-id="togglable-tabs">

            <ul id="myTab1" class="nav1 nav1-tabs left-tab" role="tablist">
                <ul id="myTab" class="nav nav-tabs left-tab" role="tablist">
                    <li id="select_grid_style_tab" role="presentation" class="active"><a href="#home<?= $category->term_id ?>" id="home<?= $category->term_id ?>-tab" role="tab" data-toggle="tab" aria-controls="home" aria-expanded="true"><img src="<?= theme_asset::image('menu1.png')?>"></a></li>
                    <li id="select_list_style_tab" role="presentation"><a href="#profile<?= $category->term_id ?>" role="tab" id="profile<?= $category->term_id ?>-tab" data-toggle="tab" aria-controls="profile"><img src="<?= theme_asset::image('menu.png')?>"></a></li>
                </ul>
                <div id="myTabContent<?= $category->term_id ?>" class="tab-content">
                    <div role="tabpanel" class="tab-pane fade in active" id="home<?= $category->term_id ?>" aria-labelledby="home-tab">

                        <!--<div class="product-tab">-->

                            <?php

                            // The Query

                            $args = array(
                                'post_type' => 'product',
                                'tax_query' => array(
                                    array(
                                        'taxonomy' => $category->taxonomy,
                                        'field'    => 'slug',
                                        'terms'    => $category->slug,
                                    ),
                                ),
                            );
                            $the_query = new WP_Query( $args );

                            $section_handler=0;
                            //$margin_handler=0;

                            // The Loop
                            if ( $the_query->have_posts() ) {
                                while ( $the_query->have_posts() ) {
                                    if ($section_handler%6==0){
                                        echo '<div class="product-tab">';
                                    }else if($section_handler%3==0){
                                        echo '<div class="product-tab prod1">';
                                    }
                                    $section_handler++;
                                    /*$margin_handler++;*/
                                    $the_query->the_post();
                                    ?>

                                    <div class="col-md-4 product-tab-grid simpleCart_shelfItem product-info-list" id="product-<?= get_the_ID(); ?>" data-product-id="<?= get_the_ID() ?>">
                                        <div class="grid-arr">
                                            <div  class="grid-arrival">
                                                <figure id="product-<?= get_the_ID(); ?>-grid-img">
                                                    <a href="<?= get_the_permalink() ?>" class="new-gri" >
                                                        <?php
                                                        foreach (product_product::thumbnails(get_the_ID())as $thumbnail){
                                                            ?>
                                                            <div class="grid-img">
                                                                <img  src="<?= $thumbnail ?>" class="img-responsive" alt="">
                                                            </div>
                                                        <?php
                                                        }
                                                        ?>

                                                        <!--<div class="grid-img">
                                                            <img  src="images/28.jpg" class="img-responsive"  alt="">
                                                        </div>-->
                                                    </a>
                                                </figure>
                                            </div>
                                            <!--<div class="block">
                                                <div class="starbox small ghosting"> </div>
                                            </div>-->
                                            <div id="product-<?= get_the_ID(); ?>-grid-info" class="women">
                                                <?php
                                                $price=product_product::price(get_the_ID());
                                                ?>
                                                <h6><a href="<?= get_the_permalink() ?>"><?= get_the_title(); ?></a></h6>
                                                <?php
                                                $attr_mean_val=product_attributes::get_mean_amportant_val(get_the_ID());
                                                ?>
                                                <div class="progress">
                                                    <div class="progress-bar progress-bar-info" style="width: <?= $attr_mean_val['percent_mean']; ?>%;">
                                                        <p <?php
                                                        if ($attr_mean_val['main_mean']<2.5)echo 'style="font-size: 6pt;"';
                                                        ?>
                                                        class="prog-text text-shadow">میانگین امتیاز کاربران: <?= $attr_mean_val['main_mean']; ?></p>
                                                    </div>
                                                </div>
                                                <p ><del><?php if ($price['org_price']>$price['discount_price']) echo $price['discount_price'];?></del><em class="item_price"><?= $price['org_price'] ?> تومان</em></p>
                                                <a onclick="add_to_basket(this)" data-text="Add To Cart" class="my-cart-b item_add">افزودن به سبد خرید</a>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                    if ($section_handler%3==0){
                                        ?>
                            <div class="clearfix"></div>
                        </div>
                            <?php
                                    }
                                }
                                /* Restore original Post Data */
                                wp_reset_postdata();
                            } else {
                                // no posts found
                            }
                            if ($section_handler%3!=0){
                            ?>
                        <div class="clearfix"></div>
                    </div>
<?php }?>


                    </div>

                    <div role="tabpanel" class="tab-pane fade" id="profile<?= $category->term_id ?>" aria-labelledby="profile-tab">

                        <?php
                        $the_query = new WP_Query( $args );

                        $margin_handler2 =0;

                        // The Loop
                        if ( $the_query->have_posts() ) {
                        while ( $the_query->have_posts() ) {
                        $margin_handler2++;
                        $the_query->the_post();
                        ?>
                        <div class="product-tab1 <?php if ($margin_handler2%2==0) echo 'prod3';?> product-info-list" id="product-<?= get_the_ID(); ?>" data-product-id="<?= get_the_ID() ?>">
                            <div class="col-md-4 product-tab1-grid">
                                <div class="grid-arr">
                                    <div  class="grid-arrival">
                                        <figure id="product-<?= get_the_ID(); ?>-list-img">
                                            <a href="<?= get_the_permalink() ?>" class="new-gri" data-toggle="modal" data-target="#myModal1">
                                                <?php
                                                foreach (product_product::thumbnails(get_the_ID())as $thumbnail){
                                                    ?>

                                                    <div class="grid-img">
                                                        <img  src="<?= $thumbnail ?>" class="img-responsive" alt="">
                                                    </div>
                                                    <?php
                                                }
                                                ?>

                                            </a>
                                        </figure>
                                    </div>
                                </div>
                                <div id="id-product-list-rate_<?= get_the_ID(); ?>" class="grid-arr">
                                    <div class="user-rate-container">
                                        <h5>امتیازات کاربران دیجی کالا</h5>
                                        <?php
                                        $important_attrs=product_attributes::get_important_attr_val(get_the_ID());
                                        foreach ($important_attrs as $important_attr) {
                                            ?>
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-info" style="width: <?= $important_attr['value'] ?>%;">
                                                    <p class="prog-text text-shadow"><?php echo $important_attr['name'].': '.$important_attr['main_value']; ?></p>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                            ?>

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8 product-tab1-grid1 simpleCart_shelfItem">
                                <div class="block">
                                    <div class="starbox small ghosting"> </div>
                                </div>
                                <div id="id-product-list-info_<?= get_the_ID(); ?>" class="women">
                                    <h6><a href="<?= get_the_permalink() ?>"><?= get_the_title() ?></a></h6>
                                    <p><?= substr(get_the_excerpt(),0,1700)?> ...</p>
                                    <?php
                                    $price=product_product::price(get_the_ID());
                                    ?>
                                    <p ><del><?php if ($price['org_price']>$price['discount_price']) echo $price['discount_price'];?></del><em class="item_price"><?= $price['org_price'] ?> تومان</em></p>
                                    <a onclick="add_to_basket(this)" data-text="Add To Cart" class="my-cart-b item_add">افزودن به سبد خرید</a>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <?php

                        }
                    /* Restore original Post Data */
                    wp_reset_postdata();
                    } else {
                        // no posts found
                    }
                        ?>

                    </div>
                </div>
            </ul>

        </div>

    </div>

        <?php
    }
    }
    ?>


</div>
<input type="hidden" name="product_id">
</form>
<div class="clearfix fasdfasdfasdfas"></div>

<script>
    function add_to_basket(button) {
        var productID=$(button).parents('.product-info-list').attr('data-product-id')
        $('form.basket-form').find('input').val(productID);
        $('form.basket-form').submit();
    }
</script>