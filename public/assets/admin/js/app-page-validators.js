const customValidatorsPreset = {
    inflector: function(name) {
        let res = '';
        if(this.rules.hasOwnProperty(name) && this.rules[name].hasOwnProperty('validatorName')){
            res = this.rules[name].validatorName;
        }
        return res;
    },
    extractor : function(regex, rule) {
        const regexp = new RegExp(regex);
        return rule.match(regexp);
    },
    rules : {
        exact_length : {
            regex : '^exact_length\\[(\\d+)\\]$',
            options : function(form, item, matches) {
                return {
                    min: parseInt(matches[1]),
                    max: parseInt(matches[1]),
                };
            },
            validatorName : 'stringLength',
        },
        min_length : {
            regex : '^min_length\\[(\\d+)\\]$',
            options : function(form, item, matches) {
                return {
                    min: parseInt(matches[1])
                }
            },
            validatorName : 'stringLength',
        },
        max_length : {
            regex : '^max_length\\[(\\d+)\\]$',
            options : function(form, item, matches) {
                return {
                    max: parseInt(matches[1]),
                };
            },
            validatorName : 'stringLength',
        },
        min : {
            regex : '^min\\[(\\d+)\\]$',
            options : function(form, item, matches) {
                return {
                    min: parseInt(matches[1]),
                };
            },
            validatorName : 'options',
        },
        max : {
            regex : '^max\\[(\\d+)\\]$',
            options : function(form, item, matches) {
                return {
                    max: parseInt(matches[1]),
                };
            },
            validatorName : 'options',
        },
        matches : {
            regex : '^matches\\[(.*?)\\]$',
            options : function(form, item, matches) {
                return {
                    compare: function() {
                        return form.querySelector(`[name="${matches[1]}"]`).value;
                    },
                };
            },
            validatorName : 'identical',
        },
        required_if_empty_data : {
            regex : '^required_if_empty_data\\[(.*?)\\]$',
            options : function(form, item, matches) {
                return {
                    listId: `${form.querySelector(item.selector).id}-list`,
                };
            },
            validatorName : 'requiredIfEmpty',
        },
        required_if_empty_file : {
            regex : '^required_if_empty_file\\[(.*?)\\]$',
            options : function(form, item, matches) {
                return {
                    listId: `${form.querySelector(item.selector).id}-list`,
                };
            },
            validatorName : 'requiredIfEmpty',
        },
        required_if_article_file_empty : {
            regex : '^required_if_article_file_empty$',
            options : function(form, item, matches) {
                return {
                    listId: `${form.querySelector(item.selector).id}-list`,
                };
            },
            validatorName : 'requiredIfEmpty',
        },
        required_mod : {
            regex : '^required_mod\\[(\\w+)\\]$',
            options : function(form, item, matches) {
                return {
                    mod: matches[1],
                };
            },
            validatorName : 'requiredMod',
        },
        is_numeric : {
            regex : '',
            options : function(form, item, matches) {
                return {};
            },
            validatorName : 'isNumeric',
        },
        max_files : {
            regex : '^max_files\\[(.*?)\\]$',
            options : function(form, item, matches) {
                const max = matches[1].split('|')[0];
                return {
                    listId: `${form.querySelector(item.selector).id}-list`,
                    max : matches[1].split('|')[0],
                };
            },
            validatorName : 'checkFileCounts',
        },
    },
    validators : {
        requiredMod : function() {
            return {
                validate: function(input) {
                    const mode = document.getElementById('formRecord').mode.value;
                    const required = input.options.mod.split('|').includes(mode);
                    if(required) {
                        return {
                            valid : input.value !== null && input.value !== '' && input.value.trim() !== '',
                        };
                    }else{
                        return {
                            valid : true,
                        };
                    }
                }
            }
        },
        requiredIfEmpty : function() {
            return {
                validate: function(input) {
                    const list = document.getElementById(input.options.listId);
                    if(list.children.length > 0) {
                        return {
                            valid : true,
                        }
                    }else{
                        return {
                            valid : input.value !== null && input.value !== '' && input.value.trim() !== '',
                        };
                    }
                }
            }
        },
        isNumeric : function() {
            return {
                validate: function(input) {
                    let valid = false;
                    // 값이 문자열일 경우, 숫자로 변환할 수 있는지 확인
                    if (typeof input.value === 'string') valid = /^-?\d*\.?\d+$/.test(input.value);
                    // 값이 숫자일 경우, 문자열로 변환 후 검사
                    if (typeof input.value === 'number') valid = /^-?\d*\.?\d+$/.test(input.value.toString());
                    return {valid: valid};
                }
            }
        },
        checkFileCounts : function() {
            return {
                validate: function(input) {
                    const list = document.getElementById(input.options.listId);
                    const max = parseInt(input.options.max);
                    if(list.children.length < max) {
                        return {
                            valid: true,
                        }
                    }else{
                        return {
                            valid: false,
                        }
                    }
                }
            }
        }
    },
};