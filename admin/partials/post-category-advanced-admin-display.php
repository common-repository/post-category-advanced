<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://all-wp.com
 * @since      1.0.0
 *
 * @package    Post_Category_Advanced
 * @subpackage Post_Category_Advanced/admin/partials
*/

$ver = PCADV_VERSION;


// plugin variables
$pca_parent_cat_opt = get_option( 'pca_parent_cat_opt' );
$pca_all_posts_opt = get_option( 'pca_all_posts_opt' );
$pca_cat_tags_opt = get_option( 'pca_cat_tags_opt' );
$pca_exclude_posts_opt = get_option( 'pca_exclude_posts_opt' );
$pca_delete_on_uninstall = get_option( 'pca_delete_on_uninstall' );
$pca_opt_n = 1;
if ( get_option( 'pca_opt_n' ) ){
    $pca_opt_n = get_option( 'pca_opt_n' );
}
// var_dump($pca_cat_tags_opt);

$other_attributes = array( 'tabindex' => '1' );

// wp post categories
$categories = get_categories( array("hide_empty" => 0, 'orderby' => 'name') );
$cat_array = array();
foreach ( $categories as $category ) {
    array_push($cat_array, $category->name);
}
// wp post tags
$tags = get_tags( array('orderby' => 'name') );
$tag_array = array();
foreach ( $tags as $tag ) {
    array_push($tag_array, $tag->name);
}

// ADD ROW action
if ( isset($_POST['pca_add']) ){
    if ( isset($_POST['hidden_number']) ){
        $n = intval($_POST['hidden_number']);
    }
    update_option( 'pca_opt_n', $n );

    header("refresh:0");
}

// UPDATE db on save button click
if (isset($_POST['save_settings'])){
    // update parent category option by checbox value
    update_option('pca_parent_cat_opt', intval($_POST['pca_parent_cat_opt']));

    // update all existent posts option by checbox value
    update_option('pca_all_posts_opt', intval($_POST['pca_all_posts_opt']));

    // update list of posts to exclude option by input field values
    if(isset($_POST['pca_exclude_posts_opt'])){
        update_option('pca_exclude_posts_opt', wp_kses($_POST['pca_exclude_posts_opt'], 'strip'));
    }

    // Delete plugin data on unistall option
    update_option('pca_delete_on_uninstall', intval($_POST['pca_delete_on_uninstall']));

    // check how many, save $n
    $options = array();
    $box_n = $pca_opt_n;
    if ( isset($_POST['hidden_number']) ){
        $box_n = intval($_POST['hidden_number']);
    }
    update_option( 'pca_opt_n', $box_n );

    for ($i=1; $i<=$box_n; $i++) {
        // Update tags for category setting
        // ..both fields are filled
        $h_cat = 'hidden_category_'.strval($i);
        $h_tags = 'hidden_tags_'.strval($i);
        if(
            isset($_POST[$h_cat]) && $_POST[$h_cat] != "" &&
            isset($_POST[$h_tags]) && $_POST[$h_tags] != ""
        ){
            $obj_n = '{ "c": "' . wp_kses($_POST[$h_cat], 'strip') . '", "t": "' . wp_kses($_POST[$h_tags], 'strip') . '" }';
            array_push($options, $obj_n); 
        }
        // ..only the category is filled
        if(
            isset($_POST[$h_cat]) && $_POST[$h_cat] != "" &&
            (!isset($_POST[$h_tags]) || $_POST[$h_tags] == "") &&
            json_decode($pca_cat_tags_opt[$i-1])->t != ""
        ){
            $obj_n = '{ "c": "' . wp_kses($_POST[$h_cat], 'strip') . '", "t": "' . wp_kses(json_decode($pca_cat_tags_opt[$i-1])->t, 'post') . '" }';
            array_push($options, $obj_n);
        }
        // ..only the tags are filled
        if(
            (!isset($_POST[$h_cat]) || $_POST[$h_cat] == "") &&
            isset($_POST[$h_tags]) && $_POST[$h_tags] != "" &&
            json_decode($pca_cat_tags_opt[$i-1])->c != ""
        ){
            $obj_n = '{ "c": "' . wp_kses(json_decode($pca_cat_tags_opt[$i-1])->c, 'post') . '", "t": "' . wp_kses($_POST[$h_tags], 'strip') . '" }';
            array_push($options, $obj_n);
        }
    }

    if( isset($_POST[$h_cat]) != "" || isset($_POST[$h_tags]) != "" ){
        update_option( 'pca_cat_tags_opt', $options );
    }

    header("refresh:0");
};

?>

<div class="pca">
    <div class="pca-header">
        <h1 class="pca-h1">Post Category Advanced</h1>
        <div style="display:flex;align-items:center;margin-left:auto;max-width:150px;padding:0 20px;border-right:1px solid #ddd">
            <a href="https://all-wp.com" target="_blank">
                <strong style="color:#774ee0;font-size:16px;line-height:20px">Need professional WordPress support?</strong>
            </a>
        </div>
        <div style="display:flex;align-items:center;padding:0 20px">
            <span style="margin-right:15px">Developed by</span>
            <a href="https://all-wp.com" target="_blank">
                <img src="<?php echo plugin_dir_url( dirname( dirname( __FILE__ ) ) ).'images/all-wp-logo.png' ?>" width="40px" height="40px" alt="all-wp.com" />
            </a>
        </div>
    </div>
    <div style="color:#ccc;font-size:12px;font-weight:600;margin-bottom:20px;margin-top:-6px"><?php echo esc_html($ver) ?></div>
    <?php settings_errors(); ?>
    <form method="POST" class="pca-form" style="padding-right:20px">
        <?php submit_button( __( '', 'textdomain' ), 'primary', 'save_settings', true, $other_attributes ); ?>
        <h2 class="pca-h2"><?php esc_html_e( 'Options', 'plugin-text-domain' ); ?></h2>
        <div class="pca-container" style="margin-bottom:30px">
            <div class="pca-setting-row" style="margin:0.5em 0 1em;padding:15px 20px 10px">
                <div style="width:40%;flex-shrink:0">
                    <strong><?php esc_html_e( 'Delete plugin data on unistall', 'plugin-text-domain' ); ?></strong>
                </div>
                <label>
                    <input type='checkbox' name='pca_delete_on_uninstall' <?php checked( $pca_delete_on_uninstall, 1 ); ?> value='1'>
                    <span class="pca-desc"><?php esc_html_e( 'Delete all rules created and plugin data from the database when uninstalling the plugin.', 'plugin-text-domain' ); ?></span>
                    <span class="pca-desc"><?php esc_html_e( '(Your categories and tags will NOT be deleted)', 'plugin-text-domain' ); ?></span>
                </label>
            </div>
        </div>
        <h2 class="pca-h2"><?php esc_html_e( 'Parent category by Category', 'plugin-text-domain' ); ?></h2>
        <div class="pca-container" style="margin-bottom:30px">
            <div class="pca-setting-row" style="margin:0.5em 0 1em;padding:15px 20px 10px">
                <div style="width:40%;flex-shrink:0">
                    <strong><?php esc_html_e( 'Auto parent-category', 'plugin-text-domain' ); ?></strong>
                </div>
                <label>
                    <input type='checkbox' name='pca_parent_cat_opt' <?php checked( $pca_parent_cat_opt, 1 ); ?> value='1'>
                    <span class="pca-desc"><?php esc_html_e( 'Automatically select parent category if at least one of its sub-categories is selected.', 'plugin-text-domain' ); ?></span>
                </label>
            </div>
        </div>
        <h2><?php esc_html_e( 'Tags by Category', 'plugin-text-domain' ); ?></h2>
        <div class="pca-container">
            <div class="pca-setting-box" style="border-top:none">
                <p style="margin-bottom:25px"><?php esc_html_e( 'When saving a post, automatically assign tags to it based on the categories selected.', 'plugin-text-domain' ); ?><br />
                <span style="color:#eb4962"><?php esc_html_e( 'You\'ll still be able to manually ADD further tags but not to REMOVE the ones set in the rules below, unless you modify the rule.', 'plugin-text-domain' ); ?></span></p>
                <div class="pca-setting-row" style="margin:0.5em 0 1em">
                    <div style="width:40%;flex-shrink:0">
                        <strong><?php esc_html_e( 'Apply to existent posts', 'plugin-text-domain' ); ?></strong>
                    </div>
                    <label>
                        <input type='checkbox' name='pca_all_posts_opt' <?php checked( $pca_all_posts_opt, 1 ); ?> value='1'>
                        <span class="pca-desc"><?php esc_html_e( 'Also apply the rules below to ALL existent posts, based on the categories currently assigned.', 'plugin-text-domain' ); ?></span>
                    </label>
                </div>
                <div class="pca-setting-row" style="margin:0.5em 0 1em">
                    <div style="margin:0.5em 0 1em;width:40%;flex-shrink:0">
                        <strong><?php esc_html_e( 'Exclude posts', 'plugin-text-domain' ); ?></strong>
                    </div>
                    <label>
                        <input id="pca-exclude-posts-opt" type='text' name='pca_exclude_posts_opt' value="<?php echo sanitize_text_field($pca_exclude_posts_opt) ?>" />
                        <span style="display:block;padding-top:10px" class="pca-desc"><?php esc_html_e( 'Comma separated list of post IDs to exclude from ALL Rules', 'plugin-text-domain' ); ?></span>
                    </label>
                </div>
            </div>
            
            <?php for ($i = 1; $i <= $pca_opt_n; $i++) { ?>

                <?php 
                // REMOVE ROW action
                $r_n = 'pca_remove_'.strval($i);
                if ( isset($_POST[$r_n]) ){
                    $data_now = $pca_cat_tags_opt;
                    unset($data_now[$i-1]);

                    update_option( 'pca_cat_tags_opt', map_deep( $data_now, 'sanitize_text_field' ) );

                    if ( isset($_POST['hidden_number']) ){
                        $n = intval($_POST['hidden_number']);
                    }
                    update_option( 'pca_opt_n', $n );

                    header("refresh:0");
                }
                ?>
            
                <div class="pca-setting-box pca-setting-box-rule">
                    <span class="pca-rule">Rule <?php echo esc_html(strval($i)) ?></span>
                    <div class="pca-setting-row">
                        <div style="width:20%;flex-shrink:0">
                            <strong class="pca-label">Category:</strong>
                        </div>
                        <select name="pca_sel_cat-<?php echo esc_html(strval($i)) ?>" id="pca-sel-cat-<?php echo esc_html(strval($i)) ?>">
                            <option value="">--Choose a category--</option>
                            <?php
                            foreach ( $cat_array as $cat ) {
                                if(json_decode($pca_cat_tags_opt[$i-1])->c == $cat){
                                    echo '<option selected="selected" value="'.esc_html($cat).'">'.esc_html($cat).'</option>';
                                }else{
                                    echo '<option value="'.esc_html($cat).'">'.esc_html($cat).'</option>';
                                }
                            }
                            ?>
                        </select>
                        <div id="pca-notif-alert-<?php echo esc_html(strval($i)) ?>" class="pca-notif-alert" style="opacity:0">category already in use!</div>
                    </div>
                    <div class="pca-setting-row" style="align-items:flex-start;margin-bottom:0px;">
                        <div style="width:20%;flex-shrink:0">
                            <strong class="pca-label">Tags:</strong>
                        </div>
                        <div>
                            <select name="pca_sel_tag-<?php echo esc_html(strval($i)) ?>" id="pca-sel-tag-<?php echo esc_html(strval($i)) ?>">
                                <option value="">--Assign tags--</option>
                                <?php
                                foreach ( $tag_array as $tag ) {
                                    echo '<option value="'.esc_html($tag).'">'.esc_html($tag).'</option>';
                                }
                                ?>
                            </select>
                            <div id="tags-list-<?php echo esc_html(strval($i)) ?>" style="margin-top:10px">
                                <?php
                                if($pca_cat_tags_opt){
                                    $data_tags = explode(",", json_decode($pca_cat_tags_opt[$i-1])->t);
                                    if(json_decode($pca_cat_tags_opt[$i-1])->t != "" && count($data_tags) > 0){
                                        foreach ( $data_tags as $d_tag ) {
                                            //Clean up multiple dashes or whitespaces
                                            $slugified = preg_replace("/[\s-]+/", " ", $d_tag);
                                            //Convert whitespaces and underscore to dash
                                            $slugified = preg_replace("/[\s_]/", "-", $slugified);
                                            echo '<div id="pca-tag-'.esc_html($slugified).'" class="pca-tag"><span class="pca-close" onclick="removePcaTag(\''.esc_html($d_tag).'\', '.esc_html(strval($i)).');">+</span>'.esc_html($d_tag).'</div>';
                                        }
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <button class="pca-btn pca_remove button-cancel" name="pca_remove_<?php echo esc_html(strval($i)) ?>">Remove rule</button>
                    <input id="hidden_category_<?php echo esc_html(strval($i)) ?>" name="hidden_category_<?php echo esc_html(strval($i)) ?>" type="text" class="pca-hidden-field" value="<?php echo $pca_cat_tags_opt[$i-1] ? sanitize_text_field(json_decode($pca_cat_tags_opt[$i-1])->c) : "" ?>" />
                    <input id="hidden_tags_<?php echo esc_html(strval($i)) ?>" name="hidden_tags_<?php echo esc_html(strval($i)) ?>" type="text" class="pca-hidden-field" value="<?php echo $pca_cat_tags_opt[$i-1] ? sanitize_text_field(json_decode($pca_cat_tags_opt[$i-1])->t) : "" ?>" />
                </div>

            <?php } ?>

            <button id="pca_add" class="pca-btn button-secondary" name="pca_add">+ Add rule</button>
        </div>
        <input id="hidden_number" name="hidden_number" type="number" value="<?php echo esc_html(strval($pca_opt_n)) ?>" class="pca-hidden-field" />
        <?php submit_button( __( '', 'textdomain' ), 'primary', 'save_settings', true, $other_attributes ); ?>
    </form>
</div>

<script>
    function incrementValue(){
        var value = parseInt(document.getElementById('hidden_number').value, 10);
        value = isNaN(value) ? 0 : value;
        value++;
        document.getElementById('hidden_number').value = value;
    }
    function decrementValue(){
        var value = parseInt(document.getElementById('hidden_number').value, 10);
        value = isNaN(value) ? 0 : value;
        value--;
        document.getElementById('hidden_number').value = value;
    }
    function removePcaTag(t, i){
        let slugified = t.replace(/\s+/g, '-');
        //remove from variable
        let hiddenFieldCont = jQuery('#hidden_tags_'+i).val();
        if (hiddenFieldCont.includes(", "+t.trim())){
            hiddenFieldCont = hiddenFieldCont.replace(', '+t.trim(), "");
        }else if(hiddenFieldCont.includes(t.trim()+", ")){
            hiddenFieldCont = hiddenFieldCont.replace(t.trim()+', ', "");
        }
        
        //hiddenFieldCont = hiddenFieldCont.replace(t, "").trim();
        jQuery('#hidden_tags_'+i).val(hiddenFieldCont);
        //remove from DOM
        jQuery("#pca-tag-"+slugified).remove();
    }
    jQuery(document).ready(function($){
        let n = $('#hidden_number').val();
        let categories_sel = [];

        for (let i=1; i<=n; i++){
            categories_sel.push($('#hidden_category_'+i).val());
            let str_tag = $('#hidden_tags_'+i).val();

            $('#pca-sel-cat-'+i).change(function(){
                let before_val = $('#hidden_category_'+i).val();
                // i due if includes sotto sono per evitare che la stessa categoria venga selezionata due volte
                if($(this).val() && categories_sel.includes($(this).val()) === false){
                    //remove at index
                    categories_sel.splice(i-1, 1);
                    //add at index
                    categories_sel.splice(i-1, 0, $(this).val().trim());

                    $('#hidden_category_'+i).val($(this).val().trim());

                    // fix HTML in DOM not selecting ??
                    // $('#pca-sel-cat-'+i+' option').removeAttr('selected');
                    // $('#pca-sel-cat-'+i+'[value="'+$(this).val()+'"]').attr('selected','selected');
                }else if(categories_sel.includes($(this).val())){
                    // show alert
                    $('#pca-notif-alert-'+i).css('opacity', '1');
                    setTimeout(function(){
                        $('#pca-notif-alert-'+i).css('opacity', '0');
                    },2000);

                    // fix HTML in DOM not selecting ??
                    $('#pca-sel-cat-'+i+' option').removeAttr('selected');
                    $('#pca-sel-cat-'+i+' option[value="'+before_val+'"]').prop("selected", true);
                    // $('#pca-sel-cat-'+i+' option:first').prop("selected", true);
                    // $('#pca-sel-cat-'+i+' option:first').attr('selected','selected');
                }
            });
            $('#pca-sel-tag-'+i).change(function(){
                let str_tag_now = $('#hidden_tags_'+i).val();
                if($(this).val() && str_tag_now.includes($(this).val()) === false){
                    let thisTag = $(this).val().trim();
                    if (str_tag_now == ""){
                        str_tag_now += thisTag;
                    }else{
                        str_tag_now += ", "+thisTag;
                    }
                    $('#hidden_tags_'+i).val(str_tag_now);
                    let slugified = thisTag.replace(/\s+/g, '-');
                    $('#tags-list-'+i).append('<div id="pca-tag-'+slugified+'" class="pca-tag"><span class="pca-close" onclick="removePcaTag(\''+thisTag+'\', '+i+');">+</span>'+thisTag+'</div>');
                }
            });
        }

        $('#pca_add').on('click', function(e){
            incrementValue();
            let n = $('#hidden_number').val();
        });
        $('.pca_remove').on('click', function(e){
            decrementValue();
            let n = $('#hidden_number').val();
        });

    });
</script>
