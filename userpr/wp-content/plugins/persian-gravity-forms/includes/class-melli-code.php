<?php if ( ! defined('ABSPATH') ) exit;

class GFParsi_MelliCode {

	private $field = array();

	public function __construct() {
		
        if( is_admin() ) {
			add_filter('gform_add_field_buttons'		    , array( $this, 'button'));
			add_filter('gform_field_type_title'				, array( $this, 'title'));
			add_filter('gform_editor_js_set_default_values' , array( $this, 'label'));
			add_action('gform_editor_js'					, array( $this, 'settings'));
			add_action('gform_field_standard_settings'		, array( $this, 'standard_settings'), 10, 2);
			add_filter('gform_tooltips'						, array( $this, 'tooltips'));
			add_filter('gform_admin_pre_render'				, array( $this ,'admin_conditional_logic' ) );
		}
		
		add_action('gform_field_input'						, array( $this, 'input'), 10, 5);
		add_action('gform_field_css_class'					, array( $this, 'classes'), 10, 3);
		add_action('gform_pre_submission'					, array( $this, 'pre_submission' ) );
		add_filter('gform_field_content'					, array( $this, 'city'), 10, 5);	
		add_filter('gform_field_validation'					, array( $this, 'validator'), 10, 4);	
		add_action('wp_footer'								, array( $this, 'js'));		
		add_action('gform_enqueue_scripts'					, array( $this, 'external_js') , 10 , 2 );
	}
	
	public function button( $field_groups ) {
		foreach( $field_groups as &$group ){
			if( $group["name"] == "advanced_fields" ){
				$group["fields"][] = array(
					"class"=>"button",
					"value" => __('National ID', 'GF_FA'),
					"onclick" => "StartAddField('mellicart');"
				);
			
			}
		}
		return $field_groups;
	}
	
	public function title($type) {
		if ($type == 'mellicart') {
			return __('National ID', 'GF_FA');
		}
	}
	
	public function label(){
		?>
		case "mellicart" :
		field.label = '<?php _e('National ID', 'GF_FA') ?>';
		break;
		<?php 
	}
	
	public function classes($classes, $field, $form){
		if( $field["type"] == "mellicart" ){
			$classes .= " gform_melli_code";
		}
		return $classes;
	}
	
	public function tooltips( $tooltips ) {
		$tooltips['gform_melli_code_city'] 	= __('<h6>نمایش لحظه ای شهر از روی کد ملی </h6>نمایش شهر و پیغام زیر فیلد کد ملی بعد از پر شدن فیلد . تذکر : در صورتی که این گزینه را فعال نمایید ،ممکن است فراخوانی شهر های ایران با توجه به زیاد بودن آنها سبب سنگین شدن صفحه گردد.' , 'GF_FA');
		$tooltips['gform_melli_code_seperate'] = __('<h6>جدا سازی ارقام</h6>در صورتی که این گزینه را فعال نمایید ، پس از پر شدن فیلد کد ملی ، <strong>در صورتی که کد ملی وارد شده صحیح تشخصی داده شود</strong> ؛ کد ملی به صورت زیر در خواهد آمد و در غیر این صورت علی صحیح نبودن کد ملی زیر فیلد نمایش داده خواهد شد :<br>xxx-xxxxxx-x' , 'GF_FA');
		$tooltips['gform_melli_code_abnormal'] = __('با توجه به اینکه کد ملی فقط باید به صورت عدد باشد ، در صورتی که کاراکتری غیر از عدد وارد شده باشد پیغام خطا نمایش داده خواهد شد .<br/>پیغام پیشفرض : کد ملی فقط باید به صورت عدد وارد شود .' , 'GF_FA');
		$tooltips['gform_melli_code_len'] = __('با توجه به اینکه کد ملی می بایست 10 رقمی باشد اگر تعداد رقم وارد شده ، اشتباه باشد پیغام خطا نمایش داده خواهد شد .<br>پیغام پیشفرض : کد ملی می بایست 10 رقمی باشد . تنها در صورتی مجاز به استفاده از کد های 8 یا 9 رقمی هستید که ارقام سمت چپ 0 باشند .' , 'GF_FA');
		$tooltips['gform_melli_code_dup'] = __('در صورتی که از تب وِیژگی تیک گزینه بدون تکرار را زده باشید ؛ بعد از پر شدن فرم و زدن دکمه ارسال پیغامی مبتنی بر تکراری بودن کد ملی نمایش داده خواهد شد . <br/>پیغام پیشفرض : این کد ملی توسط فرد دیگری ثبت شده است .' , 'GF_FA');
		$tooltips['gform_melli_code_noStandard'] = __('در صورتی که کد ملی وارد شده مطابق با الگوریتم کشور نباشد پیغام خطا نمایش داده خواهد شد .<br/>پیغام پیشفرض : کد ملی وارد شده مطابق با استانداردهای کشور نمی باشد .' , 'GF_FA');	
		return $tooltips;
	}
	
	public function input($input, $field, $value, $lead_id, $form_id ){
		
		if ( $field["type"] == "mellicart" ) {
			
			$is_admin 		= is_admin();
			$is_frontend 	= ! $is_admin; 		
			$is_form_editor = ( $is_admin && GFForms::get( 'view' ) != 'entry' );
			$is_entry_page 	= ( $is_admin && GFForms::get( 'view' ) == 'entry' );
	
			if ( ! $is_admin && ( RGFormsModel::get_input_type( $field ) == 'adminonly_hidden') ) {
				return '';
			}
		
			$field_id = $field["id"];
			$form_id = $is_admin && empty($form_id) ? rgget("id") : $form_id;
			
			$disabled_text = ( $is_admin && GFForms::get( 'view' ) != 'entry' ) ? "disabled='disabled'" : '';
			
			$size = rgar($field, "size");
			$class_suffix = GFForms::get( 'view' ) == 'entry' ? '_admin' : '';
			$class = $size . $class_suffix;
			
			$html5_attributes = '';
			
			$tabindex = GFCommon::get_tabindex();
	
			
			//$this->get_conditional_logic_event( 'keyup' )  //text or radio
			//$this->get_conditional_logic_event( 'change' ) //select
			//$this->get_conditional_logic_event( 'click' )	 // checkbox or radio
			//note : radio has keyup and click
			$logic_event = ! $is_form_editor && ! $is_entry_page ? $field->get_conditional_logic_event( 'keyup' ) : '';
		
			$input  = '<div class="ginput_container ginput_container_text ginput_container_melli_code">';
			$input .= '<input onblur="melli_code_'.$field_id.'(this);" name="input_'.$field_id.'" id="input_'.$form_id.'_'.$field_id.'" type="text" value="'.esc_attr($value).'" class="melli_code '.esc_attr($size).'" '.$tabindex.' '.$logic_event.' '.$html5_attributes.' '.$disabled_text.'/>';		
			$input .= '</div>';
			
			if ( $is_frontend ) {
				$input .= '<span class="city melli_code" id="city_'.$field_id.'"></span>';
			}
		
		}
		
		return $input;
	}
	
	public function settings(){ ?>
		<script type='text/javascript'>
			fieldSettings["mellicart"] = ".placeholder_setting, .input_mask_setting, .label_placement_setting, .prepopulate_field_setting, .conditional_logic_field_setting, .label_setting, .admin_label_setting, .size_setting, .rules_setting, .visibility_setting, .duplicate_setting, .default_value_setting, .description_setting, .css_class_setting, .mellicart_setting";
			jQuery(document).bind("gform_load_field_settings", function(event, field, form){
				jQuery("#field_mellicart").attr("checked", field["field_mellicart"] == true);
				jQuery("#field_mellicart_sp").attr("checked", field["field_mellicart_sp"] == true);
				jQuery("#field_mellicart_sp1").val(field["field_mellicart_sp1"]);
				jQuery("#field_mellicart_sp2").val(field["field_mellicart_sp2"]);
				jQuery("#field_mellicart_sp3").val(field["field_mellicart_sp3"]);
				jQuery("#field_mellicart_sp4").val(field["field_mellicart_sp4"]);
			});
		</script>
		<?php
	}
	
	
	public function admin_conditional_logic( $form ) {
		
		if ( GFCommon::is_entry_detail() ) {
			return $form;
		}

		echo "<script type='text/javascript'>" .
		     " gform.addFilter('gform_is_conditional_logic_field', function (isConditionalLogicField, field) {" .
		     "     return field.type == 'mellicart' ? true : isConditionalLogicField;" .
		     '	});' .
		     "	gform.addFilter('gform_conditional_logic_operators', function (operators, objectType, fieldId) {" .
		     '		var targetField = GetFieldById(fieldId);' .
		     "		if (targetField && targetField['type'] == 'mellicart') {" .
		     "			operators = {'is':'is','isnot':'isNot', '>':'greaterThan', '<':'lessThan', 'contains':'contains', 'starts_with':'startsWith', 'ends_with':'endsWith'};" .
		     '		}' .
		     '		return operators;' .
		     '	});' .
		     '</script>';

		return $form;
	}
	
	public function standard_settings( $position, $form_id ){
		
		if( $position == 50 ){ ?>
		
			<li class="mellicart_setting field_setting">
				
				<input type="checkbox" id="field_mellicart" onclick="SetFieldProperty('field_mellicart', this.checked);" />
				<label for="field_mellicart" class="inline">
					<?php _e("نمایش شهر بر اساس کد ملی", "GF_FA"); ?>
					<?php gform_tooltip("gform_melli_code_city"); ?>
				</label>
				
				
				<br/>
				<input type="checkbox" id="field_mellicart_sp" onclick="SetFieldProperty('field_mellicart_sp', this.checked);" />
				<label for="field_mellicart_sp" class="inline">
					<?php _e("جدا سازی خودکار ارقام توسط خط فاصله", "GF_FA"); ?>
					<?php gform_tooltip("gform_melli_code_seperate"); ?>
				</label>
				<br/>
				
				
				<br/>
				<label for="field_mellicart_sp1" >
					<?php _e("پیغام زمانی که مقدار وارد شده شامل کاراکتر غیر عددی باشد", "GF_FA"); ?>
					<?php gform_tooltip("gform_melli_code_abnormal"); ?>
				</label>
				<input type="text" class="fieldwidth-3" id="field_mellicart_sp1" size="35" onkeyup="SetFieldProperty('field_mellicart_sp1', this.value);" />
				<br/>
				
				
				<br/>
				<label for="field_mellicart_sp2" >
					<?php _e("پیغام زمانیکه تعداد ارقام وارد شده استاندارد نباشد", "GF_FA"); ?>
					<?php gform_tooltip("gform_melli_code_len"); ?>
				</label>
				<input type="text" class="fieldwidth-3" id="field_mellicart_sp2" size="35" onkeyup="SetFieldProperty('field_mellicart_sp2', this.value);" />
				<br/>
				
				
				<br/>
				<label for="field_mellicart_sp3" >
					<?php _e("پیغام زمانیکه کد ملی وارد شده قبلا ثبت شده باشد", "GF_FA"); ?>
					<?php gform_tooltip("gform_melli_code_dup"); ?>
				</label>
				<input type="text" class="fieldwidth-3" id="field_mellicart_sp3" size="35" onkeyup="SetFieldProperty('field_mellicart_sp3', this.value);" />
				<br/>
				
				
				<br/>
				<label for="field_mellicart_sp4" >
					<?php _e("پیغام زمانیکه کد ملی وارد شده مطابق با الگوی ملی نباشد", "GF_FA"); ?>
					<?php gform_tooltip("gform_melli_code_noStandard"); ?>
				</label>
				<input type="text" class="fieldwidth-3" id="field_mellicart_sp4" size="35" onkeyup="SetFieldProperty('field_mellicart_sp4', this.value);" />
				<br/>
				
				
			</li>
			<?php
		}
	}
	
	
	public function pre_submission( $form ) {
		
		$mellicart_fields = GFCommon::get_fields_by_type( $form, array( 'mellicart' ) );
		
		foreach ( (array) $mellicart_fields as $field ) {
			
			$input_name = "input_{$field['id']}";
			$input_value   = ! rgempty( $input_name ) ? rgpost( $input_name ) : '';
			
			if ( !empty( $input_value ) ) {
				
				if ( strlen($input_value) == 8 ) {
					$_POST["input_{$field['id']}"] = '00'.$input_value;
				}
				elseif ( strlen($input_value) == 9 ) {
					$_POST["input_{$field['id']}"] = '0'.$input_value;	
				}
				else {
					$_POST["input_{$field['id']}"] = $input_value;
				}
			}
		}
	}

	public function city( $content, $field, $value, $lead_id, $form_id ){
		
		if ( $field['type'] == 'mellicart' ) {
			if ( ! is_admin() ) {
				$this->field[] = $field;	
			}
		}
		return $content;
	}
	
	public function external_js( $form , $ajax ) {
		
		$melli_code = GFCommon::get_fields_by_type( $form, array( 'mellicart' ) );
		
		foreach ( (array) $melli_code as $field ) {
			
			$is_seperate = gfa_get('field_mellicart_sp', $field);
            $is_seperate = !empty($is_seperate) ? $is_seperate : false;

            $show_city = gfa_get('field_mellicart', $field);
            $show_city = !empty($show_city) ? $show_city : false;

            if ( $show_city || $is_seperate) {
				wp_register_script( 'gform_mellicode', GF_PARSI_URL . 'assets/js/melli-code.min.js', array() , GF_PARSI_VERSION, false );
				wp_enqueue_script(  'gform_mellicode' );
				break;
			}
		}
	}
	
	public function js() {
		
		$fields = $this->field;
		
		if ( empty($fields) )
			return;
			
		foreach( (array) $fields as $field ) {
		
            $is_seperate = gfa_get('field_mellicart_sp', $field);
            $is_seperate = !empty($is_seperate) && $is_seperate ? 1 : 0;

            $show_city = gfa_get('field_mellicart', $field);
            $show_city = !empty($show_city) && $show_city ? 1 : 0;

            if ( !$show_city && !$is_seperate )
                return;
			
            $message1 = gfa_get('field_mellicart_sp1', $field);
            $message1 = !empty($message1) ? $message1 : __('کد ملی فقط باید به صورت عدد وارد شود .', 'GF_FA');

            $message2 = gfa_get('field_mellicart_sp2', $field);
            $message2 = !empty($message2) ? $message2 : __('کد ملی می بایست 10 رقمی باشد .', 'GF_FA');

            $message3 = gfa_get('field_mellicart_sp4', $field);
            $message3 = !empty($message3) ? $message3 : __('کد ملی وارد شده مطابق با استانداردهای کشور نمی باشد .', 'GF_FA');
            ?>

            <script type="text/javascript">
                function melli_code_<?php echo $field['id']; ?> (melli_code) {
					var field_id 	= "<?php echo $field['id'] ?>";
					var message1	= "<?php echo $message1 ?>";
					var message2	= "<?php echo $message2 ?>";
					var message3	= "<?php echo $message3 ?>";
					var show_city	=  <?php echo $show_city ?>;
					var is_seperate	=  <?php echo $is_seperate ?>;
					melli_code_function( melli_code, field_id , message1 , message2 , message3 , is_seperate , show_city);
                }
            </script>

            <?php
        }
	}
	
	public function is_valid( $melli_code = '' ){
		
		if ( !empty($melli_code) ) {
			
			$_melli_code = $melli_code;
				
			if( strlen($melli_code) == 8 )
				$_melli_code = '00' . $melli_code;
			
			if( strlen($melli_code) == 9 )
				$_melli_code = '0' . $melli_code;
		
			$pre_check = array( 
				'0000000000', 
				'1111111111', 
				'2222222222', 
				'3333333333', 
				'4444444444', 
				'5555555555', 
				'6666666666', 
				'7777777777', 
				'8888888888', 
				'9999999999',
			);
			
			if( in_array( $_melli_code , $pre_check ) )
				return 2;
		
			if(	! is_numeric($melli_code) ) 
				return 4;
			
			$melli_code = (string) preg_replace('/[^0-9]/','',$melli_code);
			
			if( strlen($melli_code) > 10 || strlen($melli_code) <8 )
				return 3;
		
			$melli_code = $_melli_code;
		
			$list_code = str_split($melli_code);
			$last = (int) $list_code[9];
			unset($list_code[9]);
		
			$i = 10;
			$sum = 0;
			foreach( $list_code as $key => $val ) {
				$sum += intval($val) * $i;
				$i--;
			}
			
			$mod =(int) $sum % 11;
		
			if($mod >= 2)
				$mod = 11 - $mod;
			if ($mod != $last)
				return 2;
			else
				return 1;
		}
		
		return false;
	}
	
		
	public function validator($result, $value, $form, $field){	
		
		if ( $field["type"] == 'mellicart' ) {
			
			$melli_code = !empty($value) ? str_replace( '-', '', $value) : '';
			
			$is_valid = $this->is_valid($melli_code);
			
			if ( $is_valid == 4 ) {
				$message 			= gfa_get('field_mellicart_sp1', $field);
				$result['message'] 	= !empty($message) ? $message : __('کد ملی فقط باید به صورت عدد وارد شود .' , 'GF_FA');
				$result['is_valid'] = false;
				return $result;
			}		
			
			if ( $is_valid == 3 ){
				$message 			= gfa_get('field_mellicart_sp2', $field);
				$result['message']  = !empty($message) ? $message :  __('کد ملی می بایست 10 رقمی باشد . تنها در صورتی مجاز به استفاده از کد های 8 یا 9 رقمی هستید که ارقام سمت چپ 0 باشند .' , 'GF_FA');
				$result['is_valid'] = false;
				return $result;
			}
			
			if ( $is_valid == 2 ) {
				$message			= gfa_get('field_mellicart_sp4', $field);
				$result['message'] 	= !empty($message) ? $message :  __('کد ملی وارد شده مطابق با استانداردهای کشور نمی باشد .' , 'GF_FA');
				$result['is_valid'] = false;
				return $result;
			}
			
			if ( $field['noDuplicates'] ) { 
		
				if( strlen($melli_code) == 8 )
					$melli_code = '00' . $melli_code;
			
				if( strlen($melli_code) == 9 )
					$melli_code = '0' . $melli_code;
				
				if ( RGFormsModel::is_duplicate($form['id'], $field, $melli_code) ) {
					$message			= gfa_get('field_mellicart_sp3', $field);
					$result['message'] 	= !empty($message) ? $message :  __('این کد ملی توسط فرد دیگری ثبت شده است .' , 'GF_FA');
					$result['is_valid'] = false;
					return $result;
				}
			}
		}
				
		return $result;
	}
	

}

new GFParsi_MelliCode();