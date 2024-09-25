/**
 * Page User List
 */

'use strict';

document.addEventListener('DOMContentLoaded', function (e) {
    (function () {
        const frm = document.getElementById('frm')
            formValidationSelect2Ele = jQuery(formValidationExamples.querySelector('[name="comp_id"]'));

        console.log(frm)
        const fv = FormValidation.formValidation(frm, {
            fields: {
                id: {
                    validators: {
                        notEmpty: {
                            message: '아이디를 입력하세요.'
                        },
                        stringLength: {
                            min: 6,
                            max: 15,
                            message: '아이디는 6글자 이상 15글자 이하로 입력해주세요.'
                        },
                        regexp: {
                            regexp: /^[a-zA-Z0-9]+$/,
                            message: '아이디는 영문, 숫자만 사용 가능합니다.'
                        }
                    }
                },
                password: {
                    validators: {
                        notEmpty: {
                            message: '비밀번호를 입력하세요.'
                        },
                        stringLength: {
                            min: 8,
                            max: 20,
                            message: '비밀번호는 8글자 이상 20글자 이하로 입력하세요.'
                        },
                    }
                },
                password_confirm: {
                    validators: {
                        notEmpty: {
                            message: '비밀번호를 한번 더 입력하세요.'
                        },
                        identical: {
                            compare: function () {
                                return frm.querySelector('[name="password"]').value;
                            },
                            message: '비밀번호가 일치하지 않습니다.'
                        }
                    }
                },
                name: {
                    validators: {
                        notEmpty: {
                            message: '이름을 입력하세요'
                        },
                        regexp: {
                            regexp: /^[ㄱ-ㅎ|ㅏ-ㅣ|가-힣 ]+$/,
                            message: '한글로 입력하세요.'
                        }
                    }
                },
                email: {
                    validator: {
                        notEmpty: {
                            message: '이메일을 입력하세요'
                        },
                        emailAddress: {
                            message: '이메일 양식에 맞지 않습니다.'
                        }
                    }
                },
                tel: {
                    validator: {
                        notEmpty: {
                            message: '연락처를 입력하세요'
                        },
                        // regexp: {
                        //     regexp: /^[ㄱ-ㅎ|ㅏ-ㅣ|가-힣 ]+$/,
                        //     message: '한글로 입력하세요.'
                        // },
                        // emailAddress: {
                        //     message: '이메일 양식에 맞지 않습니다.'
                        // }
                    }
                },
                comp_id: {
                    validators: {
                        notEmpty: {
                            message: '회사를 선택하세요.'
                        }
                    }
                },
                cert_no: {
                    validators: {
                        notEmpty: {
                            message: '강사 인증번호를 입력하세요.'
                        },
                        stringLength: {
                            min: 4,
                            max: 4,
                            message: '인증번호 4자리로 입력해주세요.'
                        },
                        regexp: {
                            regexp: /^[0-9]+$/,
                            message: '강사 인증번호는 숫자로만 입력하세요.'
                        }
                    }
                },
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap5: new FormValidation.plugins.Bootstrap5({
                    // Use this for enabling/changing valid/invalid class
                    // eleInvalidClass: '',
                    eleValidClass: '',
                    rowSelector: function (field, ele) {
                        // field is the field name & ele is the field element
                        switch (field) {
                            case 'id':
                            case 'password':
                            case 'name':
                            case 'email':
                            case 'tel':
                            case 'comp_id':
                            case 'cert_no':
                            case 'memo':
                                return '.col-md-6';
                            // case 'formValidationPlan':
                            //     return '.col-xl-3';
                            // case 'formValidationSwitch':
                            // case 'formValidationCheckbox':
                            //     return '.col-12';
                            default:
                                return '.row';
                        }
                    }
                }),
                submitButton: new FormValidation.plugins.SubmitButton(),
                // Submit the form when all fields are valid
                defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                autoFocus: new FormValidation.plugins.AutoFocus()
            },
            init: instance => {
                instance.on('plugins.message.placed', function (e) {
                    //* Move the error message out of the `input-group` element
                    if (e.element.parentElement.classList.contains('input-group')) {
                        // `e.field`: The field name
                        // `e.messageElement`: The message element
                        // `e.element`: The field element
                        e.element.parentElement.insertAdjacentElement('afterend', e.messageElement);
                    }
                    //* Move the error message out of the `row` element for custom-options
                    if (e.element.parentElement.parentElement.classList.contains('custom-option')) {
                        e.element.closest('.row').insertAdjacentElement('afterend', e.messageElement);
                    }
                });
            }
        });

        // Select2 (Country)
        if (formValidationSelect2Ele.length) {
            select2Focus(formValidationSelect2Ele);
            formValidationSelect2Ele.wrap('<div class="position-relative"></div>');
            formValidationSelect2Ele
                .select2({
                    placeholder: '회사를 선택하세요.',
                    dropdownParent: formValidationSelect2Ele.parent()
                })
                .on('change', function () {
                    // Revalidate the color field when an option is chosen
                    fv.revalidateField('comp_id');
                });
        }
    })
});

$(function () {
    const phoneMaskList = document.querySelectorAll('.phone-mask'),
        form = document.getElementById('frm');

    // Phone Number
    if (phoneMaskList) {
        phoneMaskList.forEach(function (phoneMask) {
            new Cleave(phoneMask, {
                phone: true,
                phoneRegionCode: 'KR'
            });
        });
    }

    // 회사
    $("#comp_id").select2({
        placeholder: "회사를 선택하세요",
        allowClear: !0,
        ajax: {
            url: common.API_URI + "/companies",
            data: function(t) {
                return t.comp_cd = 'COM001', t
            },
            processResults: function(t) {
                return t.results = $.map(t.data, (function(t) {
                    return t.id = t.comp_id, t.text = t.comp_name, t
                })), t
            }
        },
    });
});