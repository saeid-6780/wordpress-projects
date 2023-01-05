
<style>

    .attr-container {width: 100%;direction: rtl;}
    .filter-top{padding: 0;width: 100%;margin-bottom: 25px;list-style: none}
    .filter-top > li{height: 34px;;font-size: 9.5pt;background: #eee; ;border-radius: 1px;border: 1px solid #ccc;margin-left: 10px;padding-right: 4px;position: relative;
        cursor: pointer;}
    .filter-top span.title{margin: 0 auto;line-height: 28px;font-size: 11pt;margin-right: 5px;}
    .filter-top li img{float: left;margin-top: 8px;margin-left: -2px;}
    .filter-top li .options{width:100%;height: 130px;background: #fff;box-shadow: -4px 4px 3px rgba(0,0,0,0.2);border-right: 1px solid #AF1D0D;position: absolute;top: 32px;right: -1px;line-height: 19px;overflow-y: auto;overflow-x: hidden;display: none;z-index: 2;}
    .options ul li {font-size: 9.6pt;padding-right: 12px;cursor: pointer; margin-top:10px;display: block !important; }
    .options ul {padding-top: 10px;}
    .filter-top .options .square {width: 10px;height: 10px;display: inline-block;background-image: url('../../../assets/images/banner.jpg') ;vertical-align: middle;margin-left: 3px;}


    .square-hover{background: url(<?= theme_asset::image('spritefiltercontrols.png') ?>)no-repeat -58px -205px !important;}

    .square-selected {background: url(<?= theme_asset::image('spritefiltercontrols.png') ?>)no-repeat -58px -256px !important;}
    .remove-filter{width: 9px;height: 14px;background: url(<?= theme_asset::image('spritefiltercontrols.png') ?>)no-repeat -57px -382px ;display:inline-block;margin-left: 4px;margin-right: 4px;vertical-align: middle;cursor: pointer;}
    .filter-selected-span{background: #eeeeee;font-size: 9.6pt;margin: 0 10px;border-radius: 2px;box-shadow: 1px 1px 3px rgba(0,0,0,.15);float: right;border: 1px solid #AF1D0D;padding: 3px;}


    .features-selected {margin-bottom: 20px;margin-right: 1px;height: auto;display: inline-block;}


</style>

    <?php

    if ( is_array($data['potential_attribute']) && count($data['potential_attribute'])>0 ){
        ?>

    <div class="attr-container reviews-bottom">

        <div class="row features-selected" ></div>
        <ul class="filter-top">
            <li onmouseenter="filter_hover_in(this)" onmouseleave="filter_hover_out(this)" class="">
                <img src="<?= theme_asset::image('down-arrow.png'); ?>">
                <span class="title">انتخاب ویژگی</span>
                <div class="options ">
                    <ul>
                        <?php

                        foreach ($data['potential_attribute'] as $attribute ) { ?>
                            <li onclick="add_features(this)" onmouseleave="feature_hover_out(this)"
                                onmouseenter="feature_hover_in(this)" data-id="<?= $attribute['id']; ?>" data-attr-id="1"><span
                                    class="square"></span><?= $attribute['name']; ?>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>
            </li>
        </ul>

    </div>

        <?php wp_register_script('mousetrc',THEME_URL.'/assets/js/mousetrcscripts.js','jquery',false,false);
        wp_enqueue_script('mousetrc');
         }else {  ?>

    <?php }

    ?>
