<?php
get_header();
get_template_part('views/partials/header');
get_template_part('views/partials/single_top');
//get_template_part('views/partials/product_description');
get_template_part('views/partials/product_comments');
get_template_part('views/partials/product_content_close');
get_footer();

?>













<!--<div  class="" id="reviews" a>
    <div class="descr">
        <h4>نظرات کاربران </h4>
        <form action="#" method="post">
            <div class="reviews-top reviews-bottom">
                <div class="reviews-left">
                    <img src="images/men3.jpg" alt=" " class="img-responsive">
                </div>
                <div class="reviews-right col-lg-12">
                    <div>
                        <ul>
                            <li><a href="#">سعید1</a></li>
                        </ul>
                        <p>لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است. چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است و برای شرایط فعلی تکنولوژی مورد نیاز و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد..</p>

                        <button onclick="" type="button" class="btn btn-info">پاسخ به دیدگاه</button>


                        <div class="btn-group likedislike" data-toggle="buttons">

                            <label class="btn btn-primary dislike">
                                <input type="radio" name="options" id="option2" autocomplete="off"><img src="<?/*=theme_asset::image('dislike.png')*/?>">
                                100
                            </label>

                            <label class="btn btn-primary  like">
                                <input type="radio" name="options" id="option1" autocomplete="off" checked> <img src="<?/*=theme_asset::image('like.png')*/?>">
                                200
                            </label>

                        </div>
                    </div>
                    <div class="nested">

                    </div>

                    <div class="nestedcomments">
                        <div class="reply-reviews">
                            <ul>
                                <li><a href="#">سعید2</a></li>
                            </ul>
                            <p>برای شرایط فعلی تکنولوژی مورد نیاز و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد. کتابهای زیادی در شصت و سه درصد گذشته، حال و آینده شناخت فراوان جامعه و متخصصان را می طلبد.</p>

                            <div class="btn-group likedislike" data-toggle="buttons">

                                <label class="btn btn-primary dislike">
                                    <input type="radio" name="options" id="option2" autocomplete="off"><img src="<?/*=theme_asset::image('dislike.png')*/?>">
                                    100
                                </label>

                                <label class="btn btn-primary  like">
                                    <input type="radio" name="options" id="option1" autocomplete="off" checked> <img src="<?/*=theme_asset::image('like.png')*/?>">
                                    200
                                </label>

                            </div>
                        </div>
                        <div class="reply-reviews">
                            <ul>
                                <li><a href="#">سعید3</a></li>
                            </ul>
                            <p>با نرم افزارها شناخت بیشتری را برای طراحان رایانه ای علی الخصوص طراحان خلاقی و فرهنگ پیشرو در زبان فارسی ایجاد کرد. در این صورت می توان امید داشت که تمام و دشواری موجود در ارائه راهکارها و شرایط سخت تایپ به پایان رسد.</p>

                            <div class="btn-group likedislike" data-toggle="buttons">

                                <label class="btn btn-primary dislike">
                                    <input type="radio" name="options" id="option2" autocomplete="off"><img src="<?/*=theme_asset::image('dislike.png')*/?>">
                                    100
                                </label>

                                <label class="btn btn-primary  like">
                                    <input type="radio" name="options" id="option1" autocomplete="off" checked> <img src="<?/*=theme_asset::image('like.png')*/?>">
                                    200
                                </label>

                            </div>
                        </div>
                    </div>



                </div>
                <div class="clearfix"></div>

            </div>

            <script>
                var comment_reply_button=$('.reviews-top .reviews-right .btn.btn-info');


                comment_reply_button.click(function () {
                    $(this).addClass('active');
                    var comment_reply_area=$(this).parents('.reviews-right').find('.nested');
                    var comment_reply_form=comment_reply_area.find('ul.filter-top');
                    var form_num=comment_reply_form.length;
                    if (form_num<1){
                        var comment_form='<div class="row features-selected" ></div><ul class="filter-top col-sm-4"><li onmouseleave="filter_hover_out(this)" onmouseenter="filter_hover_in(this)" class="col-sm-3"><img src="'+'<?/*=theme_asset::image('down-arrow.png')*/?>'+'"><span class="title">انتخاب ویژگی</span><div class="options"><ul><li onclick="add_features(this)" onmouseenter="feature_hover_in(this)" onmouseleave="feature_hover_out(this)" data-id="1" data-attr-id="1"><span class="square"></span>قیمت</li><li onclick="add_features(this)" onmouseleave="feature_hover_out(this)" onmouseenter="feature_hover_in(this)" data-id="2" data-attr-id="2"><span class="square"></span>باتری</li></ul></div></li></ul><label>نظر شما </label><textarea type="text" Name="Message" onfocus="this.value ='+ "''"+';" onblur="if (this.value =='+ "''"+') {this.value ='+ "'Message...'"+';}" required="">متن نظر...</textarea><div class="row"><div class="col-md-6 row-grid"><label>نام</label><input type="text" value="Name" Name="Name" onfocus="this.value ='+ "''"+';" onblur="if (this.value =='+" ''"+') {this.value ='+ "'Name'"+';}" required=""></div><div class="col-md-6 row-grid"><label>ایمیل</label><input type="email" value="Email" Name="Email" onfocus="this.value ='+ "''"+';" onblur="if (this.value =='+ "''"+') {this.value ='+ "'Email'"+';}" required=""></div><div class="clearfix"></div></div><input type="submit" value="ارسال">';
                        comment_reply_area.append(comment_form);
                    }

                })


            </script>


            <div class="reviews-bottom">
                <h4>افزودن نظر</h4>
                <p>ایمیل شما منتشر نخواهد شد</p>
                <p>مشخص کنید که نظر شما در مورد کدام یک از ویژگی های محصول است</p>

                <div class="row features-selected" >
                </div>


                <ul class="filter-top col-sm-4">

                    <li onmouseenter="filter_hover_in(this)" onmouseleave="filter_hover_out(this)" class="col-sm-3">
                        <img src="<?/*=theme_asset::image('down-arrow.png')*/?>">
                        <span class="title">
                انتخاب ویژگی
                    </span>
                        <div class="options ">
                            <ul>

                                <li onclick="add_features(this)" onmouseleave="feature_hover_out(this)" onmouseenter="feature_hover_in(this)" data-id="1" data-attr-id="1">
                                    <span class="square"></span>
                                    قیمت
                                </li>
                                <li onclick="add_features(this)" onmouseleave="feature_hover_out(this)" onmouseenter="feature_hover_in(this)" data-id="2" data-attr-id="2">
                                    <span class="square"></span>
                                    باتری
                                </li>

                            </ul>
                        </div>
                    </li>

                </ul>

                <label>نظر شما </label>
                <textarea type="text" Name="Message" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Message...';}" required="">متن نظر...</textarea>
                <div class="row">
                    <div class="col-md-6 row-grid">
                        <label>نام</label>
                        <input type="text" value="Name" Name="Name" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Name';}" required="">
                    </div>
                    <div class="col-md-6 row-grid">
                        <label>ایمیل</label>
                        <input type="email" value="Email" Name="Email" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Email';}" required="">
                    </div>
                    <div class="clearfix"></div>
                </div>
                <input type="submit" value="ارسال">

                <script>
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

                    function add_features(tag) {

                        var title = $(tag).parents('li').find('.title').text();
                        var value = $(tag).text();
                        var id = $(tag).attr('data-id');
                        var attrid = $(tag).attr('data-attr-id');

                        var filterSelected = $(tag).parents('.reviews-bottom').find('.features-selected');
                        var filterSselectedSpan=filterSelected.find('span[data-id='+id+']');
                        var len = filterSselectedSpan.length;
                        if (len > 0) {
                            filterSselectedSpan.remove();
                        }
                        else {
                            var span = '<span data-id="' + id + '" class="filter-selected-span">'  + value + '<i class="remove-filter" onclick="removeSelected(this)" ></i><input type="hidden" name="attr-'+attrid+'[]" value="'+id+'"></span>';
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

                </script>


            </div>
        </form>
    </div>
</div>
-->

