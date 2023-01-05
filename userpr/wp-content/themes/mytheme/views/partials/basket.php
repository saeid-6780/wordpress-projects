<div class="remodal-bg">
    <button id="basket-view-button" class="view-cart" data-remodal-target="basket-modal">
        <i class="fa fa-cart-arrow-down"></i>
        <span class="badge badge-warning total_item_num"><?= general_utility::convert_eng_to_fa(product_basket::total_count()) ?></span>

    </button>

    <div class="remodal basket-modal" data-remodal-id="basket-modal" data-remodal-options="closeOnOutsideClick: true">
        <button id="basket-close-button" data-remodal-action="close" class="remodal-close"></button>
        <div id="basket-full-allarm" class="full-allarm"></div>
        <h2>سبد خرید</h2>
        <div class="bs-docs-example">
            <table id="basket-table" class="table table-hover">
                <thead id="basket-table-header">
                <tr>
                    <th>#</th>
                    <th>نام محصول</th>
                    <th>تعداد</th>
                    <th>قیمت</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $basket_items=product_basket::basket_items();
                $i=1;
                foreach ($basket_items as $key=>$basket_item) {
                    ?>
                    <tr id="basket-row-product-<?= $key ?>" data-product-id="<?= $key ?>">
                        <td><?= $i ?></td>
                        <td><?= $basket_item['product_name'] ?></td>
                        <td><?= $basket_item['quantity'] ?></td>
                        <td><?= general_utility::convert_eng_to_fa($basket_item['product_price']) ?><i class="fa fa-close"></i></td>
                    </tr>
                    <?php
                    $i++;
                }
                ?>
                <tr id="basket-total-price" class="total-price">
                    <td></td>
                    <td></td>
                    <td>جمع کل خرید</td>
                    <td class="price-val"><?= general_utility::convert_eng_to_fa((product_basket::basket_total_price())); ?></td>
                </tr>
                </tbody>
            </table>

        </div>

    </div>
</div>