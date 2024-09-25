<?php extract($item['data']); ?>
<div class="col-sm-12 form-validation-row">
	<div class="input-group input-group-merge mb-3">
		<div class="form-floating form-floating-outline">
			<ul
				class="list-unstyled mb-0 p-2 bg-lighter rounded-3 d-none"
				id="<?=$reply_list['id']?>">
				<div class="d-flex align-items-center">
					<div class="badge text-body text-truncate">
						<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
					</div>
				</div>
			</ul>
			<?=form_label(lang($reply_list['label']), $reply_list['id']);?>
		</div>
	</div>
    <div class="input-group input-group-merge">
        <?=get_admin_form_ico($reply_content)?>
        <div class="form-floating form-floating-outline">
            <?php
                echo form_textarea(
                    [
                        'name' => $reply_content['field'],
                        'id' => $reply_content['id'],
                        'rows' => $reply_content['attributes']['rows']
                    ],
                    set_admin_form_value($reply_content['field'], $reply_content['default'], null),
                    $reply_content['attributes']
                );
                echo form_label(lang($reply_content['label']), $reply_content['id']);
            ?>
        </div>
    </div>
    <?=get_admin_form_text($reply_content['form_text'])?>
</div>
