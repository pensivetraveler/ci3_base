<html
	lang="ko"
	class="light-style layout-navbar-fixed layout-menu-fixed layout-compact"
	dir="ltr"
	data-theme="theme-default"
	data-assets-path="/public/assets/admin/"
	data-template="vertical-menu-template-starter"
	data-style="light">
	<head>
		<title><?=isset($data['title'])?$data['title']:APP_NAME_EN;?></title>
		<meta charset="utf-8">
		<meta name="author" content="" />

		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="Cache-Control"  content="No-Cache">
		<meta http-equiv="Pragma" content="No-Cache">
		<meta http-equiv="expires" content="Fri, 04 Apr 2014 23:59:59 GMT" />
		<meta http-equiv="imagetoolbar" content="no">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
		<meta name="mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
		<meta name="format-detection" content="telephone=no,date=no,address=no,email=no,url=no"/>
		<link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url('public/assets/admin/img/favicon/favicon.ico');?>">

		<!-- Fonts -->
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
		<link rel="stylesheet" href="<?php echo base_url('public/assets/css/font.css');?>" />

		<!-- Icons. Uncomment required icon fonts -->
		<link rel="stylesheet" href="<?php echo base_url('public/assets/admin/vendor/fonts/remixicon/remixicon.css');?>" />
		<link rel="stylesheet" href="<?php echo base_url('public/assets/admin/vendor/fonts/flag-icons.css');?>" />

		<!-- Menu waves for no-customizer fix -->
		<link rel="stylesheet" href="<?php echo base_url('public/assets/admin/vendor/libs/node-waves/node-waves.css');?>" />

		<!-- Core CSS -->
		<link rel="stylesheet" href="<?php echo base_url('public/assets/admin/vendor/css/rtl/core.css');?>" class="template-customizer-core-css"/>
		<link rel="stylesheet" href="<?php echo base_url('public/assets/admin/vendor/css/rtl/theme-default.css');?>" class="template-customizer-theme-css"/>
		<link rel="stylesheet" href="<?php echo base_url('public/assets/admin/css/demo.css');?>" />
		<link rel="stylesheet" href="<?php echo base_url('public/assets/admin/css/style.css');?>" />

		<!-- Vendors CSS -->
		<link rel="stylesheet" href="<?php echo base_url('public/assets/admin/vendor/libs/perfect-scrollbar/perfect-scrollbar.css');?>" />
		<link rel="stylesheet" href="<?php echo base_url('public/assets/admin/vendor/libs/typeahead-js/typeahead.css');?>" />
		<link rel="stylesheet" href="<?php echo base_url('public/assets/admin/vendor/libs/animate-css/animate.css');?>" />
		<link rel="stylesheet" href="<?php echo base_url('public/assets/admin/vendor/libs/sweetalert2/sweetalert2.css');?>" />
		<link rel="stylesheet" href="<?php echo base_url('public/assets/admin/vendor/libs/flatpickr/flatpickr.css');?>" />
		<link rel="stylesheet" href="<?php echo base_url('public/assets/admin/vendor/libs/select2/select2.css');?>" />
		<link rel="stylesheet" href="<?php echo base_url('public/assets/admin/vendor/libs/bootstrap-select/bootstrap-select.css');?>" />
		<link rel="stylesheet" href="<?php echo base_url('public/assets/admin/vendor/libs/quill/editor.css');?>" />

		<!-- Page CSS -->
		<!-- Page -->
		<?php if(isset($addCSS)) add_stylesheet($addCSS); ?>
		<link rel="stylesheet" href="<?php echo base_url('public/assets/admin/vendor/css/pages/page-misc.css');?>" />

		<!-- Custom -->
		<script src="<?php echo base_url('public/assets/js/html5.min.js');?>"></script>
		<script src="<?php echo base_url('public/assets/js/placeholder.min.js');?>"></script>
		<script src="<?php echo base_url('public/assets/js/utils.js');?>"></script>
		<script src="<?php echo base_url('public/assets/js/josa.js');?>"></script>
		<script src="<?php echo base_url('public/assets/js/pattern.js');?>"></script>
		<script src="<?php echo base_url('public/assets/js/inflector.js');?>"></script>

		<!-- Helpers -->
		<script src="<?php echo base_url('public/assets/admin/vendor/js/helpers.js');?>"></script>
		<!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
		<!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
		<!--script src="<?php echo base_url('public/assets/admin/vendor/js/template-customizer.js');?>"></script -->
		<!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
		<script src="<?php echo base_url('public/assets/admin/js/config.js');?>"></script>

		<?php if(isset($addJS['head'])) add_javascript($addJS['head']); ?>
		<script src="<?php echo base_url('public/assets/admin/js/app-page-preset.js');?>"></script>
		<script src="<?php echo base_url('public/assets/admin/js/app-page-utils.js');?>"></script>
		<script src="<?php echo base_url('public/assets/admin/js/app-page-errors.js');?>"></script>

		<?php if(property_exists($this, 'phptojs')) echo $this->phptojs->getJsVars(); ?>

		<?php if(!isset($status_code) || (isset($status_code) && $status_code !== 404)): ?>
		<script>
			window.<?=$this->config->config['phptojs']['namespace']?> = window.<?=$this->config->config['phptojs']['namespace']?> || {};

			if(!window.<?=$this->config->config['phptojs']['namespace']?>.hasOwnProperty('ERRORS'))
				window.<?=$this->config->config['phptojs']['namespace']?>.ERRORS = [];

			window.onerror = function(event, source, lineno, colno, error) {
				let message, stack = [];

				if(event instanceof jQuery.Event) {
					console.log(source);
					message = colno;
					source = elementToSelector(event.target);
					lineno = colno = null;
				}else{
					message = event;
				}
				if(error !== undefined && error.hasOwnProperty('stack')) stack = error.stack;

				// setJavascriptErrorModal(message, source, lineno, colno, stack);
				<?=$this->config->config['phptojs']['namespace']?>.ERRORS.push(getJavascriptErrorObject(message, source, lineno, colno, stack));

				// 후킹 작업 후 true를 리턴하면 기본 동작을 막을 수 있음
				return false;
			};

			window.onload = function(){
				setTimeout(function() {
					showErrorModal(document.getElementById('errorModal'), <?=$this->config->config['phptojs']['namespace']?>.ERRORS);
				}, 500)
			};
		</script>
		<?php endif; ?>
	</head>
	<body data-class="<?=isset($class)?$class:''?>" data-method="<?=isset($method)?$method:''?>">