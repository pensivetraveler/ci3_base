let record;
let editors = {};

common.FORM_LIFECYCLE = {
    preparePlugins : false,
    resetFrmInputs : false,
    readyFrmInputs : false,
    fetchFrmValues : false,
    applyFrmValues : false,
    refreshPlugins : false,
    checkFrmValues : false,
    transFrmValues : false,
};

common.FORM_REPEATER = [];

function updateFormLifeCycle(state, form = null, detail = {}) {
    common.FORM_LIFECYCLE[state] = true;

    // Event trigger
    const target = form ?? window;
    if(form) {
        detail = Object.assign({
            formSelector: form.getAttribute('id'),
        }, detail);
    }

    target.dispatchEvent(
        new CustomEvent(state, {
            bubbles : true,
            cancelable : true,
            composed : false,
            detail : detail,
        }),
    );
}

function preparePlugins() {
    // select picker
    if ($('.selectpicker').length) {
        $('.selectpicker').selectpicker();
        handleBootstrapSelectEvents();
    }

    $('body').on('refreshed.bs.select', '.selectpicker', function (e, clickedIndex, isSelected, previousValue) {
        const value = $(this).val();
        let obj = [];
        for(const option of [].slice.call(this.options)) {
            if(!option.classList.contains('bs-title-option')) {
                obj.push({
                    value : option.value,
                    text : option.text,
                })
            }
        }
        $(this).selectpicker('destroy').addClass('selectpicker').selectpicker('val', value);
    });

    // select2
    if ($('.select2').length) {
        $('.select2').each(function () {
            var $this = $(this);
            $this.prepend('<option value="" disabled selected></option>');
            select2Focus($this);
            $this.wrap('<div class="position-relative"></div>').select2({
                allowClear: true,
                placeholder: $this.attr('placeholder'),
                dropdownParent: $this.parent()
            });
        });
    }

    // FlatPickr Initialization & Validation
    document.querySelectorAll('.form-input_date-flatpickr').forEach(function(input) {
        input.flatpickr({
            enableTime: false,
            // See https://flatpickr.js.org/formatting/
            dateFormat: 'Y-m-d',
            // After selecting a date, we need to revalid”ate the field
            onChange: function (data, value, full) {
                $(full.input).trigger('change'); // 'change' 이벤트 강제로 발생
            }
        });
    });

    // Cleave Initialization & Validation
    document.querySelectorAll('.form-input_tel-cleave-hp').forEach(function(input) {
        new Cleave(input, {
            phone: true,
            delimiter: '-',
            phoneRegionCode: 'KR'
        });
    });

    document.querySelectorAll('.form-input_date-cleave-fulldate').forEach(function(input) {
        new Cleave(input, {
            date: true,
            delimiter: '-',
            datePattern: ['Y', 'm', 'd']
        });
    });

    document.querySelectorAll('.form-input_date-cleave-year').forEach(function(input) {
        new Cleave(input, {
            date: true,
            datePattern: ['Y']
        });
    });

    document.querySelectorAll('.form-input_date-cleave-month').forEach(function(input) {
        new Cleave(input, {
            date: true,
            datePattern: ['m']
        });
    });

    document.querySelectorAll('.form-input_date-cleave-date').forEach(function(input) {
        new Cleave(input, {
            date: true,
            datePattern: ['d']
        });
    });

    document.querySelectorAll('.form-input_text-cleave-version').forEach(function(input) {
        new Cleave(input, {
            delimiter: '.',
            blocks: [1, 1, 1],
            uppercase: false,
            numericOnly: true
        });
    });

    document.querySelectorAll('.form-input_time-cleave-time').forEach(function(input) {
        new Cleave(input, {
            time: true,
            timePattern: ['h', 'm']
        });
    });

    document.querySelectorAll('.form-input_time-cleave-hour').forEach(function(input) {
        new Cleave(input, {
            time: true,
            timePattern: ['h']
        });
    });

    document.querySelectorAll('.form-input_time-cleave-minute').forEach(function(input) {
        new Cleave(input, {
            time: true,
            timePattern: ['m']
        });
    });

    // Bootstrap Max Length
    if ($('.form-maxlength').length) {
        $('.form-maxlength').each(function () {
            $(this).maxlength({
                warningClass: 'label label-success bg-success text-white',
                limitReachedClass: 'label label-danger',
                separator: getLocale(' out of ', common.LOCALE),
                preText: getLocale('You typed ', common.LOCALE),
                postText: getLocale(' chars available', common.LOCALE),
                validate: true,
                threshold: +this.getAttribute('maxlength')
            });
        });
    }

    // textarea-autosize
    if($('.form-input_textarea-autosize').length) {
        $('.textarea-autosize').each(function() {
            autosize(this);
        })
    }

    if ($('.select2-repeater').length) {
        $('.select2-repeater').each(function () {
            var $this = $(this);
            $this.prepend('<option value="" disabled selected></option>');
            select2Focus($this);
            $this.wrap('<div class="position-relative"></div>').select2({
                allowClear: true,
                dropdownParent: $this.parent(),
                placeholder: $this.data('placeholder') // for dynamic placeholder
            });
        });
    }

    // form-repeater-jquery
    if($('[data-repeater-type="jquery"]').length) {
        $('[data-repeater-type="jquery"]').each(function() {
            var formType = $(this).data('form-type');
            var groupName = $(this).data('group-name');
            var repeater = common.FORM_REPEATER[groupName] = $(this).repeater({
                initEmpty: false,
                defaultValues: {},
                show: function () {
                    var row = parseInt($(this).closest('[data-repeater-type]').attr('data-repeater-count'))+1;
                    var wrap = this;
                    var withList = false;
                    var formControl = $(this).find('input, select, textarea');
                    var formLabel = $(this).find('.form-label');
                    formControl.each(function (i, item) {
                        var id = `form_${formType}-${groupName}-` + (row-1) + '-' + $(item).data('group-field');

                        // label
                        if(item.nextElementSibling.tagName === 'LABEL') $(item.nextElementSibling).attr('for', id);

                        // list-item-wrap
                        if($(item).data('with-list')) {
                            withList = true;
                            console.log(id+'-list')
                            $(wrap).find(`#${item.id}-list`).attr('id', id+'-list');
                        }

                        $(item).attr('id', id);
                    });

                    $(this).attr('data-row-index', row);
                    $(this).closest('[data-repeater-type]').attr('data-repeater-count', row)

                    // select2-repeater
                    if($(this).find('.select2-repeater').length > 0){
                        if($('.select2-repeater').length > 0){
                            $('.select2-container').remove();
                            $('.select2-repeater.form-select').select2({
                                placeholder: 'Placeholder text'
                            });
                            $('.select2-container').css('width', '100%');
                            var $this = $(this);
                            select2Focus($this);
                            $('.position-relative .select2-repeater').each(function () {
                                $(this).select2({
                                    dropdownParent: $(this).closest('.position-relative')
                                });
                            });
                        }
                    }

                    // list-item-wrap
                    if(withList) $(this).find('.form-list-item-wrap').addClass('d-none').empty();

                    const cbName = camelize(`after_${groupName}_repeater_show`);
                    if(typeof cbName === 'function') window[cbName](this);

                    $(this).slideDown();
                },
                hide: function (deleteElement) {
                    console.log('hi1')
                    const repeater = this;
                    const form = repeater.closest('form');
                    const identifier = form[common.IDENTIFIER].value;
                    if(!identifier) {
                        $(this).slideUp(deleteElement);
                    }else{
                        const wrap = repeater.closest('[data-repeater-type]');
                        if(repeaterId = wrap.getAttribute('data-repeater-id')) {
                            if(repeater.querySelector(`[data-group-field="${repeaterId}"]`).value === ''){
                                $(this).slideUp(deleteElement);
                                return;
                            }
                        }

                        Swal.fire({
                            title: getLocale('Do you really want to delete?', common.LOCALE),
                            text: getLocale('You can\'t undo this action', common.LOCALE),
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: getLocale('delete', common.LOCALE),
                            cancelButtonText: getLocale('cancel', common.LOCALE),
                            customClass: {
                                confirmButton: 'btn btn-primary me-3 waves-effect waves-light',
                                cancelButton: 'btn btn-outline-secondary waves-effect'
                            },
                            buttonsStyling: false
                        }).then(function (result) {
                            if (result.isConfirmed) {
                                deleteRepeater(repeater, deleteElement)
                            }
                        });
                    }
                },
                ready: function(setIndexes) {
                    console.log('Repeater is ready');
                    setIndexes(); // If you need to set index
                },
                isFirstItemUndeletable: false
            });
        })
    }

    /**
     * file list sortable
     */
    if($('.form-list-item-wrap_sorter').length){
        $('.form-list-item-wrap_sorter').each((k, v) => {
            Sortable.create(v, {
                animation: 150,
                group: 'handleList',
                handle: '.drag-handle',
                swap: true,
                onEnd: function (event) {
                    const newIndex = parseInt(event.newIndex)+1;
                    const item = event.item;
                    const articleId = item.getAttribute('data-article-id')
                    const fileId = item.getAttribute('data-file-id')
                    executeAjax({
                        url : common.API_URI + '/reorder',
                        headers : {
                            'Authorization' : common.HOOK_PHPTOJS_VAR_TOKEN,
                        },
                        method : 'patch',
                        data : {
                            new_index : newIndex,
                            article_id : articleId,
                            file_id : fileId,
                        },
                        success: function(response) {
                            showAlert({
                                type: 'success',
                                title: 'Complete',
                                text: response.msg,
                            });
                        },
                    });
                }
            });
        })
    }

    updateFormLifeCycle('preparePlugins')
}

/**
 * resetFrmInputs
 * - form mode 의 값에 관계없이 form input 들에 대한 처리
 * @param form
 * @param fields
 */
function resetFrmInputs(form, fields = []) {
    form.querySelectorAll('input, textarea, select').forEach(function(node) {
        if(!Boolean(node.getAttribute('data-reset-value'))) {
            return;
        }

        const value = fields.reduce((acc, curr) => {
            if(curr.field === node.name) acc = curr.default;
            return acc;
        }, '');

        switch (node.tagName) {
            case 'INPUT' :
                switch(node.type) {
                    case 'radio' :
                    case 'checkbox' :
                        node.checked = false;
                        break;
                    case 'text' :
                    case 'date' :
                    case 'tel' :
                    case 'number' :
                    case 'hidden' :
                    case 'password' :
                        node.value = value;
                        break;
                    case 'file' :
                        const newNode = node.cloneNode(true);
                        node.parentNode.replaceChild(newNode, node);
                }
                break;
            case 'SELECT' :
                node.value = value;
                break;
            case 'TEXTAREA' :
                node.value = value;
                break;
        }

        if(node.type !== 'hidden' && !(Boolean(node.getAttribute('data-detect-changed')))) {
            node.setAttribute('data-input-changed', 'false');
        }

        if(node.hasAttribute('data-original-value')){
            node.removeAttribute('data-original-value');
            if(btn = document.querySelector(`[data-id="${node.name}"].btn-dup-check`)){
                btn.removeAttribute('disabled');
            }
        }
    });

    // list 초기화
    fields.map(function(item) {
        if(list = document.querySelector('#'+item.id+'-list')) {
            list.innerHTML = '';
            list.classList.add('d-none');
        }
    })

    // repeater 초기화
    if($('[data-repeater-type="jquery"]').length) {
        $('[data-repeater-type="jquery"]').attr('data-repeater-count', 1);
    }

    // Event trigger
    updateFormLifeCycle('resetFrmInputs', form);
}

/**
 * readyFrmInputs
 * - form mode 에 따라 변경되는 input 처리
 * @param form
 * @param mode
 * @param fields
 */
function readyFrmInputs(form, mode, fields = []) {
    resetFrmInputs(form, fields);

    form.querySelectorAll('[data-view-mod]').forEach((node) => {
        const modList = node.getAttribute('data-view-mod').split('|');
        if(modList.includes(mode)){
            node.closest('.form-validation-row').classList.remove('d-none');
        }else{
            node.closest('.form-validation-row').classList.add('d-none');
        }
    });

    if(form.mode !== undefined) form.mode.value = mode;

    // Event trigger
    updateFormLifeCycle('readyFrmInputs', form, {
        mode : mode,
    });
}

/**
 * fetchFrmValues
 * - ajax 통신으로 server data를 fetch 및 data return
 * @param form
 * @param key
 * @returns {*}
 */
function fetchFrmValues(form = null, key = '') {
    let data;

    executeAjax({
        async: false,
        url : common.API_URI + '/' + key + '?' + new URLSearchParams(common.API_PARAMS).toString(),
        headers: {
            'Authorization' : common.HOOK_PHPTOJS_VAR_TOKEN,
        },
        success: function(response, textStatus, jqXHR) {
            data = response.data[0];
            if(form) {
                record = data;

                // Event trigger
                updateFormLifeCycle('fetchFrmValues', form, {
                    record : data,
                });
            }
        },
    });

    return data;
}

/**
 * applyFrmValues
 * - fetchFrmValues 로부터 전달받은 데이터를 각 input 에 입력
 * @param form
 * @param data
 * @param fields
 */
function applyFrmValues(form, data, fields = []) {
    if(!fields.length) return;

    // 단순 복제로 원본 fields object(common.FORM_DATA)에 대해서도
    // 변경이 반영되는 것을 방지하기 위해 깊은 복제 실행.
    const cloneFields = structuredClone(fields);

    const initGroupProperties = () => {
        return {
            inputs : [],
            selects : [],
            checkboxes : [],
            radios : [],
            textareas : [],
            excepts : [],
            files : [],
            customs : [],
        };
    };
    const groups = {
        basic : initGroupProperties(),
    };
    const groupAttrs = {
        basic : {
            group_name : 'basic',
        }
    };


    /**
     * classify cloneFields into groups
     */
    cloneFields.forEach((item) => {
        if(item.group && !groups.hasOwnProperty(item.group)) {
            groups[item.group] = initGroupProperties();
            groupAttrs[item.group] = Object.assign(item.group_attributes, {
                group_name : item.group,
            });
        }
        const groupName = item.group ? item.group : 'basic';
        if(item.category === 'custom') {
            groups[groupName].customs.push(item.field);
        }else{
            switch (item.type) {
                case 'select' : groups[groupName].selects.push(item.field); break;
                case 'checkbox' : groups[groupName].checkboxes.push(item.field); break;
                case 'radio' : groups[groupName].radios.push(item.field); break;
                case 'textarea' : groups[groupName].textareas.push(item.field); break;
                case 'file' : groups[groupName].files.push(item.field); break;
                case 'custom' : groups[groupName].customs.push(item.field); break;
                default :
                    if(['password'].includes(item.type)) {
                        groups[groupName].excepts.push(item.field);
                    }else{
                        groups[groupName].inputs.push(item.field);
                    }
            }
        }
    });

    Object.keys(groups).forEach((groupName, i) => {
        const dto = groups[groupName];

        if(groupAttrs[groupName].group_repeater) {
            if(groupAttrs[groupName].repeater_type === 'jquery' && $('[data-repeater-type="jquery"]').length) {
                const repeater = document.querySelector(`[data-repeater-type="jquery"][data-group-name="${groupName}"]`);
                const rowCount = parseInt(repeater.getAttribute('data-repeater-count'));
                for(let i = rowCount+1; i <= data[groupName].length; i++) repeater.querySelector('[data-repeater-create]').click();
                repeater.setAttribute('data-repeater-count', data[groupName].length === 0 ? 1 : data[groupName].length);
            }
        }

        Object.keys(dto).map((category) => {
            if(dto[category].length > 0) {
                if(groupName === 'basic') {
                    dto[category].forEach((fieldName) => {
                        applyFrmValuesByCategory(category, groupAttrs[groupName], fieldName, cloneFields, form, data);
                    });
                }else{
                    if(!data.hasOwnProperty(groupName)) return;

                    if(groupAttrs[groupName].envelope_name) {
                        let frmData = data[groupName];
                        if(groupAttrs[groupName].group_repeater) {
                            // 데이터 개수에 따라, field의 id, name 을 data index 의 값으로 대체해야 함.
                            for(let dataIndex = 0; dataIndex < data[groupName].length; dataIndex++) {
                                Object.keys(data[groupName][dataIndex]).map((fieldName) => {
                                    if(!dto[category].includes(fieldName)) return;
                                    if(common.IDENTIFIER) frmData[dataIndex][common.IDENTIFIER] = data[common.IDENTIFIER];
                                    applyFrmValuesByCategory(category, groupAttrs[groupName], fieldName, cloneFields, form, frmData[dataIndex], dataIndex);
                                });
                            }
                        }else{
                            if(common.IDENTIFIER) frmData[common.IDENTIFIER] = data[common.IDENTIFIER];
                            dto[category].forEach((fieldName) => {
                                applyFrmValuesByCategory(category, groupAttrs[groupName], fieldName, cloneFields, form, frmData);
                            });
                        }
                    }else{
                        dto[category].forEach((fieldName) => {
                            applyFrmValuesByCategory(category, groupAttrs[groupName], fieldName, cloneFields, form, data);
                        });
                    }
                }
            }
        });
    });
}

function getFrmInputDto(groupAttrs, field, dataIndex = 0) {
    const groupName = groupAttrs.group_name === 'basic'?'':groupAttrs.group_name;

    if(!groupName) {
        return field;
    }else{
        if(!groupAttrs.group_repeater) {
            return field;
        }else{
            let regexId, regexName;
            if(groupAttrs.envelope_name) {
                regexId = new RegExp(`(-${groupName}-)\\d+(-${field.field})`);
                field.id = field.id.replace(regexId, `$1${dataIndex}$2`);

                regexName = new RegExp(`(${groupName}\\[)\\d+(\\]\\[${field.field}\\])`);
                field.name = field.name.replace(regexName, `$1${dataIndex}$2`);
            }else{
                regexId = new RegExp(`(-${field.field}-)\\d+`);
                field.id = field.id.replace(regex, `$1${dataIndex}`);

                regexName = new RegExp(`(${listName}\\[)\\d+(\\])`);
                field.name = field.name.replace(regexName, `$1${dataIndex}$2`);
            }
            return field;
        }
    }
}

function applyFrmValuesByCategory(category, groupAttr, fieldName, fields, form, data, dataIndex = 0) {
    if(!data) return;
    const groupName = groupAttr.group_name === 'basic'?'':groupAttr.group_name;
    const fieldIndex = fields.findIndex(item => item.field === fieldName && item.group === groupName);
    if(fieldIndex < 0) return;
    const field = getFrmInputDto(groupAttr, fields[fieldIndex], dataIndex);

    const name = field.name;
    const id = field.id;

    switch (category) {
        case 'inputs' :
            switch (field.subtype) {
                default:
                    if(form[name] && data[fieldName]) form[name].value = data[fieldName];
            }
            break;
        case 'selects' :
            switch (field.subtype) {
                default:
                    if(form[name] && data[fieldName]) form[name].value = data[fieldName];
            }
            break;
        case 'checkboxes' :
            switch (field.subtype) {
                case 'single' :
                    if(!data[fieldName]) return;
                    form.querySelectorAll(`[name="${name}"]`).forEach((input) => {
                        if(input.value == data[fieldName]) input.checked = true;
                    });
                    break;
                default:
                    if(!data[fieldName]) return;
                    form.querySelectorAll(`[name="${name}[]"]`).forEach((input) => {
                        if(typeof data[fieldName] === 'string') {
                            if(data[fieldName].split(',').includes(input.value)) input.checked = true;
                        }else{
                            if(data[fieldName].includes(input.value)) input.checked = true;
                        }
                    });
            }
            break;
        case 'radios' :
            switch (field.subtype) {
                default:
                    if(!data[fieldName]) return;
                    form.querySelectorAll(`[name="${name}"]`).forEach((input) => {
                        if(input.value == data[fieldName]) input.checked = true;
                    });
            }
            break;
        case 'textareas' :
            switch (field.subtype) {
                default:
                    if(form[name] && data[fieldName]) form[name].value = data[fieldName];
            }
            break;
        case 'files' :
            switch (field.subtype) {
                case 'basic' :
                case 'thumbnail' :
                case 'single' :
                case 'multiple' :
                case 'readonly' :
                    // setFormListItem(`#${id}-list`, data[fieldName], field);
                    break;
                default :
                    if(data[fieldName] === undefined) console.warn(`applyFrmValues : data doesn't have '${name}'.`);
                    if(form[name] === undefined) console.warn(`applyFrmValues : form doesn't have '${name}'.`);
                    if(data[fieldName] !== undefined && form[name] !== undefined)
                        form[name].value = data[fieldName];
                    break;
            }
            break;
        case 'customs' :
            switch (field.subtype) {
                case 'youtube' :
                    // setFormListItem(`#${id}-list`, data[fieldName], field);
                    break;
                case 'quill' :
                    document.getElementById(`${id}-quill`).innerHTML = data[fieldName];
                    form[name].value = data[fieldName];
                    break;
                case 'reply_list' :
                    // setFormListItem(`#${id}`, data[fieldName], field);
                    break;
                default :
                    if(data[fieldName] === undefined) console.warn(`applyFrmValues : data doesn't have '${name}'.`);
                    if(form[name] === undefined) console.warn(`applyFrmValues : form doesn't have '${name}'.`);
                    if(data[fieldName] !== undefined && form[name] !== undefined)
                        form[name].value = data[fieldName];
                    break;
            }
            break;
    }

    if(field.form_attributes.with_list) {
        setFormListItem(`#${id}-list`, data, field);
    }
}

function refreshPlugins() {
    // Select Picker
    if($('.selectpicker').length) $('.selectpicker').selectpicker('refresh');

    // Select2
    if($('.select2').length) $('.select2').trigger('change');
    if($('.select2-repeater').length) $('.select2-repeater').trigger('change');

    // textarea-autosize
    if($('.textarea-autosize').length) {
        $('.textarea-autosize').each(function() {
            if(this.scrollHeight > this.clientHeight) {
                //textarea height 확장
                this.style.height = this.scrollHeight + "px";
            }else{
                //textarea height 축소
                this.style.removeProperty('height');
                autosize(this);
                this.style.height = this.scrollHeight + "px";
            }
        })
    }

    // textarea-quill
    if($('.textarea-quill').length) {
        $('.textarea-quill').each(function() {
            editors[this.id] = new Quill(`#${this.id}`, {
                bounds: `#${this.id}`,
                placeholder: 'Type Something...',
                modules: {
                    formula: true,
                    toolbar: toolbarDefault.quill
                },
                theme: 'snow'
            });

            $(`#${this.id}`).on('keydown', function(e) {
                $(`[data-textarea-id="${this.id}"]`).val(editors[this.id].root.innerHTML)
            });
        })
    }


    updateFormLifeCycle('refreshPlugins');
}

function checkDuplicate(button) {
    try {
        const fieldName = button.getAttribute('data-rel-field');
        if(!fieldName) throw new Error(`checkDuplicate : fieldName is not defined !`);

        const form = button.closest('form');
        if(!form.hasOwnProperty(fieldName)) throw new Error(`checkDuplicate : fieldName is not valid !`);

        const input = form[fieldName];
        const value = input.value;
        const originalValue = input.getAttribute('data-original-value');

        // 같은 값인 경우 중복 체크 하지 않음.
        if(originalValue && originalValue === value) return;

        fv.revalidateField(input.name).then((status) => {
            if(status === 'Valid') {
                executeAjax({
                    url: common.API_URI + '/checkDuplicate',
                    headers: {
                        'Authorization' : common.HOOK_PHPTOJS_VAR_TOKEN,
                    },
                    data: {
                        field: fieldName,
                        value: input.value,
                    },
                    after: {
                        callback: showAlert,
                        params: {
                            type: 'success',
                        },
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.warn(jqXHR.responseJSON)
                        showAlert({
                            type: 'warning',
                            text: jqXHR.responseJSON.msg,
                        });
                    }
                });
            }
        });
    } catch (error) {
        customErrorHandler(error);
    }
}

function downloadFile(fileId) {
    const url = location.origin+location.pathname;
    location.href = url+'/downloader/'+fileId;
}

function deleteFile(btn, type = '') {
    const form = repeater.closest('form');
    const identifier = form[common.IDENTIFIER].value;

    const listWrap = btn.closest('ul');
    const itemWrap = btn.closest('.form-list-item');
    if(!itemWrap.hasAttribute('data-full-item')) return;
    const item = JSON.parse(itemWrap.getAttribute('data-full-item').replace(/'/g, '"'));

    executeAjax({
        url : common.API_URI + '/deleteFile/' + identifier + (type ? '?type='+type : ''),
        headers : {
            'Authorization' : common.HOOK_PHPTOJS_VAR_TOKEN,
        },
        method : 'patch',
        data : item,
        success: function(response) {
            console.log(response)
            showAlert({
                type: 'success',
                title: 'Complete',
                text: response.msg,
            });
            itemWrap.remove();
            if(!listWrap.children.length) listWrap.classList.add('d-none');
        },
    });
}

function deleteRepeater(repeater, deleteElement) {
    const data = {};
    $(repeater).find('input, select, textarea').each(function(i , item) {
        if(item.type === 'file') return;
        data[item.getAttribute('data-group-field')] = item.value;
    });

    executeAjax({
        url : common.API_URI + '/deleteRepeater/' + identifier,
        headers : {
            'Authorization' : common.HOOK_PHPTOJS_VAR_TOKEN,
        },
        method : 'patch',
        data : data,
        success: function(response) {
            console.log(response)
            showAlert({
                type: 'success',
                title: 'Complete',
                text: response.msg,
            });

            $(repeater).slideUp(deleteElement)
        },
    });
}

function initializeForm() {
    record = null;
    Object.keys(common.FORM_LIFECYCLE).forEach((v, k) => {
        if(!k) return;
        common.FORM_LIFECYCLE[v] = false;
    });
}

$(function() {
    preparePlugins();

    // input event
    $('[data-dup-check]').on('input', function(e) {
        const btn = document.querySelector(`[data-id="${this.name}"].btn-dup-check`);
        if(btn && this.getAttribute('data-original-value')) {
            if(this.value === this.getAttribute('data-original-value')){
                btn.setAttribute('disabled', 'disabled');
            }else{
                btn.removeAttribute('disabled');
            }
        }
    });

    // $('body').on('input', 'input, textarea', function(e) {
    //     if($(this).attr('max')) {
    //         const max = parseInt(this.getAttribute('max'));
    //         if(max > 0 && this.value.length > max) {
    //             this.value = this.value.substring(0, max);
    //             $(this).siblings('.form-text').text(`${max}글자 이하 입력해주세요.`).removeClass('d-none').focus();
    //         }else{
    //             $(this).siblings('.form-text').addClass('d-none');
    //         }
    //     }
    //
    //     if($(this).data('add-hypen')) {
    //         if(this.value.length >= 9) this.value = this.value.replace(/[^0-9]/g, "").replace(/^(\d{2,3})(\d{3,4})(\d{4})$/, `$1-$2-$3`);
    //     }
    //
    //     if($(this).data('text-type')) {
    //         const type = $(this).data('text-type').split('|');
    //         var regex;
    //         if(type.includes('eng') && type.includes('num')) {
    //             regex = /[^0-9a-zA-Z]/g;
    //         } else if (type.includes('eng')) {
    //             regex = /[^a-zA-Z]/g;
    //         } else if (type.includes('num')) {
    //             regex = /[^0-9]/g;
    //         }
    //         this.value = this.value.replace(regex, "");
    //     }
    // });

    // $('body').on('change', 'input, textarea, select', function(e) {
    //     if($(this).attr('minlength')) {
    //         const minlength = parseInt(this.getAttribute('minlength'));
    //         if(minlength > 0 && this.value.length < minlength) {
    //             $(this).siblings('.form-text').text(`${minlength}글자 이상 입력해주세요.`).removeClass('d-none').focus();
    //         }else{
    //             $(this).siblings('.form-text').addClass('d-none');
    //         }
    //     }
    //
    //     if($(this).data('dup-check')){
    //         let params;
    //         try {
    //             params = JSON.parse($(this).data('dup-check'))
    //         } catch (e) {
    //             params = JSON.parse($(this).data('dup-check').replace(/'/g, '"'));
    //         }
    //
    //         const value = $(this).val();
    //         const formText = $(this).siblings('.form-text');
    //         $.ajax({
    //             url: `${common.API_URI}/auth/dupCheck`,
    //             data: {key: params.key, value: value},
    //             headers: {
    //                 'Authorization' : common.HOOK_PHPTOJS_VAR_TOKEN,
    //             },
    //             method: 'get',
    //             dataType: 'json',
    //             success: function(json) {
    //                 console.log(json)
    //                 if(json.code === 2000) {
    //                     formText.addClass('d-none');
    //                 }else{
    //                     formText.text(json.msg).removeClass('d-none');
    //                 }
    //             },
    //             error: function(jqXHR, textStatus, errorThrown) {
    //                 let msg = jqXHR.responseJSON.msg;
    //                 if(jqXHR.responseJSON.code === 4090 && param.title) msg = msg.replace('데이터가', Josa.r(param.title, '이/가'));
    //                 formText.text(msg).removeClass('d-none').focus();
    //             }
    //         });
    //     }
    // });
})