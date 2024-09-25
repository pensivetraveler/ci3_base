<div class="row g-6 mb-6">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb"><?=get_admin_breadcrumbs($titleList)?></ol>
    </nav>
</div>
<div class="row g-6 mb-6">
    <div class="card">
        <div class="card-header border-bottom">
            <h5 class="card-title mb-0">Filters</h5>
            <div class="d-flex justify-content-between align-items-center row gx-5 pt-4 gap-5 gap-md-0">
                <div class="col-md-4 user_role"></div>
                <div class="col-md-4 user_plan"></div>
                <div class="col-md-4 user_status"></div>
            </div>
        </div>
        <div class="card-datatable table-responsive">
			<table class="datatables-records table table-bordered">
                <thead>
					<tr>
						<th></th>
						<th></th>
						<?php foreach ($columns as $column): ?>
						<th><?=lang($column['label'])?></th>
						<?php endforeach; ?>
					</tr>
                </thead>
            </table>
        </div>

        <!-- Modal to add new record -->
        <?php
        if($this->sideForm)
            $this->load->view('admin/layout/form_side', ['formType' => 'side', 'formData' => $formData]);
        ?>
        <!--/ DataTable with Buttons -->

    </div>
</div>