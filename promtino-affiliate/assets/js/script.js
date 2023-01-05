/**
 * Created by Saeid on 7/24/2020.
 */
jQuery(document).ready(function () {
    var receiveToProgress=0;
    jQuery(window).scroll(function() {
        var hT = jQuery('.progress-bar').offset().top,
            hH = jQuery('.progress-bar').outerHeight(),
            wH = jQuery(window).height(),
            wS = jQuery(this).scrollTop();
        if (wS > (hT+hH-wH)){
            if (receiveToProgress==0) {
                jQuery('.progress-bar').jQMeter({
                    goal: affiliate_data['click_limit'],
                    raised: affiliate_data['done_clicks'],
                    bgColor:"#F6A21E4D",
                    barColor:"#F6A21E"
                });
                receiveToProgress=1;
            }
        }
    });
});
jQuery(document).ready(function(){
    jQuery('.show-get-link-section').click(function () {
        jQuery('.affiliate-link-builder-section').slideToggle( "slow" );
    })
});

function copyToClipBoard(element){
    var $temp = jQuery("<input>");
    jQuery("body").append($temp);
    $temp.val(jQuery(element).val()).select();
    document.execCommand("copy");
    $temp.remove();
    var tooltip = document.getElementById("copy-tooltip");
    tooltip.innerHTML = "Link Copied";
}

function tooltipMakeDefaul() {
    var tooltip = document.getElementById("copy-tooltip");
    tooltip.innerHTML = "Copy to clipboard";
}

jQuery(document).ready(function(){
    jQuery('#campain-select').on('change',function () {
        //alert('asdf');
        var campain=jQuery(this).find(':selected').attr('value');

        var url=jQuery('#copyable-affiliate-link').val();
        var orgLink=location.protocol + '//' + location.host + location.pathname;
        var refferalID=getUrlParameterByName(affiliate_data['refferal_variable'],url);
        var campainQString='';
        if (campain!='')
            campainQString='&'+affiliate_data['campaign_variable']+'='+campain;
        var newURL=orgLink+'?'+affiliate_data['refferal_variable']+'='+refferalID+campainQString;
        jQuery('#copyable-affiliate-link').val(newURL);
    });
});

function getUrlParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, '\\$&');
    var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, ' '));
}