/*!
 * Copyright (c) 2011-2012 Mike Green <myatus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
if(typeof myatu_bgm==="undefined"){var myatu_bgm={}}(function(b){b.extend(myatu_bgm,{showHideLayoutTable:function(){var a=(b('input[name="background_size"]:checked').val()==="full");myatu_bgm.showHide(".bg_fs_layout",a);myatu_bgm.showHide(".bg_extra_layout",!a,false);myatu_bgm.updateBackgroundOpacity((!a)?100:false);myatu_bgm.showHideBackgroundTransition()},showHideInfoExtra:function(){myatu_bgm.showHide(".info_tab_extra",b("#info_tab").is(":checked"))},showHidePinItBtnExtra:function(){myatu_bgm.showHide(".pin_it_btn_extra",b("#pin_it_btn").is(":checked"))},showHideTrackClicksExtra:function(){myatu_bgm.showHide("#bg_track_clicks_extra",b("#bg_track_clicks").is(":checked"))},showHideSelector:function(){var a=(b('input[name="change_freq"]:checked').val()==="custom");myatu_bgm.showHide(".image_sel_ad",a);myatu_bgm.showHide("#image_sel_random",a);if(!a){b("#image_sel_random").prop("checked",true)}},showHideRememberLastImage:function(){var a=(b('input[name="change_freq"]:checked').val()==="custom");myatu_bgm.showHide(".change_freq_lc",a)},showHideBackgroundTransition:function(){var d=(b('input[name="background_size"]:checked').val()==="full"),a=(b('input[name="change_freq"]:checked').val()==="custom");myatu_bgm.showHide(".bg_transition",(d&&a));myatu_bgm.showHideSelector();myatu_bgm.showHideRememberLastImage()},updatePreviewColor:function(){var a=b("#background_color").val();if(a&&a.charAt(0)==="#"){if(a.length>1){b("#bg_preview_bg_color").css("background-color",a);b("#clear_color").show()}else{b("#bg_preview_bg_color").css("background-color","");b("#clear_color").hide()}}},updateOpacity:function(g,i,h){var j=b(i).val(),a="100";if(g){j=g}if(j<10){a=".0"+j}else{if(j<100){a="."+j}}b(h).css("opacity",a)},updateBackgroundOpacity:function(a){myatu_bgm.updateOpacity(a,"#background_opacity","#bg_preview")},updateOverlayOpacity:function(){myatu_bgm.updateOpacity(false,"#overlay_opacity","#bg_preview_overlay")},updatePreviewOverlay:function(){var a=myatu_bgm.GetAjaxData("overlay_data",b("#active_overlay option:selected").val());if(a){b("#bg_preview_overlay").css("background","url('"+a+"') repeat fixed top left transparent")}else{b("#bg_preview_overlay").css("background","")}},updatePreviewGallery:function(){var d=b("#active_gallery option:selected").val(),a=myatu_bgm.GetAjaxData("select_image",{active_gallery:d,prev_img:"none"});if(a){b("#bg_preview").css("background-image","url('"+a.thumb+"')")}else{b("#bg_preview").css("background-image","")}},updatePreviewLayout:function(){var g=b('input[name="background_size"]:checked').val(),j=b('input[name="background_position"]:checked').val().replace("-"," "),h=b('input[name="background_repeat"]:checked').val(),i=b("#background_stretch_horizontal").is(":checked"),a=b("#background_stretch_vertical").is(":checked");if(g==="full"){b("#bg_preview").css({"background-size":"100% auto","background-repeat":"no-repeat","background-position":"50% 50%"})}else{b("#bg_preview").css({"background-size":((i)?"100%":"50px")+" "+((a)?"100%":"50px"),"background-repeat":h,"background-position":j})}},clearColor:function(){b("#background_color").val("#");myatu_bgm.updatePreviewColor();return false}});b(document).ready(function(h){h("#color_picker").farbtastic(function(c){h("#background_color").val(c);myatu_bgm.updatePreviewColor()});h.farbtastic("#color_picker").setColor(h("#background_color").val());h("#background_color").focusin(function(){h("#color_picker").show()}).focusout(function(){h("#color_picker").hide();myatu_bgm.updatePreviewColor()}).keyup(function(){if(this.value.charAt(0)!=="#"){this.value="#"+this.value}h.farbtastic("#color_picker").setColor(h("#background_color").val());myatu_bgm.updatePreviewColor()});h("#opacity_picker").slider({value:h("#background_opacity").val(),range:"min",min:1,max:100,slide:function(d,c){h("#background_opacity").val(c.value);h("#opacity_picker_val").text(c.value+"%");myatu_bgm.updateBackgroundOpacity()}});h("#ov_opacity_picker").slider({value:h("#overlay_opacity").val(),range:"min",min:1,max:100,slide:function(d,c){h("#overlay_opacity").val(c.value);h("#ov_opacity_picker_val").text(c.value+"%");myatu_bgm.updateOverlayOpacity()}});h("#transition_speed_picker").slider({value:15100-h("#transition_speed").val(),min:100,max:15000,step:100,slide:function(d,c){h("#transition_speed").val(15100-c.value);myatu_bgm.updateOpacity()}});myatu_bgm.updatePreviewColor();myatu_bgm.updateBackgroundOpacity();myatu_bgm.updateOverlayOpacity();h("#info_tab").change(myatu_bgm.showHideInfoExtra);myatu_bgm.showHideInfoExtra();h("#pin_it_btn").change(myatu_bgm.showHidePinItBtnExtra);myatu_bgm.showHidePinItBtnExtra();h("#bg_track_clicks").change(myatu_bgm.showHideTrackClicksExtra);myatu_bgm.showHideTrackClicksExtra();h('input[name="background_size"]').change(myatu_bgm.showHideLayoutTable);myatu_bgm.showHideLayoutTable();h("#active_gallery").change(myatu_bgm.updatePreviewGallery);myatu_bgm.updatePreviewGallery();h("#active_overlay").change(myatu_bgm.updatePreviewOverlay);myatu_bgm.updatePreviewOverlay();h('input[name="background_size"]').change(myatu_bgm.updatePreviewLayout);myatu_bgm.updatePreviewLayout();h('input[name="background_position"]').change(myatu_bgm.updatePreviewLayout);h('input[name="background_repeat"]').change(myatu_bgm.updatePreviewLayout);h("#background_stretch_horizontal").change(myatu_bgm.updatePreviewLayout);h("#background_stretch_vertical").change(myatu_bgm.updatePreviewLayout);h('input[name="change_freq"]').change(myatu_bgm.showHideBackgroundTransition);h("#clear_color").click(myatu_bgm.clearColor);h("#footer_debug_link").click(function(){h("#footer_debug").toggle();return false});var g=35,f=h("#bg_preview_div"),a=f.offset().top;h(window).scroll(function(){var d=h(window).scrollTop()-h("#screen-meta").height(),c=h("#submit").offset().top-h("#screen-meta").height()-g;if(d>a-g){if(d+f.height()+10<c){f.css({"box-shadow":"5px 5px 5px #aaa",border:"1px solid #aaa"});f.stop().animate({top:(d-a+g)+"px"},"slow")}}else{f.css({"box-shadow":"",border:""});f.stop().animate({top:0})}})})}(jQuery));