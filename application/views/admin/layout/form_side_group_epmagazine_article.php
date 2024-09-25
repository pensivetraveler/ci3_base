<?php extract($item['data']); ?>
<div class="col-sm-12 mb-1">
	<div class="border rounded-3 px-3 py-2">
		<h6 class="text-body fw-medium text-primary"><?=lang($item['label'])?></h6>
		<?php
		echo form_input(
				[
						'type' => $article_id['type'],
						'name' => $article_id['name'],
						'id' => $article_id['id'],
				],
				set_admin_form_value($article_id['field'], $article_id['default'], null),
				$article_id['attributes'],
		);
		echo form_input(
				[
						'type' => $article_cd['type'],
						'name' => $article_cd['name'],
						'id' => $article_cd['id'],
				],
				set_admin_form_value($article_cd['field'], $article_cd['default'], null),
				$article_cd['attributes'],
		);
		?>
		<div class="col-sm-12 form-validation-row mb-4">
			<div class="input-group input-group-merge">
				<?=get_admin_form_ico($subject)?>
				<div class="form-floating form-floating-outline">
					<?php
					echo form_input([
							'name' => $subject['name'],
							'id' => $subject['id'],
					], $subject['default'], $subject['attributes']);
					?>
					<?=form_label(lang($subject['label']), $subject['id'])?>
				</div>
			</div>
			<?=get_admin_form_text($subject['form_text'])?>
		</div>
		<div class="col-sm-12 form-validation-row">
			<div class="input-group input-group-merge">
				<?=get_admin_form_ico($uploads)?>
				<div class="form-floating form-floating-outline">
					<?php
					echo form_upload(
							[
									'name' => $uploads['name'],
									'id' => $uploads['id'],
							],
							'',
							$uploads['attributes'],
					);
					echo form_label(lang($uploads['label']), $uploads['id']);
					?>
				</div>
			</div>
			<?=get_admin_form_text($uploads['form_text'])?>
			<?=get_admin_form_list_item($uploads, 'side')?>
		</div>
	</div>
</div>
