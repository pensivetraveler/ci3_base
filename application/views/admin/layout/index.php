<?=doctype('html5')?>
<?php $this->load->view('admin/includes/head'); ?>
<?php $this->load->view('admin/includes/header'); ?>
<?php $this->load->view('admin/includes/modal_prepend'); ?>
<?php $this->load->view($subPage); ?>
<?php $this->load->view('admin/includes/modal_append'); ?>
<?php $this->load->view('admin/includes/footer'); ?>
<?php $this->load->view('admin/includes/tail'); ?>