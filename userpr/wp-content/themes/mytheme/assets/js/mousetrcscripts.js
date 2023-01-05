/**
 * Created by Saeid on 4/13/2018.
 */


var comment_reply_button=$('.reviews-top .reviews-right .btn.btn-info');

/*comment_reply_button.click(function () {
    $(this).addClass('active');
    var comment_reply_area=$(this).parents('.reviews-right').find('.nested');
    var comment_reply_form=comment_reply_area.find('ul.filter-top');
    var form_num=comment_reply_form.length;
    if (form_num<1){
        var comment_form='<div class="row features-selected" ></div><ul class="filter-top col-sm-4"><li onmouseleave="filter_hover_out(this)" onmouseenter="filter_hover_in(this)" class="col-sm-3"><img src="'+'<?=theme_asset::image('down-arrow.png')?>'+'"><span class="title">انتخاب ویژگی</span><div class="options"><ul><li onclick="add_features(this)" onmouseenter="feature_hover_in(this)" onmouseleave="feature_hover_out(this)" data-id="1" data-attr-id="1"><span class="square"></span>قیمت</li><li onclick="add_features(this)" onmouseleave="feature_hover_out(this)" onmouseenter="feature_hover_in(this)" data-id="2" data-attr-id="2"><span class="square"></span>باتری</li></ul></div></li></ul><label>نظر شما </label><textarea type="text" Name="Message" onfocus="this.value ='+ "''"+';" onblur="if (this.value =='+ "''"+') {this.value ='+ "'Message...'"+';}" required="">متن نظر...</textarea><div class="row"><div class="col-md-6 row-grid"><label>نام</label><input type="text" value="Name" Name="Name" onfocus="this.value ='+ "''"+';" onblur="if (this.value =='+" ''"+') {this.value ='+ "'Name'"+';}" required=""></div><div class="col-md-6 row-grid"><label>ایمیل</label><input type="email" value="Email" Name="Email" onfocus="this.value ='+ "''"+';" onblur="if (this.value =='+ "''"+') {this.value ='+ "'Email'"+';}" required=""></div><div class="clearfix"></div></div><input type="submit" value="ارسال">';
        comment_reply_area.append(comment_form);
    }

});*/

//single product page set element range
console.log('mokhtasate window:');
console.log(screen.height);
console.log(screen.width);
//console.log($( document ).width());
//console.log($( document ).height());

/*
 console.log($( '.container .logo' ).position());
 console.log($( '.container .logo' ).offset());
 console.log($( '.container .logo' ).height());
 */

//.flex-control-nav.flex-control-thumbs

/*
// start main code for extract element size ranges

var importantTagSelector=[
    '.container .logo',
    '.header-right .site-guide.btn.btn-info:first',
    '.header-right .site-guide.btn.btn-info:last',
    '#product-introduction',
    '#add-to-basket a.my-cart-b.item_add',
    '#set-basket-quantity .entry.value-minus1',
    '#set-basket-quantity .entry.value-plus1',
    '.icon',
    '.icon1',
    '.icon2',
    '.icon3',
    '#home-tab',
    '#reviews-tab',
    '#product-content',
    '#product-attributes',
    '#myTabContent1',
    '#commentform'
];
/*,
'#product-slider .flex-viewport',
    '#product-slider ol.flex-control-nav.flex-control-thumbs'*/
/*var tagPixelRange={};
var tagPosition;
var tagHeight;
var tagWidth;

jQuery(document).ready(function() {

    var productRater=$('#product-rater').find('.starbox').find('.stars').find('.star_holder');
    productRater.addClass('asdfsdf');
    tagPosition=productRater.offset();
    tagWidth=productRater.width();
    tagHeight=productRater.height();
    tagPixelRange['product_rater']={'startx':tagPosition.left,'starty':tagPosition.top,'endx':tagPosition.left+tagWidth,'endy':tagPosition.top+tagHeight};

    var thumbnailGalleryImage=$('#product-slider').find('ol.flex-control-nav.flex-control-thumbs');
    tagPosition=thumbnailGalleryImage.offset();
    tagWidth=thumbnailGalleryImage.width();
    tagHeight=thumbnailGalleryImage.height();
    tagPixelRange['thumbnail_gallery_image']={'startx':tagPosition.left,'starty':tagPosition.top,'endx':tagPosition.left+tagWidth,'endy':tagPosition.top+tagHeight};

    var zoomableGalleryImage=$('#product-slider').find('.flex-viewport');
    tagPosition=zoomableGalleryImage.offset();
    tagWidth=zoomableGalleryImage.width();
    tagHeight=zoomableGalleryImage.height();
    tagPixelRange['zoomable_gallery_image']={'startx':tagPosition.left,'starty':tagPosition.top,'endx':tagPosition.left+tagWidth,'endy':tagPosition.top+tagHeight};


});

$('#comment-section').children('div.reviews-right.col-lg-12').each(function (){
    tagPosition=$(this).offset();

    tagWidth=$(this).width();
    tagHeight=$(this).height();
    tagPixelRange[$(this).attr('id')]={'startx':tagPosition.left,'starty':tagPosition.top,'endx':tagPosition.left+tagWidth,'endy':tagPosition.top+tagHeight};
});

$.each(importantTagSelector, function( index, value){
    //console.log(value);
    //console.log($(value).position());
    tagPosition=$(value).offset();

    tagWidth=$(value).width();
    tagHeight=$(value).height();
    tagPixelRange[value]={'startx':tagPosition.left,'starty':tagPosition.top,'endx':tagPosition.left+tagWidth,'endy':tagPosition.top+tagHeight};
});
console.log(tagPixelRange);

$(document).ready(function(){
    $.ajax({
        type: 'POST',
        url: ajaxdata.ajax_url,
        dataType: 'json',
        data: { action:'page_element_range', product_id: ajaxdata.product_id,page_type:ajaxdata.page_type,screen_width:screen.width,element_range:tagPixelRange },
        success: function(response) {
            /*console.log(response.like);
             alert();*/
            /*thisLabel.find('span').text(response.like);
            rateSection.find('.dislike').find('span').text(response.dislike);
        },
        error:function () {

        }
    })
});
//end main code for extract element size ranges
*/

$('#product-introduction-show-more').click(function () {
   $('#product-introduction').toggleClass('show-more');
    if ($('#product-introduction').hasClass('show-more')) {
        $(this).text('نمایش کمتر');
    }else {
        $(this).text('نمایش بیشتر');
    }
});

function filter_hover_in(tag) {
    $(tag).find('.options').slideDown(200);
}
function filter_hover_out(tag) {
    $(tag).find('.options').slideUp(200);
}

function feature_hover_in(tag) {
    $(tag).find('.square').addClass('square-hover');
}
function feature_hover_out(tag) {
    $(tag).find('.square').removeClass('square-hover');
}

var removeIcon=$('.filter-selected-span i');

function add_features(tag){
    var title = $(tag).parents('li').find('.title').text();
    var value = $(tag).text();
    var id = $(tag).attr('data-id');
    //var attrid = $(tag).attr('data-attr-id');
    var attrid = $(tag).text();

    var filterSelected = $(tag).parents('.reviews-bottom').find('.features-selected');
    var filterSselectedSpan=filterSelected.find('span[data-id='+id+']');
    var len = filterSselectedSpan.length;
    if (len > 0) {
        filterSselectedSpan.remove();
    }
    else {
        var span = '<span data-id="' + id + '" class="filter-selected-span">'  + value + '<i class="remove-filter" onclick="removeSelected(this)" ></i><input type="hidden" name="features'+'[]" value="' + id + '"></span>';
        filterSelected.append(span);
    }
    $('.square', tag).toggleClass('square-selected');
}

function removeSelected(tag) {
    var spanTag =$(tag).parents('span');

    var id =spanTag.attr('data-id');
    var thisLiSquare=$(tag).parents('.reviews-bottom').find('div.options');
    thisLiSquare.find(' li[data-id='+id+']').find('.square').removeClass('square-selected');
    spanTag.remove();
}

$('.reviews-right .likedislike .like').click(function () {
    var thisLabel=$(this);
    var rateSection=thisLabel.parents('.likedislike');
    var commentID=rateSection.attr('data-commentID');

    $.ajax({
        type: 'POST',
        url: ajaxdata.ajax_url,
        dataType: 'json',
        data: { action:'comment_like', comment_id: commentID },
        success: function(response) {
            /*console.log(response.like);
            alert();*/
            thisLabel.find('span').text(response.like);
            rateSection.find('.dislike').find('span').text(response.dislike);
        },
        error:function () {

        }
    })
});
$('.reviews-right .likedislike .dislike').click(function () {
    var thisLabel=$(this);
    var rateSection=thisLabel.parents('.likedislike');
    var commentID=rateSection.attr('data-commentID');

    $.ajax({
        type: 'POST',
        url: ajaxdata.ajax_url,
        dataType: 'json',
        data: { action:'comment_dislike', comment_id: commentID },
        success: function(response) {
            console.log(response);
            if (response.error==1) {
                alert('شما قبلا به این نظر امتیاز داده اید');
            }else {
                thisLabel.find('span').text(response.dislike);
                rateSection.find('.like').find('span').text(response.like);
            }
        },
        error:function () {
        }
    })
});


var currentUserSetRate=0;


$(function() {
    $('.starbox').each(function() {
        var starbox = $(this);
        starbox.starbox({
            average: starbox.attr('data-start-value'),
            changeable: starbox.hasClass('unchangeable') ? false : starbox.hasClass('clickonce') ? 'once' : true,
            ghosting: starbox.hasClass('ghosting'),
            autoUpdateAverage: starbox.hasClass('autoupdate'),
            buttons: starbox.hasClass('smooth') ? false : starbox.attr('data-button-count') || 5,
            stars: starbox.attr('data-star-count') || 5,

        }).bind('starbox-value-changed', function(event, value) {

            var rate=value*5;
            //alert(rate);
            $.ajax({
                type: 'POST',
                url: ajaxdata.ajax_url,
                dataType: 'json',
                data: { action:'product_rate', product_id: ajaxdata.product_id,rate:rate },
                success: function(response) {
                    console.log(response);
                    //starbox('setOption', 'average', response.your_rate);
                    var yourrate='<p class="your-rate-description">امتیاز شما: '+ response.your_rate*5 +'</p>';
                    $('.rate-description').find('p.your-rate-description').remove();
                    $('.rate-description').append(yourrate);

                    currentUserSetRate=1;
                },
                error:function () {
                }
            });
            if(starbox.hasClass('random')) {
                var val = Math.random();
                starbox.next().text(' '+val);
                return val;
            }
        })
    });
});

jQuery(document).ready(function(){
    jQuery('#product_description').on('show.bs.collapse', function () {
    });
});

//alert('asdfsadfasdf');
var seenedModalRate=0;
var inst = $('[data-remodal-id=rate-modal]').remodal();
var starttime = new Date();
var timeOnPage=6000;
$.exitIntent('enable',{ 'sensitivity': 50 });
if (ajaxdata.user_set_rate===null){
    $(document).bind('exitintent',
        function () {
            if (seenedModalRate == 0) {
                if ((new Date() - starttime) > timeOnPage) {
                    if (currentUserSetRate===0) {
                        inst.open();
                    }
                    //alert('jklkjl');
                }
            }
        });
}

$('.show-recommendation-item').click(function () {
    var showRecommendedProduct=0;
    if (ajaxdata.user_set_rate===null){
        if (seenedModalRate == 0) {
            if (currentUserSetRate===0){
                inst.open();
            }else showRecommendedProduct=1;
        }else showRecommendedProduct=1;
    }else showRecommendedProduct=1;
    if (showRecommendedProduct===1){
        window.location.href = $(this).attr('data-productlink');
    }
});

$('.product-nav-link').click(function () {
    var goToNavLink=0;
    if (ajaxdata.user_set_rate===null){
        if (seenedModalRate == 0) {
            if (currentUserSetRate===0){
                inst.open();
            }else goToNavLink=1;
        }else goToNavLink=1;
    }else goToNavLink=1;
    if (goToNavLink===1){
        window.location.href = $(this).attr('data-productnavlink');
    }
});

$(document).on('closed', '.remodal.rating-modal', function (e) {
    // Reason: 'confirmation', 'cancellation'
    seenedModalRate=1;
});

$(document).on('confirmation', '.remodal', function () {
    seenedModalRate=1;
});

if (typeof email_collected != 'undefined') {
    if (typeof email_collected.stat != 'undefined') {
        var EmailCModal = $('[data-remodal-id=email-collection-modal]').remodal();
        //alert(email_collected.close_num);
        if (email_collected.close_num >= 3) {
            EmailCModal.destroy();
        } else {
            var modalTimeout = window.setTimeout(showECModal, 2000);

            function showECModal() {
                EmailCModal.open();
            }
        }
    }
}

jQuery(document).ready(function(){
    jQuery(document).bind('gform_confirmation_loaded', function(event, formId){
        //alert('asdfd');
        if(formId == 1) {

            var emailCollectionModal=$('.email-collection-modal');
            emailCollectionModal.find('h3').fadeOut(100);
            emailCollectionModal.find('h5').fadeOut(100);
            emailCollectionModal.find('.email-collection-desc').fadeOut(100);
            emailCollectionModal.find('h4').fadeIn(300);
            emailCollectionModal.find('.remodal-confirm').fadeIn(300);
        }

    });
});


