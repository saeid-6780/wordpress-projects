/**
 * Created by Saeid on 4/28/2018.
 */

    $.exitIntent('enable', {'sensitivity': 0});
    $(document).bind('exitintent',
        function () {
            $.ajax({
                type: 'POST',
                url: ajaxdata.ajax_url,
                dataType: 'json',
                data: {action: 'basket_save'},
                success: function(response){

                },
                error: function () {
                }
            });
        });

$('i.fa-close').click(function(){
    var productRow=$(this).parents('tr');
    var productId=productRow.attr('data-product-id');
    var totalPrice=$(this).parents('div.bs-docs-example').find('tr.total-price').find('td.price-val');
    var basketModal=$(this).parents('.remodal.basket-modal');
    var itemNum=$('.view-cart').find('span.total_item_num');
    basketModal.find('.full-allarm').fadeOut(150);

    //alert(totalPrice.text());
    productRow.fadeOut(150);
    $.ajax({
        type: 'POST',
        url: ajaxdata.ajax_url,
        dataType: 'json',
        data: {action: 'basket_product_remove',product_id:productId},
        success: function (response) {
            if (response['error']==1){
                basketModal.html('<button data-remodal-action="close" class="remodal-close"></button><h2>در سبد خرید شما کالایی وجود ندارد</h2>');
                $('.view-cart').fadeOut(100);
            }
            else {
                totalPrice.text(response['total_price']);
                itemNum.text(response['total_count'])
            }
        },
        error: function () {
        }
    });
});

if (typeof fullBasketError.error !== 'undefined' ){
    var allarmContent='<p>ظرفیت سبد خرید شما تکمیل شده است. لطفا پیش از قرار دادن آیتم دیگر در سبد، یکی از آیتم های قبلی را حذف کنید.</p>';
    $('.remodal.basket-modal').find('div.full-allarm').append(allarmContent);
    var bModal = $('[data-remodal-id=basket-modal]').remodal();
    bModal.open();
}

