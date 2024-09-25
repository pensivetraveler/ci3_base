<?php extract($item['data']); ?>
<div class="col-sm-12">
	<div class="form-repeater p-3 border border-1 rounded-3 col-sm-12 border-input">
		<div class="row">
			<div class="col-sm-8 align-content-center">
				<span>EP매거진 등록</span>
			</div>
			<div class="col-sm-4 text-end">
				<button class="btn btn-primary btn-sm" data-repeater-create type="button">
					<i class="ri-add-line me-1"></i>
					<span class="align-middle">추가</span>
				</button>
			</div>
		</div>
		<div data-repeater-list="<?=$item['group']?>" class="form-validation-row">
			<div data-repeater-item>
				<hr class="" />
				<div class="row">
					<div class="col-sm-10">
						<div class="form-floating form-floating-outline mb-3">
							<?php
								echo form_dropdown(
									$article_cd['field'],
									$article_cd['options'],
									'',
									array_merge(
										$article_cd['attributes'],
										[
											'id' => 'form-repeater-1-1',
											'data-placeholder' => "속지분류",
											'data-allow-clear' => "true",
										]
									)
								);
								echo form_label(lang($article_cd['label']), $article_cd['id']);
							?>
						</div>
						<div class="form-floating form-floating-outline mb-3">
							<?php
								echo form_input(
									[
										'name' => $subject['field'],
										'id' => 'form-repeater-1-2',
									],
									$subject['default'],
									$subject['attributes'],
								);
								echo form_label(lang($subject['label']), $subject['id']);
							?>
						</div>
						<div class="form-floating form-floating-outline">
							<?php
								echo form_upload(
									[
										'name' => $thumbnail['field'],
										'id' => 'form-repeater-1-3',
									],
									'',
									$thumbnail['attributes'],
								);
								echo form_label(lang($thumbnail['label']), $thumbnail['id']);
							?>
						</div>
					</div>
					<div class="col-sm-2 text-end align-content-start">
						<button class="btn btn-outline-danger btn-sm p-1" data-repeater-delete type="button">
							<i class="ri-close-line"></i>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>