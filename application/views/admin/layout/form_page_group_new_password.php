<?php extract($item['data']); ?>
<div class="row mb-4 form-validation-row">
	<?=form_label(lang($item['label']), '', ['class' => 'col-sm-2 col-form-label'])?>
	<div class="col-sm-10">
		<div class="input-group input-group-merge form-password-toggle">
			<?=get_admin_form_ico($password)?>
			<?php
				echo form_password([
					'name' => $password['field'],
					'id' => $password['id'],
				], $password['default'], $password['attributes']);
			?>
			<span class="input-group-text cursor-pointer text-primary">
				<i class="password-toggle-icon ri-eye-off-line"></i>
			</span>
		</div>
		<?=get_admin_form_text($password['form_text'])?>
	</div>
</div>
<div class="row mb-4 form-validation-row">
	<?=form_label(lang($password_confirm['label']), '', ['class' => 'col-sm-2 col-form-label'])?>
	<div class="col-sm-10">
		<div class="input-group input-group-merge form-password-toggle">
			<?=get_admin_form_ico($password_confirm)?>
			<?php
				echo form_password([
					'name' => $password_confirm['field'],
					'id' => $password_confirm['id'],
				], $password_confirm['default'], $password_confirm['attributes']);
			?>
			<span class="input-group-text cursor-pointer text-primary">
				<i class="password-toggle-icon ri-eye-off-line"></i>
			</span>
		</div>
		<?=get_admin_form_text($password_confirm['form_text'])?>
	</div>
</div>