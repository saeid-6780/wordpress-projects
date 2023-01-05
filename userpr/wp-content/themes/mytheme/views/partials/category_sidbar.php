<ul class="resp-tabs-list hor_1">

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
            ?>

            <li id="category-<?= $category->term_id; ?>" <?php if ($category->term_id==$queried_cat->term_id){echo 'class="resp-tab-active"';} ?> ><?= $category->name; ?></li>

            <?php
        }
    }
    ?>

</ul>