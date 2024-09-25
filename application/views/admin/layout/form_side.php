<!-- Modal to add new record -->
<div
	class="offcanvas offcanvas-end"
	tabindex="-1"
	id="offcanvasRecord"
	data-bs-scroll="true"
	data-bs-backdrop="true"
	data-bs-keyboard="false"
	aria-labelledby="offcanvasLabel">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title" id="offcanvasLabel"><?=lang('New Record')?></h5>
        <button
			inert
            type="button"
            class="btn-close text-reset"
            data-bs-dismiss="offcanvas"
            aria-label="Close"></button>
    </div>
    <div class="offcanvas-body flex-grow-1">
		<?php
			echo form_open_multipart('', [
				'id' => 'formRecord',
				'class' => "add-new-record pt-0 row g-3 needs-validation form-type-{$formType}",
				'onsubmit' => 'return false',
			], [
				'mode' => '',
			]);
			foreach ($formData as $item):
				if($item['category'] === 'group'):
					$this->load->view("admin/layout/form_{$formType}_group_".$item['view'], ['item' => $item]);
				elseif($item['category'] === 'custom'):
					$this->load->view("admin/layout/form_{$formType}_custom_".$item['view'], ['item' => $item]);
				elseif($item['type'] === 'hidden'):
					echo form_input(
						[
							'type' => $item['type'],
							'name' => $item['field'],
							'id' => $item['id'],
						],
						set_admin_form_value($item['field'], $item['default'], null),
						$item['attributes'],
					);
				else:
		?>
		<div class="col-sm-12 form-validation-row">
			<div class="input-group input-group-merge">
				<?=get_admin_form_ico($item)?>
				<div class="form-floating form-floating-outline">
					<?php
						switch ($item['type']) {
							case 'password' :
								echo form_password(
									[
										'name' => $item['field'],
										'id' => $item['id'],
									],
									set_admin_form_value($item['field'], $item['default'], null),
									$item['attributes']
								);
								break;
							case 'checkbox' :
								echo get_admin_form_checkbox($item, $formType);
								break;
							case 'select' :
								echo form_dropdown(
									$item['field'],
									$item['options'] ?? [],
									set_admin_form_value($item['field'], $item['default'], null),
									array_merge([
										'id' => $item['id'],
										'data-style' => 'btn-default'
									], $item['attributes'])
								);
								break;
							case 'textarea' :
								echo form_textarea(
									[
										'name' => $item['field'],
										'id' => $item['id'],
										'rows' => $item['attributes']['rows']
									],
									set_admin_form_value($item['field'], $item['default'], null),
									$item['attributes']
								);
								break;
							case 'file' :
								echo form_upload([
									'name' => $item['field'],
									'id' => $item['id'],
								], '', $item['attributes']);
								break;
							default :
								echo form_input(
									[
										'type' => $item['type'],
										'name' => $item['field'],
										'id' => $item['id'],
									],
									set_admin_form_value($item['field'], $item['default'], null),
									$item['attributes']
								);
						}
						echo form_label(lang($item['label']), $item['id']);
					?>
				</div>
				<?php
					if($item['subtype'] === 'unique')
						echo form_button([
							'data-rel-field' => $item['field'],
							'type' => 'button',
							'class' => 'btn btn-outline-primary waves-effect btn-dup-check',
						], lang('Check'), [
							'onclick' => "checkDuplicate(this)",
						]);
				?>
			</div>
			<?=get_admin_form_text($item)?>
			<?=get_admin_form_list_item($item, $formType)?>
		</div>
		<?php
				endif;
			endforeach;
		?>
        <div class="col-sm-12">
            <button type="submit" class="btn btn-primary data-submit me-sm-4 me-1">Submit</button>
            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancel</button>
        </div>
        <?=form_close()?>
    </div>
</div>
<!--/ DataTable with Buttons -->
