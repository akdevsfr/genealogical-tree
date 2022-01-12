<?php
namespace Genealogical_Tree\Genealogical_Tree_Public\Traits;


trait Genealogical_Tree_Style_1 {

	/**
	 * Get display members for shortcode
	 *
	 * @since    1.0.0
	 */
	public function display_tree_style1($tree, $setting) {

		$html  ='';
		$html .='
		<div class="gt-style-1">';
			if(isset($setting->ancestor) && $setting->ancestor=='on'){
				$html .= '<ul class="has-ancestor">';
					$html .= '<li class="parent alter-tree">';
						$html .='<ul class="parents">';
							$html .= $this->tree_style_alter($tree, $setting);
						$html .='</ul>';
					$html .='</li>';
					$html .= '<li class="child root">'; }
						$html .='<ul class="childs">';
							$html .= $this->tree_style1($tree, $setting);
						$html .='</ul>';
			if(isset($setting->ancestor) && $setting->ancestor=='on'){
					$html .='</li>';
				$html .='</ul>';
			}
		$html .='
		</div>';
		return $html;
	}

	/**
	 * Get display members for shortcode
	 *
	 * @since    1.0.0
	 */
	public function tree_style_alter($tree, $setting, $checker = array(), $gen = 0) {

		$gen++;

		if( $gen > 2 ) {
			if ( gt_fs()->is_not_paying() && ! gt_fs()->is_trial() ) {
			 	return;
			}
		}
		
		if( $setting->generation_number_ancestor != -1 ) {
			if( $gen > $setting->generation_number_ancestor ) {
			 	return;
			}
		}

		$famc = get_post_meta($tree, 'famc', true);

		$father = get_post_meta($famc, 'father', true);
		$mother = get_post_meta($famc, 'mother', true);

		$families = [1];

		if($setting->collapsible_family_root && $setting->collapsible_family_onload && count($families) > 0){
			$collapsible_family_onload = 'display:none;';
			$sign = '+';
		} else {
			$collapsible_family_onload = '';
			$sign = '-';
		}

		$html ='';
		if($father){
			$html .='<li class="parent father">';
				$html .='<ul class="parents">';
					$html .= $this->tree_style_alter($father, $setting, $families, $gen);
				$html .='</ul>';
				$html .= $this->ind_style($father, $setting, $gen, $families, 'alter', $sign);
			$html .='</li>';
		}

		if($mother){
			$html .='<li class="parent mother">';
				$html .='<ul class="parents">';
					$html .= $this->tree_style_alter($mother, $setting, $families, $gen);
				$html .='</ul>';
				$html .= $this->ind_style($mother, $setting, $gen, $families, 'alter', $sign);
			$html .='</li>';
		}
		return $html;
	}

	/**
	 * Get display members for shortcode
	 *
	 * @since    1.0.0
	 */
	public function tree_style1($tree, $setting, $checker = array(), $gen = 0) {

		$gen++;

		if($gen > 5){
			if (gt_fs()->is_not_paying() && !gt_fs()->is_trial()) {
			 	return;
			}
		}

		if($setting->generation_number != -1) {
			if($gen > $setting->generation_number){
			 	return;
			}
		}
		
		$families = $this->get_families_by_root($tree, $setting);

		if($setting->collapsible_family_root && $setting->collapsible_family_onload && count($families) > 0){
			$collapsible_family_onload = 'display:none;';
			$sign = '+';
		} else {
			$collapsible_family_onload = '';
			$sign = '-';
		}

		$html ='';
		$html .='<li class="child root">';
			$html .= $this->ind_style($tree, $setting, $gen, $families, 'root', $sign);
			
			if($families) {

				$html .='<ul class="families" style="'.$collapsible_family_onload.'">';
				foreach ($families as $key => $family) {

					$sex = get_post_meta($tree, 'sex', true);
					if($sex == 'M' || ($sex == 'F' && $setting->female_tree != 'on')){
						if($family->spouse) {
							array_push($checker, $family->spouse);
							$html .='<li class="family spouse">';
								if($setting->collapsible_family_spouse && $setting->collapsible_family_onload && count($families) > 0){
									$collapsible_family_onload = 'display:none;';
									$sign = '+';
								} else {
									$collapsible_family_onload = '';
									$sign = '-';
								}
								$html .= $this->ind_style($family->spouse, $setting, null, $family->chill, 'spouse', $sign);
								if($family->chill) {
									$html .='<ul class="childs" style="'.$collapsible_family_onload.'">';
									foreach ($family->chill as $key => $chill) {
										if(!in_array($chill, $checker)){
											$html .= $this->tree_style1($chill, $setting, $checker, $gen);
										}
									}
									$html .='
									</ul>';
								 }
								$html .='
							</li>';
						} else {
							if($family->chill) {
							
							$sex_alt = '';
							
							if($sex == 'M') {
								$sex_alt = 'F';
							}
							
							if($sex == 'F') {
								$sex_alt = 'M';
							}

							$html .='
								<li class="family">
									<div class="ind">
										<div class="ind-cont">';
											if(isset($setting->thumb->show) && $setting->thumb->show == 'on') {
											$image_url = GENEALOGICAL_TREE_DIR_URL . 'public/img/ava-'.$sex_alt.'.jpg';
											$html .='
											<div class="image">
												<div class="image-cont">
													<img src="'.$image_url.'">
												</div>
											</div>';
											}
											$html .='
											<div class="info">
												<div class="name">
													Unknown
												</div>
											</div>
										</div>
									</div>';
									$html .='
									<ul class="childs">';
									foreach ($family->chill as $key => $chill) {
										if(!in_array($chill, $checker)){
											$html .= $this->tree_style1($chill, $setting, $checker, $gen);
										}
									}
									$html .='
									</ul>';
									$html .='
								</li>';
							}
						}
					}
				}
				$html .='</ul>';
			}
		$html .='</li>';
		return $html;
	}
}