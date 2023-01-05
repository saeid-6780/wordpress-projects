<div class="content">
    <div class="main-categories" id="select-cat">
        <div class="container ">
            <?php $categorys=get_terms([
                'taxonomy' => 'product_category',
                'hide_empty' => false,
                'orderby'=>'id',
                'order'=>'ASC'
            ]);
            $cat_image_file_names=['mobile.jpg','power-bank.jpg','phone-pouch-cover.png','handsfree.jpg'];
            if (!empty($categorys)){
                foreach ($categorys as $key=>$category){
                    ?>
                    <div class="col-sm-6 focus-grid">
                        <a href="<?= get_term_link($category) ?>">

                            <div class="focus-layout">
                                <h4 class="clrchg"><?= $category->name; ; ?></h4>
                                <img class="focus-img" src="<?= theme_asset::image($cat_image_file_names[$key]) ?>">
                            </div>
                        </a>
                    </div>
            <?php
                }
            }

            ?>

        <!--    <div class="col-sm-6 focus-grid">
                <a href="categories.html">
                    <div class="focus-layout">
                        <img class="focus-img" src="<?/*= theme_asset::image('mob2.png') */?>">
                        <h4 class="clrchg">لوازم جانبی موبایل</h4>
                    </div>
                </a>
            </div>
-->
            <div class="clearfix"></div>
        </div>
    </div>

</div>