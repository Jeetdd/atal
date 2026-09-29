<div id="tab-15">
    <div class="row">
    <div class="wpm-col-6">
        <table class="table table-responsive wpm_6310_admin_table">
        <tr>
          <td>
            <b>Activate Pagination</b><br />   
            <span class="wpm-6310-pagination-msg"></span>    
            <span class="wpm-6310-pro">(Pro) <div class='wpm-6310-pro-text'>This feature is available on the pro version only.</div></span>     
          </td>
          <td>
            <label class="switch" for="activation_pagination">
              <input type="checkbox" name="activate_pagination" id="activation_pagination" value="1" <?php echo (isset($allSlider[400]) && $allSlider[400]) ? 'checked' : '' ?>>
              <span class="slider round"></span>
            </label>
          </td>
        </tr>
        <tr class="pagination_act_field">
            <td>
              <b>Member Per Page</b>
              <span class="wpm-6310-pro">(Pro) <div class='wpm-6310-pro-text'>This feature is available on the pro version only.</div></span>
            </td>
            <td>
             <input type="number" min="1" name="pagination_per_page" value="<?php echo (isset($allSlider[401]) && $allSlider[401] !== '') ? $allSlider[401]:6;?>"
                class="wpm-form-input" step="1"  />
              </select>
            </td>
        </tr>
        <tr class="pagination_act_field">
            <td>
              <b>Desktop Font Size</b>
              <span class="wpm-6310-pro">(Pro) <div class='wpm-6310-pro-text'>This feature is available on the pro version only.</div></span>
            </td>
            <td>
            <input type="number" min="0" name="pagination_font_size" value="<?php echo (isset($allSlider[391]) && $allSlider[391] !== '') ? $allSlider[391]:16;?>"
                class="wpm-form-input" step="1" id="wpm_pagination_font_size" />
            </td>
        </tr>
        <tr class="pagination_act_field">
            <td>
              <b>Mobile Font Size</b>
              <span class="wpm-6310-pro">(Pro) <div class='wpm-6310-pro-text'>This feature is available on the pro version only.</div></span>
            </td>
            <td>
            <input type="number" min="0" name="mobile_pagination_font_size" value="<?php echo isset($allSlider[392]) ? $allSlider[392] : 12 ?>"
                class="wpm-form-input" step="1" />
            </td>
        </tr>       
        <tr class="pagination_act_field">
            <td>
              <b>Padding Top / Bottom</b>
              <span class="wpm-6310-pro">(Pro) <div class='wpm-6310-pro-text'>This feature is available on the pro version only.</div></span>
            </td>
            <td>
            <input name="pagination_padding_top" id="wpm_pagination_padding_top" type="number" min="0"
                value="<?php echo (isset($allSlider[393]) && $allSlider[393] !== '') ? $allSlider[393]:10;?>" class="wpm-form-input" />
            </td>
        </tr>
        <tr class="pagination_act_field">
            <td>
              <b>Padding Left / Right</b>
              <span class="wpm-6310-pro">(Pro) <div class='wpm-6310-pro-text'>This feature is available on the pro version only.</div></span>
            </td>
            <td>
            <input name="pagination_padding_left" id="wpm_pagination_padding_left" type="number" min="0"
                value="<?php echo (isset($allSlider[394]) && $allSlider[394] !== '') ? $allSlider[394]:15;?>" class="wpm-form-input" />
            </td>
        </tr>
        
        </table>
    </div>
    <div class="wpm-col-6">
        <table class="table table-responsive wpm_6310_admin_table">
        <tr class="pagination_act_field">
            <td>
              <b>Button Distance </b>
              <span class="wpm-6310-pro">(Pro) <div class='wpm-6310-pro-text'>This feature is available on the pro version only.</div></span>
            </td>
            <td>
            <input name="pagination_button_distance" id="wpm_pagination_button_distance" type="number" min="0"
                value="<?php echo (isset($allSlider[395]) && $allSlider[395] !== '') ? $allSlider[395]:1;?>" class="wpm-form-input" />
            </td>
        </tr>
        <tr class="pagination_act_field">
            <td>
              <b>Font Color</b>
              <span class="wpm-6310-pro">(Pro) <div class='wpm-6310-pro-text'>This feature is available on the pro version only.</div></span>
            </td>
            <td>
            <input type="text" name="pagination_font_color" id="wpm_pagination_font_color"
                class="wpm-form-input wpm_6310_color_picker" data-format="rgb"
                value="<?php echo (isset($allSlider[396]) && $allSlider[396] !== '') ? $allSlider[396]:"rgb(255, 255, 255)";?>">
            </td>
        </tr>
        <tr class="pagination_act_field">
            <td>
              <b>Active Font  Color</b>
              <span class="wpm-6310-pro">(Pro) <div class='wpm-6310-pro-text'>This feature is available on the pro version only.</div></span>
            </td>
            <td>
            <input type="text" name="pagination_font_active_color" id="wpm_pagination_font_active_color"
                class="wpm-form-input wpm_6310_color_picker" data-format="rgb"
                value="<?php echo (isset($allSlider[397]) && $allSlider[397] !== '') ? $allSlider[397]:"rgb(255, 255, 255)";?>">
            </td>
        </tr>
        <tr class="pagination_act_field">
            <td>
              <b>Background Color</b>
              <span class="wpm-6310-pro">(Pro) <div class='wpm-6310-pro-text'>This feature is available on the pro version only.</div></span>
            </td>
            <td>
            <input type="text" name="pagination_background_color" id="wpm_pagination_background_color"
                class="wpm-form-input wpm_6310_color_picker" data-format="rgb"
                value="<?php echo (isset($allSlider[398]) && $allSlider[398] !== '') ? $allSlider[398]:"rgb(17, 152, 219)";?>">
            </td>
        </tr>
        <tr class="pagination_act_field">
            <td>
              <b>Active Background  Color</b>
              <span class="wpm-6310-pro">(Pro) <div class='wpm-6310-pro-text'>This feature is available on the pro version only.</div></span>
            </td>
            <td>
            <input type="text" name="pagination_background_active_color" id="wpm_pagination_background_active_color"
                class="wpm-form-input wpm_6310_color_picker" data-format="rgb"
                value="<?php echo (isset($allSlider[399]) && $allSlider[399] !== '') ? $allSlider[399]:"rgb(4, 94, 112)";?>">
            </td>
        </tr>
        <tr class="pagination_act_field">
        <td>
          <b>Font Family</b>
          <span class="wpm-6310-pro">(Pro) <div class='wpm-6310-pro-text'>This feature is available on the pro version only.</div></span>
        </td>
        <td>
          <input name="pagination_font_family" id="pagination_font_family" type="text"
            value="<?php echo (isset($allSlider[402]) && $allSlider[402] !== '')?$allSlider[402]:'sans-serif' ?>" />
        </td>
      </tr> 
        </table>
    </div>
    </div>
</div>

<style>
/* Pagination */
.wpm-6310-pagination{
  display: none;
}
.wpm-6310-page-1{
  display: block;
}
.wpm-6310-page-number-wrapper-<?php echo $styleId ?>{
  width: 100%;
  float: left;
  text-align: center;
}
.wpm-6310-page-number-<?php echo $styleId ?>{
  display: inline-block;
  cursor: pointer;
  font-size: <?php echo isset($allSlider[391])?$allSlider[391]: 16 ?>px;
  color: <?php echo (isset($allSlider[396]) && $allSlider[396]) ? $allSlider[396] : 'rgb(249 249 249)' ?>;
  background-color: <?php echo (isset($allSlider[398]) && $allSlider[398]) ? $allSlider[398] : 'rgb(0 119 181)' ?>;
  padding: <?php echo isset($allSlider[393])?$allSlider[393]: 10 ?>px <?php echo isset($allSlider[394])?$allSlider[394]: 15 ?>px;
  margin: 0 <?php echo isset($allSlider[395])?$allSlider[395]: 1 ?>px;
  font-family: <?php echo isset($allSlider[402])?str_replace("+", " ", $allSlider[402]):'sans-serif' ?>;
}
.wpm-6310-page-number-wrapper-<?php echo $styleId ?> .wpm-6310-page-active{
  color: <?php echo (isset($allSlider[397]) && $allSlider[397]) ? $allSlider[397] : 'rgb(249 249 249)' ?>;;
  background-color: <?php echo (isset($allSlider[399]) && $allSlider[399]) ? $allSlider[399] : 'rgb(0 119 181)' ?>;
}
</style>  

<script>
     <?php
      if(!(isset($allSlider[400]) && $allSlider[400] != '') ){       
        echo 'jQuery(".pagination_act_field").hide();';
      }
    ?>
    jQuery(document).ready(function(){
     jQuery('body').on('click', '#activation_pagination', function(){
      if (jQuery(this).prop('checked') == true) {       
        jQuery(".pagination_act_field").show();
      }
      else{       
        jQuery(".pagination_act_field").hide();
      }
     
    });
});
</script>