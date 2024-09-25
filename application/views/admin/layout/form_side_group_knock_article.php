<?php extract($item['data']); ?>
<div class="col-sm-12">
    <div
		class="p-3 border border-1 rounded-3 col-sm-12 border-input"
		data-form-type="side"
		data-group-name="<?=$item['group']?>"
		data-repeater-type="<?=$item['attr']['repeater_type']?>"
		data-repeater-count="1"
		data-repeater-id="<?=$item['attr']['repeater_id']?>">
        <div class="row">
            <div class="col-sm-8 align-content-center text-primary">
                <span>노크똑똑송 등록</span>
            </div>
            <div class="col-sm-4 text-end">
                <button class="btn btn-primary btn-sm" data-repeater-create type="button">
                    <i class="ri-add-line me-1"></i>
                    <span class="align-middle">추가</span>
                </button>
            </div>
        </div>
        <div data-repeater-list="<?=$item['group']?>">
            <div data-repeater-item data-row-index="1">
				<?php
					echo form_input(
						[
							'type' => 'hidden',
							'name' => get_group_field_name($item['attr'], $item['group'], $article_id['field']),
							'id' => get_group_field_id($item['attr'], $item['group'], $article_id['field']),
						],
						'',
						$article_id['attributes'],
					);
				?>
                <hr class="" />
                <div class="row">
                    <div class="col-sm-10">
                        <div class="form-validation-row mb-3">
                            <div class="input-group input-group-merge">
                                <div class="form-floating form-floating-outline">
                                    <?php
                                        echo form_input(
                                            [
                                                'name' => $subject['field'],
                                                'id' => $subject['id'],
                                            ],
                                            $subject['default'],
                                            $subject['attributes'],
                                        );
                                        echo form_label(lang($subject['label']), $subject['id']);
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-validation-row">
                            <div class="input-group input-group-merge">
                                <div class="form-floating form-floating-outline">
                                    <?php
                                        echo form_upload(
                                            [
                                                'name' => $uploads['field'],
                                                'id' => $uploads['id'],
                                            ],
                                            '',
                                            $uploads['attributes'],
                                        );
                                        echo form_label(lang($uploads['label']), $uploads['id']);
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2 text-end align-content-start">
                        <button class="btn btn-outline-danger btn-sm p-1" data-repeater-delete type="button">
                            <i class="ri-close-line"></i>
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
						<?=get_admin_form_list_item($uploads, 'side')?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
