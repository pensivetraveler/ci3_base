		<!-- Core JS -->
		<!-- build:js assets/vendor/js/core.js -->
		<script src="<?php echo base_url('public/assets/admin/vendor/libs/jquery/jquery.js');?>"></script>
		<script src="<?php echo base_url('public/assets/admin/vendor/libs/popper/popper.js');?>"></script>
		<script src="<?php echo base_url('public/assets/admin/vendor/js/bootstrap.js');?>"></script>
		<script src="<?php echo base_url('public/assets/admin/vendor/libs/perfect-scrollbar/perfect-scrollbar.js');?>"></script>
		<script src="<?php echo base_url('public/assets/admin/vendor/libs/node-waves/node-waves.js');?>"></script>
		<script src="<?php echo base_url('public/assets/admin/vendor/libs/hammer/hammer.js');?>"></script>
		<script src="<?php echo base_url('public/assets/admin/vendor/libs/i18n/i18n.js');?>"></script>
		<script src="<?php echo base_url('public/assets/admin/vendor/libs/typeahead-js/typeahead.js');?>"></script>
		<script src="<?php echo base_url('public/assets/admin/vendor/js/menu.js');?>"></script>
		<!-- endbuild -->

		<!-- Vendor JS -->
		<script src="<?php echo base_url('public/assets/admin/vendor/libs/cleavejs/cleave.js');?>"></script>
		<script src="<?php echo base_url('public/assets/admin/vendor/libs/cleavejs/cleave-phone.js');?>"></script>
		<script src="<?php echo base_url('public/assets/admin/vendor/libs/cleavejs/cleave-phone.kr.js');?>"></script>
		<script src="<?php echo base_url('public/assets/admin/vendor/libs/flatpickr/flatpickr.js');?>"></script>
		<script src="<?php echo base_url('public/assets/admin/vendor/libs/bootstrap-select/bootstrap-select.js');?>"></script>
		<script src="<?php echo base_url('public/assets/admin/vendor/libs/select2/select2.js');?>"></script>
		<script src="<?php echo base_url('public/assets/admin/vendor/libs/moment/moment.js');?>"></script>
		<script src="<?php echo base_url('public/assets/admin/vendor/libs/sweetalert2/sweetalert2.js');?>"></script>

		<!-- Main JS -->
		<script src="<?php echo base_url('public/assets/admin/js/main.js');?>"></script>

		<!-- Page JS -->
		<script src="<?php echo base_url('public/assets/admin/js/app-page-form.js');?>"></script>
		<script src="<?php echo base_url('public/assets/admin/js/app-page-event.js');?>"></script>
		<script src="<?php echo base_url('public/assets/admin/js/app-page-validators.js');?>"></script>
		<?php if(isset($addJS['tail'])) add_javascript($addJS['tail']); ?>
		<script src="<?php echo base_url('public/assets/admin/js/app-page-onload.js');?>"></script>

		<?php if($this->config->config['life_cycle'] === 'post_controller'): ?>
		<script>
			$(function() {
				if(
					<?=$this->config->item('phptojs.namespace')?>.HOOK_PHPTOJS_VAR_DIALOG !== null
					&&
					Object.keys(<?=$this->config->item('phptojs.namespace')?>.HOOK_PHPTOJS_VAR_DIALOG).length > 0
				) {
					const flashData = <?=$this->config->item('phptojs.namespace')?>.HOOK_PHPTOJS_VAR_DIALOG;
					Swal.fire({
						title: flashData.title,
						text: flashData.text,
						icon: flashData.type,
						customClass: {
							confirmButton: 'btn btn-primary waves-effect waves-light'
						},
						buttonsStyling: false
					}).then(function(e){
						if(flashData.onclick.redirect) {
							const uri = flashData.onclick.redirect;
							<?=$this->config->item('phptojs.namespace')?>.HOOK_PHPTOJS_VAR_DIALOG = null;
							location.href = uri;
						}
					});
				}
			});
		</script>
		<?php endif; ?>
		<div
			style="
					display: none;
					position: absolute;
					top: 0;
					left: 0;
					/*width: 100vw;*/
					/*height: 100vh;*/
					width: 400px;
					height: 800px;
					border: 5px solid red;
				"
		></div>
	</body>
</html>