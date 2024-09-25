/**
 * Page User List
 */

'use strict';

// Datatable (jquery)
$(function () {
    let borderColor, bodyBg, headingColor;

    if (isDarkStyle) {
        borderColor = config.colors_dark.borderColor;
        bodyBg = config.colors_dark.bodyBg;
        headingColor = config.colors_dark.headingColor;
    } else {
        borderColor = config.colors.borderColor;
        bodyBg = config.colors.bodyBg;
        headingColor = config.colors.headingColor;
    }

    // Variable declaration for table
    var dt_table = $('.datatables-users'),
        select2 = $('.select2'),
        userView = 'app-user-view-account.html',
        statusObj = {
            1: { title: 'Pending', class: 'bg-label-warning' },
            2: { title: 'Active', class: 'bg-label-success' },
            3: { title: 'Inactive', class: 'bg-label-secondary' }
        };

    if (select2.length) {
        var $this = select2;
        select2Focus($this);
        $this.wrap('<div class="position-relative"></div>').select2({
            placeholder: 'Select Country',
            dropdownParent: $this.parent()
        });
    }

    // Users datatable
    if (dt_table.length) {
        var dt_user = dt_table.DataTable({
            ajax: common.API_URI + '/students', // JSON file to add data
            columns: [
                // columns according to JSON
                { data: '' },
                { data: 'id' },
                { data: 'name' },
                { data: 'birth' },
                { data: 'gender' },
                { data: 'email' },
                { data: 'tel' },
                { data: 'addr' },
                { data: 'class_per_week' },
                { data: 'class_time' },
                { data: 'class_type' },
                { data: 'register_dt' },
                { data: 'enroll_staus' },
                { data: 'withdraw_dt' },
                { data: 'action' },
            ],
            columnDefs: [
                {
                    // For Responsive
                    className: 'control',
                    searchable: false,
                    orderable: false,
                    responsivePriority: 2,
                    targets: 0,
                    render: function (data, type, full, meta) {
                        return '';
                    }
                },
                {
                    // For Checkboxes
                    targets: 1,
                    orderable: false,
                    render: function () {
                        return '<input type="checkbox" class="dt-checkboxes form-check-input">';
                    },
                    checkboxes: {
                        selectAllRender: '<input type="checkbox" class="form-check-input">'
                    }
                },
                {
                    targets: 2,
                    render: function (data, type, full, meta) {
                        var $id = full['id'];
                        return '<span >' + $id + '</span>';
                    }
                },
                {
                    targets: 3,
                    render: function (data, type, full, meta) {
                        var $name = full['name'];
                        return '<span >' + $name + '</span>';
                    }
                },
                {
                    targets: 4,
                    render: function (data, type, full, meta) {
                        var $birth = full['birth'];
                        return '<span >' + $birth + '</span>';
                    }
                },
                {
                    targets: 5,
                    render: function (data, type, full, meta) {
                        var $gender = full['gender'];
                        return '<span >' + $gender + '</span>';
                    }
                },
                {
                    targets: 6,
                    render: function (data, type, full, meta) {
                        var $email = full['email'];
                        return '<span >' + $email + '</span>';
                    }
                },
                {
                    targets: 7,
                    render: function (data, type, full, meta) {
                        var $tel = full['tel'];
                        return '<span >' + $tel + '</span>';
                    }
                },
                {
                    targets: 8,
                    render: function (data, type, full, meta) {
                        var $addr = full['addr1']+' '+full['addr2']+' '+full['addr3'];
                        return '<span >' + $addr + '</span>';
                    }
                },
                {
                    targets: 9,
                    render: function (data, type, full, meta) {
                        var $class_per_week = full['class_per_week'];
                        return '<span >' + $class_per_week + '회</span>';
                    }
                },
                {
                    targets: 10,
                    render: function (data, type, full, meta) {
                        var $class_time = full['class_hour']+':'+full['class_minute'];
                        return '<span >' + $class_time + '</span>';
                    }
                },
                {
                    targets: 11,
                    render: function (data, type, full, meta) {
                        var $class_type = '1:'+full['class_type'];
                        return '<span >' + $class_type + '</span>';
                    }
                },
                {
                    targets: 12,
                    render: function (data, type, full, meta) {
                        var $register_dt = full['register_dt'];
                        return '<span >' + $register_dt + '</span>';
                    }
                },
                {
                    targets: 13,
                    render: function (data, type, full, meta) {
                        var $enroll_status;
                        switch (full['enroll_status']) {
                            case '1' :
                                $enroll_status = '수강대기'
                                break;
                            case '2' :
                                $enroll_status = '수강중'
                                break;
                            case '3' :
                                $enroll_status = '수강종료'
                                break;
                        }
                        return '<span >' + $enroll_status + '</span>';
                    }
                },
                {
                    targets: 14,
                    render: function (data, type, full, meta) {
                        var $withdraw_dt = full['withdraw_dt']?full['withdraw_dt']:'-';
                        return '<span >' + $withdraw_dt + '</span>';
                    }
                },
                {
                    // Actions
                    targets: 15,
                    title: 'Actions',
                    searchable: false,
                    orderable: false,
                    render: function (data, type, full, meta) {
                        return (
                            '<div class="d-flex align-items-center gap-50">' +
                            '<a href="javascript:;" class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect delete-record" data-bs-toggle="tooltip" title="Delete User" data-id="' + full.user_id +'"><i class="ri-delete-bin-7-line ri-20px"></i></a>' +
                            '<a href="'+ common.EDIT_VIEW_URI + `/${full.user_id}` +'" class="dropdown-item" data-id="'+ full.user_id +'"><i class="ri-edit-box-line me-2"></i></a>' +
                            '</div>'
                        );
                    }
                }
            ],
            order: [[2, 'desc']],
            dom:
                '<"row"' +
                '<"col-md-2 d-flex align-items-center justify-content-md-start justify-content-center"<"dt-action-buttons mt-5 mt-md-0"B>>' +
                '<"col-md-10"<"d-flex align-items-center justify-content-md-end justify-content-center"<"me-4"f><"add-new">>>' +
                '>t' +
                '<"row"' +
                '<"col-sm-12 col-md-6"i>' +
                '<"col-sm-12 col-md-6"p>' +
                '>',
            language: {
                sLengthMenu: 'Show _MENU_',
                search: '',
                searchPlaceholder: 'Search User'
            },
            // Buttons with Dropdown
            buttons: [
                {
                    extend: 'collection',
                    className: 'btn btn-outline-secondary dropdown-toggle waves-effect waves-light',
                    text: '<span class="d-flex align-items-center"><i class="ri-upload-2-line ri-16px me-2"></i> <span class="d-none d-sm-inline-block">Export</span></span> ',
                    buttons: [
                        {
                            extend: 'print',
                            text: '<i class="ri-printer-line me-1" ></i>Print',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [1, 2, 3, 4, 5],
                                // prevent avatar to be print
                                format: {
                                    body: function (inner, coldex, rowdex) {
                                        if (inner.length <= 0) return inner;
                                        var el = $.parseHTML(inner);
                                        var result = '';
                                        $.each(el, function (index, item) {
                                            if (item.classList !== undefined && item.classList.contains('user-name')) {
                                                result = result + item.lastChild.firstChild.textContent;
                                            } else if (item.innerText === undefined) {
                                                result = result + item.textContent;
                                            } else result = result + item.innerText;
                                        });
                                        return result;
                                    }
                                }
                            },
                            customize: function (win) {
                                //customize print view for dark
                                $(win.document.body)
                                    .css('color', headingColor)
                                    .css('border-color', borderColor)
                                    .css('background-color', bodyBg);
                                $(win.document.body)
                                    .find('table')
                                    .addClass('compact')
                                    .css('color', 'inherit')
                                    .css('border-color', 'inherit')
                                    .css('background-color', 'inherit');
                            }
                        },
                        {
                            extend: 'csv',
                            text: '<i class="ri-file-text-line me-1" ></i>Csv',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [2, 3, 4, 5],
                                // prevent avatar to be display
                                format: {
                                    body: function (inner, coldex, rowdex) {
                                        if (inner.length <= 0) return inner;
                                        var el = $.parseHTML(inner);
                                        var result = '';
                                        $.each(el, function (index, item) {
                                            if (item.classList !== undefined && item.classList.contains('user-name')) {
                                                result = result + item.lastChild.firstChild.textContent;
                                            } else if (item.innerText === undefined) {
                                                result = result + item.textContent;
                                            } else result = result + item.innerText;
                                        });
                                        return result;
                                    }
                                }
                            }
                        },
                        {
                            extend: 'excel',
                            text: '<i class="ri-file-excel-line me-1"></i>Excel',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [2, 3, 4, 5],
                                // prevent avatar to be display
                                format: {
                                    body: function (inner, coldex, rowdex) {
                                        if (inner.length <= 0) return inner;
                                        var el = $.parseHTML(inner);
                                        var result = '';
                                        $.each(el, function (index, item) {
                                            if (item.classList !== undefined && item.classList.contains('user-name')) {
                                                result = result + item.lastChild.firstChild.textContent;
                                            } else if (item.innerText === undefined) {
                                                result = result + item.textContent;
                                            } else result = result + item.innerText;
                                        });
                                        return result;
                                    }
                                }
                            }
                        },
                    ]
                }
            ],
            // For responsive popup
            responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal({
                        header: function (row) {
                            var data = row.data();
                            return 'Details of ' + data['full_name'];
                        }
                    }),
                    type: 'column',
                    renderer: function (api, rowIdx, columns) {
                        var data = $.map(columns, function (col, i) {
                            return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
                                ? '<tr data-dt-row="' +
                                col.rowIndex +
                                '" data-dt-column="' +
                                col.columnIndex +
                                '">' +
                                '<td>' +
                                col.title +
                                ':' +
                                '</td> ' +
                                '<td>' +
                                col.data +
                                '</td>' +
                                '</tr>'
                                : '';
                        }).join('');

                        return data ? $('<table class="table"/><tbody />').append(data) : false;
                    }
                }
            },
            initComplete: function () {

            }
        });
        $('.add-new').html(
            "<button class='btn btn-primary waves-effect waves-light add-record'><i class='ri-add-line me-0 me-sm-1 d-inline-block d-sm-none'></i><span class= 'd-none d-sm-inline-block'> 등록 </span ></button>"
        );
    }

    // Delete Record
    $('.add-record').on('click', function () {
        location.href = common.ADD_VIEW_URI;
    });

    $('.datatables-users tbody').on('click', '.delete-record', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: '삭제하시겠습니까?',
            text: '삭제한 데이터는 복구가 불가합니다.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '네 삭제합니다.',
            cancelButtonText: '취소',
            customClass: {
                confirmButton: 'btn btn-primary me-3 waves-effect waves-light',
                cancelButton: 'btn btn-outline-secondary waves-effect'
            },
            buttonsStyling: false
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: common.API_URI + '/admins/' + id,
                    method: 'delete',
                    success: function (json) {
                        if (json.code === 2000) {
                            Swal.fire({
                                title: '삭제 완료',
                                text: '데이터가 삭제되었습니다.',
                                icon: 'success',
                                customClass: {
                                    confirmButton: 'btn btn-success waves-effect'
                                }
                            }).then(function (result) {
                                dt_user.ajax.reload(null, false)
                                // dt_user.row($(this).parents('tr')).remove().draw();
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: ' 오류가 발생했습니다!',
                                icon: 'error',
                                customClass: {
                                    confirmButton: 'btn btn-primary waves-effect waves-light'
                                },
                                buttonsStyling: false
                            }).then(function () {
                                location.reload();
                            });
                        }
                    },
                    error: function (err) {
                        Swal.fire({
                            title: 'Error!',
                            text: ' 오류가 발생했습니다!',
                            icon: 'error',
                            customClass: {
                                confirmButton: 'btn btn-primary waves-effect waves-light'
                            },
                            buttonsStyling: false
                        }).then(function () {
                            location.reload();
                        });
                    }
                });
            }
        });
    });
});