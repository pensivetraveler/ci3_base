<?php extract($item['data']); ?>
<div class="col-sm-12">
    <div
		class="form-repeater border border-input rounded-3 px-0 overflow-hidden"
		data-form-type="side"
		data-group-name="<?=$item['group']?>"
		data-repeater-type="<?=$item['attr']['repeater_type']?>"
		data-repeater-count="4"
		data-repeater-id="<?=$item['attr']['repeater_id']?>">
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-center">주차</th>
                        <th class="text-center">내용</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    <?php for($i = 0; $i < 4; $i++): ?>
                    <tr data-group-row="<?=$i?>">
                        <td class="text-center">
                            <span class="fw-medium"><?=$i+1?>주</span>
                        </td>
                        <td>
							<?php
								echo form_input(
									[
										'type' => 'hidden',
										'name' => get_group_field_name($item['attr'], $item['group'], $article_id['field'], $i),
										'id' => get_group_field_id($item['attr'], $item['group'], $article_id['field'], $i),
									],
									'',
									$article_id['attributes'],
								);
								echo form_input(
									[
										'type' => 'hidden',
										'name' => get_group_field_name($item['attr'], $item['group'], $sort_order['field'], $i),
										'id' => get_group_field_id($item['attr'], $item['group'], $sort_order['field'], $i),
									],
									$i+1,
									$sort_order['attributes'],
								);
							?>
							<div class="col-sm-12 form-validation-row">
                                <div class="input-group input-group-merge mb-3">
                                    <div class="form-floating form-floating-outline">
                                        <?php
                                            echo form_input(
                                                [
                                                    'type' => $subject['type'],
													'name' => get_group_field_name($item['attr'], $item['group'], $subject['field'], $i),
													'id' => get_group_field_id($item['attr'], $item['group'], $subject['field'], $i),
                                                ],
                                                set_admin_form_value($subject['field'], $subject['default'], null),
                                                $subject['attributes']
                                            );
                                            echo form_label(lang($subject['label']), $subject['id']);
                                        ?>
                                    </div>
                                </div>
                                <div class="input-group input-group-merge">
                                    <div class="form-floating form-floating-outline">
                                        <?php
                                            echo form_textarea(
                                                [
													'name' => get_group_field_name($item['attr'], $item['group'], $content['field'], $i),
													'id' => get_group_field_id($item['attr'], $item['group'], $content['field'], $i),
                                                    'rows' => $content['attributes']['rows']
                                                ],
                                                set_admin_form_value($content['field'], $content['default'], null),
                                                $content['attributes']
                                            );
                                            echo form_label(lang($content['label']), $content['id']);
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endfor; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    const sample = {
        subject : [
            '뒤뚱뛰뚱 펭순이',
            '귀여운 펭귄',
            '까망이와 하양이',
            '폭신폭신 계란놀이'
        ],
        content : [
            'Go & Stop(음표와 쉼표)의 개념을 걷기와 쉬기로 연결하여 대근육 발달을 돕고, 빨,주,노,초,파 다섯 가지 색을 악기와 연결하여 연주하며 색 인지 능력과 음감을 발달시킵니다.',
            '필폼을 손으로 놀이하며 소근육을 조절해 보고, 다양한 놀이로 창의력 향상 시키며 이글루 도장을 찍은 결과물을 보며 성취감을 느껴봅니다.',
            '하얀색 알(2분 음표), 검정색 알(4분 음표) 을 음표와 빠르기로 연결하고,다양한 방법으로 빠르기를 경험하며 창의력을 증진시킵니다.',
            '계란 뒤집기 교구를 통해 눈과 손의 협응력을 키우고, 플레이 버블 폼으로 촉감을 느껴보며 물감이 섞었을 때 나타나는 시각적인 반응에 대한 감각적인 인지와 창의력이 향상됩니다.'
        ],
    };
    function setSample() {
        Object.keys(sample).map((item) => {
            sample[item].forEach((val, key) => {
                const input = document.getElementById('formRecord').querySelector(`[name="article_list[${key}][${item}]"`);
                input.value = val;
                input.setAttribute('data-input-changed' , 'true');
            });
        });
    }
</script>