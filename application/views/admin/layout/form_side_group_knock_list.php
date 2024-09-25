<?php extract($item['data']) ?>
<div class="col-sm-12 form-validation-row">
    <?php
        echo form_input(
            [
                'type' => 'hidden',
                'name' => $article_id['name'],
                'id' => $article_id['id'],
            ],
			'',
            $article_id['attributes'],
        );
    ?>
    <div class="input-group input-group-merge">
        <?=get_admin_form_ico($uploads)?>
        <div class="form-floating form-floating-outline">
            <?php
				echo form_upload([
					'name' => $uploads['name'],
					'id' => $uploads['id'],
				],
			'',
				$uploads['attributes']);
            ?>
            <?=form_label(lang($uploads['label']), $uploads['id'])?>
        </div>
    </div>
    <?=get_admin_form_text($uploads['form_text'])?>
	<?=get_admin_form_list_item($uploads, 'side')?>
</div>