/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_MpAdvancedBookingSystem
 * @author    Webkul Software Private Limited
 * @copyright Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */

define([
    'jquery',
    'mage/template',
    'Magento_Ui/js/modal/alert',
    'jquery/ui',
    'useDefault',
    'collapsable',
    'mage/translate',
    'mage/backend/validation',
    'Magento_Ui/js/modal/modal'
], function ($, mageTemplate, alert) {
    'use strict';

    $.widget('mage.customOptions', {
        options: {
            selectionItemCount: {},
        },

        /** @inheritdoc */
        _create: function () {
            this.baseTmpl = mageTemplate('#custom-option-base-template');
            this.rowTmpl = mageTemplate('#custom-option-select-type-row-template');

            this._initOptionBoxes();
            this._initSortableSelections();
            this._bindCheckboxHandlers();
            this._bindReadOnlyMode();
            this._addValidation();
            this.addOptionManually();
        },

        /**
         * @private
         */
        _addValidation: function () {
            $.validator.addMethod(
                'required-option-select',
                function (value) {
                    return value !== '';
                },
                $.mage.__('Select type of option.')
            );

            $.validator.addMethod(
                'required-option-select-type-rows',
                function (value, element) {
                    var optionContainerElm = element.up('div[id*=_type_]'),
                        selectTypesFlag = false,
                        selectTypeElements = $('#' + optionContainerElm.id + ' .select-type-title');

                    selectTypeElements.each(function () {
                        if (!$(this).closest('tr').hasClass('ignore-validate')) {
                            selectTypesFlag = true;
                        }
                    });

                    return selectTypesFlag;
                },
                $.mage.__('Please add rows to option.')
            );
        },

        /**
         * @private
         */
        _initOptionBoxes: function () {
            var syncOptionTitle;

            if (!this.options.isReadonly) {
                this.element.sortable({
                    axis: 'y',
                    handle: '[data-role=draggable-handle]',
                    items: '#product_options_container_top > div',
                    update: this._updateOptionBoxPositions,
                    tolerance: 'pointer'
                });
            }

            /**
             * @param {jQuery.Event} event
             */
            syncOptionTitle = function (event) {
                var currentValue = $(event.target).val(),
                    optionBoxTitle = $(
                        '.admin__collapsible-title > span',
                        $(event.target).closest('.fieldset-wrapper')
                    ),
                    newOptionTitle = $.mage.__('New Ticket Type');

                optionBoxTitle.text(currentValue === '' ? newOptionTitle : currentValue);
            };
            this._on({
                /**
                 * Reset field value to Default
                 */
                'click .use-default-label': function (event) {
                    $(event.target).closest('label').find('input').prop('checked', true).trigger('change');
                },

                /**
                 * Remove custom option or option row for 'select' type of custom option
                 */
                'click button[id^=product_option_][id$=_delete]': function (event) {
                    var element = $(event.target).closest('#product_options_container_top > div.fieldset-wrapper .fieldset.new-ticket-type-wrapper');
                    if (element.length
                        && element.siblings('.fieldset.new-ticket-type-wrapper:not(.ignore-validate)').length
                    ) {
                        // $('#product_' + element.attr('id').replace('product_', '') + '_is_delete').val(1);
                        $('#' + $(event.target).attr('id').replace('_delete', '_is_delete')).val(1);
                        element.addClass('ignore-validate').hide();
                        this.refreshSortableElements();
                    }
                },

                /**
                 * Minimize custom option block
                 */
                'click #product_options_container_top [data-target$=-content]': function () {
                    if (this.options.isReadonly) {
                        return false;
                    }
                },

                /**
                 * Add new row
                 */
                'click #add_new_defined_option': function (event) {
                    this.addNewTicketType(event);
                },

                /**
                 * Add new option row for 'select' type of custom option
                 */
                /* 'click button[id^=product_option_][id$=_add_select_row]': function (event) {
                    this.addSelection(event);
                }, */

                /**
                 * Import custom options from products
                 */
                'click #import_new_defined_option': function () {
                    var importContainer = $('#import-container'),
                        widget = this;

                    importContainer.modal({
                        title: $.mage.__('Select Product'),
                        type: 'slide',

                        /** @inheritdoc */
                        opened: function () {
                            $(document).off().on('click', '#productGrid_massaction-form button', function () {
                                $('.import-custom-options-apply-button').trigger('click', 'massActionTrigger');
                            });
                        },
                        buttons: [{
                            text: $.mage.__('Import'),
                            attr: {
                                id: 'import-custom-options-apply-button'
                            },
                            'class': 'action-primary action-import import-custom-options-apply-button',

                            /** @inheritdoc */
                            click: function (event, massActionTrigger) {
                                var request = [];

                                $(this.element).find('input[name=product]:checked').map(function () {
                                    request.push(this.value);
                                });

                                if (request.length === 0) {
                                    if (!massActionTrigger) {
                                        alert({
                                            content: $.mage.__('Please select items.')
                                        });
                                    }

                                    return;
                                }

                                $.post(widget.options.customOptionsUrl, {
                                    'products[]': request,
                                    'form_key': widget.options.formKey
                                }, function ($data) {
                                    $.parseJSON($data).each(function (el) {
                                        var i;

                                        el.id = widget.getFreeOptionId(el.id);
                                        el['option_id'] = el.id;

                                        if (typeof el.optionValues !== 'undefined') {
                                            for (i = 0; i < el.optionValues.length; i++) {
                                                el.optionValues[i]['option_id'] = el.id;
                                            }
                                        }
                                        //Adding option
                                        widget.addOption(el);
                                        //Will save new option on server side
                                        $('#product_option_' + el.id + '_option_id').val(0);
                                        //$('#option_' + el.id + ' input[name$="option_type_id]"]').val(-1);
                                    });
                                    importContainer.modal('closeModal');
                                });
                            }
                        }]
                    });
                    importContainer.load(
                        this.options.productGridUrl,
                        {
                            'form_key': this.options.formKey,
                            'current_product_id': this.options.currentProductId
                        },
                        function () {
                            importContainer.modal('openModal');
                        }
                    );
                },

                /**
                 * Change custom option type
                 */
                'change select[id^=product_option_][id$=_type]': function (event, data) {
                    var widget = this,
                        currentElement = $(event.target),
                        parentId = '#' + currentElement.closest('.fieldset-alt').attr('id'),
                        group = currentElement.find('[value="' + currentElement.val() + '"]')
                            .closest('optgroup').attr('data-optgroup-name'),
                        tmpl, disabledBlock, priceType;

                    data = data || {};

                    if (typeof group !== 'undefined') {
                        group = group.toLowerCase();
                    }

                    if (group=="select") {
                        if (typeof group === 'undefined') {
                            return;
                        }
                        disabledBlock = $(parentId).find(parentId + '_type_' + group);

                        if (disabledBlock.length) {
                            disabledBlock.removeClass('ignore-validate').show();
                        } else {
                            if ($.isEmptyObject(data)) { //eslint-disable-line max-depth
                                data['option_id'] = $(parentId + '_id').val();
                                data.price = data.sku = '';
                            }
                            data.group = group;
                            data.select_id = data.id;
                            this.options.selectionItemCount[data.id] = data.id + 1;
                            tmpl = widget.element.find('#custom-option-' + group + '-type-template').html();
                            tmpl = mageTemplate(tmpl, {
                                data: data
                            });

                            $(tmpl).insertAfter($(parentId));

                            if (data['price_type']) { //eslint-disable-line max-depth
                                priceType = $('#' + widget.options.fieldId + '_' + data['option_id'] + '_price_type');
                                priceType.val(data['price_type']).attr('data-store-label', data['price_type']);
                            }
                            this._bindUseDefault(widget.options.fieldId + '_' + data['option_id'], data);
                        }
                    }
                },
                //Sync title
                'change .field-option-title > .control > input[id$="_title"]': syncOptionTitle,
                'keyup .field-option-title > .control > input[id$="_title"]': syncOptionTitle,
                'paste .field-option-title > .control > input[id$="_title"]': syncOptionTitle
            });
        },

        /**
         * @private
         */
        _initSortableSelections: function () {
            if (!this.options.isReadonly) {
                this.element.find('[id^=product_option_][id$=_type_select] tbody').sortable({
                    axis: 'y',
                    handle: '[data-role=draggable-handle]',

                    /** @inheritdoc */
                    helper: function (event, ui) {
                        ui.children().each(function () {
                            $(this).width($(this).width());
                        });

                        return ui;
                    },
                    update: this._updateSelectionsPositions,
                    tolerance: 'pointer'
                });
            }
        },

        /**
         * Sync sort order checkbox with hidden dropdown
         */
        _bindCheckboxHandlers: function () {
            this._on({
                /**
                 * @param {jQuery.Event} event
                 */
                'change [id^=product_option_][id$=_required]': function (event) {
                    var $this = $(event.target);

                    $this.closest('#product_options_container_top > div')
                        .find('[name$="[is_require]"]').val($this.is(':checked') ? 1 : 0);
                }
            });
            this.element.find('[id^=product_option_][id$=_required]').each(function () {
                $(this).prop('checked', $(this).closest('#product_options_container_top > div')
                    .find('[name$="[is_require]"]').val() > 0);
            });
        },

        /**
         * Update Custom option position
         */
        _updateOptionBoxPositions: function () {
            $(this).find('div[id^=option_]:not(.ignore-validate) .fieldset-alt > [name$="[sort_order]"]').each(
                function (index) {
                    $(this).val(index);
                }
            );
        },

        /**
         * Update selections positions for 'select' type of custom option
         */
        _updateSelectionsPositions: function () {
            $(this).find('tr:not(.ignore-validate) [name$="[sort_order]"]').each(function (index) {
                $(this).val(index);
            });
        },

        /**
         * Disable input data if "Read Only"
         */
        _bindReadOnlyMode: function () {
            if (this.options.isReadonly) {
                $('div.booking-product-custom-options').find('button,input,select,textarea').each(function () {
                    $(this).prop('disabled', true);

                    if ($(this).is('button')) {
                        $(this).addClass('disabled');
                    }
                });
            }
        },

        /**
         * @param {String} id
         * @param {Object} data
         * @private
         */
        _bindUseDefault: function (id, data) {
            var title = $('#' + id + '_title'),
                price = $('#' + id + '_price'),
                priceType = $('#' + id + '_price_type');

            //enable 'use default' link for title
            if (data.checkboxScopeTitle) {
                title.useDefault({
                    field: '.field',
                    useDefault: 'label[for$=_title]',
                    checkbox: 'input[id$=_title_use_default]',
                    label: 'span'
                });
            }
            //enable 'use default' link for price and price_type
            if (data.checkboxScopePrice) {
                price.useDefault({
                    field: '.field',
                    useDefault: 'label[for$=_price]',
                    checkbox: 'input[id$=_price_use_default]',
                    label: 'span'
                });
                //not work set default value for second field
                priceType.useDefault({
                    field: '.field',
                    useDefault: 'label[for$=_price]',
                    checkbox: 'input[id$=_price_use_default]',
                    label: 'span'
                });
            }
        },

        addNewTicketType: function (event) {
            var data = {};
            data.type = '';
            data['option_id'] = 0;
            data.type = "multiple";

            data.id = $('#product_options_container_top > div')
                .find('[name^="product[options]"][name$="[data-id]"]').val();

            if (!this.options.selectionItemCount[data.id]) {
                this.options.selectionItemCount[data.id] = 1;
            }

            var widget = this,
                parentId = '#' + $('.fieldset-alt').attr('id'),
                group = "select",
                tmpl, disabledBlock, priceType;

            data = data || {};
            if (typeof group !== 'undefined') {
                group = group.toLowerCase();
            }
            
            if (group == "select") {
                if (typeof group === 'undefined') {
                    return;
                }
                disabledBlock = $(parentId).find(parentId + '_type_' + group);

                if (disabledBlock.length) {
                    disabledBlock.removeClass('ignore-validate').show();
                } else {
                    if ($.isEmptyObject(data)) { //eslint-disable-line max-depth
                        data['option_id'] = $(parentId + '_id').val();
                        data.price = data.sku = '';
                    }
                    data.group = group;
                    data['select_id'] = this.options.selectionItemCount[data.id];
                    tmpl = widget.element.find('#custom-option-' + group + '-type-template').html();

                    tmpl = mageTemplate(tmpl, {
                        data: data
                    });
                    $(tmpl).insertAfter($(parentId));

                    if (data['price_type']) { //eslint-disable-line max-depth
                        priceType = $('#' + widget.options.fieldId + '_' + data['option_id'] + '_price_type');
                        priceType.val(data['price_type']).attr('data-store-label', data['price_type']);
                    }
                    this._bindUseDefault(widget.options.fieldId + '_' + data['option_id'], data);
                    //Add selections

                    this.options.itemCount++;
                    this.options.selectionItemCount[data.id] = parseInt(this.options.selectionItemCount[data.id], 10) + 1;
                }
            }
        },

        addTicketType: function (event) {
            var data = {},
                element = event.target || event.srcElement || event.currentTarget,
                rowTmpl, priceType,
                parentId = '#' + $('.fieldset-alt').attr('id'),
                group = "select",
                tmpl, disabledBlock, priceType;

            data = event;
            data.id = data['option_id'];
            data['select_id'] = data['option_type_id'];
            this.options.selectionItemCount[data.id] = data['item_count'];

            if (typeof group !== 'undefined') {
                group = group.toLowerCase();
            }

            if (group == "select") {
                if (typeof group === 'undefined') {
                    return;
                }
                disabledBlock = $(parentId).find(parentId + '_type_' + group);

                if (disabledBlock.length) {
                    disabledBlock.removeClass('ignore-validate').show();
                } else {
                    if ($.isEmptyObject(data)) { //eslint-disable-line max-depth
                        data['option_id'] = $(parentId + '_id').val();
                        data.price = data.sku = '';
                    }
                    data.group = group;
                    data['select_id'] = this.options.selectionItemCount[data.id];
                    tmpl = this.element.find('#custom-option-' + group + '-type-template').html();

                    tmpl = mageTemplate(tmpl, {
                        data: data
                    });
                    $(tmpl).insertAfter($(parentId));

                    if (data['price_type']) {
                        priceType = $('#' + this.options.fieldId + '_' + data.id + '_select_' + data['select_id'] +
                            '_price_type');
                        priceType.val(data['price_type']).attr('data-store-label', data['price_type']);
                    }
                    this._bindUseDefault(this.options.fieldId + '_' + data.id + '_select_' + data['select_id'], data);
                    this.refreshSortableElements();
                    this.options.selectionItemCount[data.id] = parseInt(this.options.selectionItemCount[data.id], 10) + 1;
                    this.options.itemCount++;
                }
            }
        },

        /**
         * Add selection value for 'select' type of custom option
         */
        addSelection: function (event) {
            
            var data = {},
                element = event.target || event.srcElement || event.currentTarget,
                rowTmpl, priceType;

            if (typeof element !== 'undefined') {
                data.id = $(element).closest('#product_options_container_top > div')
                    .find('[name^="product[options]"][name$="[data-id]"]').val();
                // data['option_type_id'] = -1;

                if (!this.options.selectionItemCount[data.id]) {
                    this.options.selectionItemCount[data.id] = 1;
                }

                data['select_id'] = this.options.selectionItemCount[data.id];
                data.price = data.sku = '';
            } else {
                data = event;
                data.id = data['option_id'];
                data['select_id'] = data['option_type_id'];
                this.options.selectionItemCount[data.id] = data['item_count'];
            }
            rowTmpl = this.rowTmpl({
                data: data
            });

            $(rowTmpl).appendTo($('#select_option_type_row_' + data.id));

            //set selected price_type value if set
            if (data['price_type']) {
                priceType = $('#' + this.options.fieldId + '_' + data.id + '_select_' + data['select_id'] +
                    '_price_type');
                priceType.val(data['price_type']).attr('data-store-label', data['price_type']);
            }

            this._bindUseDefault(this.options.fieldId + '_' + data.id + '_select_' + data['select_id'], data);
            this.refreshSortableElements();
            this.options.selectionItemCount[data.id] = parseInt(this.options.selectionItemCount[data.id], 10) + 1;

            //$('#' + this.options.fieldId + '_' + data.id + '_select_' + data['select_id'] + '_title').focus();
        },

        /* removeOption: function () {
            $("#product_options_container_top").html("");
            this.options.itemCount = this.options.optItemCount;
        }, */

        addOptionManually: function () {
            if (!this.options.currentProductId) {
                var data = {},
                    baseTmpl;

                data.id = this.options.itemCount;
                data.type = '';
                data['option_id'] = 0;

                baseTmpl = this.baseTmpl({
                    data: data
                });

                $(baseTmpl)
                    .appendTo(this.element.find('#product_options_container_top'))
                    .find('.collapse').collapsable();

                data.type = "multiple";
                if (data.type) {
                    $('#' + this.options.fieldId + '_' + data.id + '_type').val(data.type).trigger('change', data);
                }

                //set selected is_require value if set
                if (data['is_require']) {
                    $('#' + this.options.fieldId + '_' + data.id + '_is_require').val(data['is_require']).trigger('change');
                }

                this.refreshSortableElements();
                this._bindCheckboxHandlers();
                this._bindReadOnlyMode();
                this.options.itemCount++;
                $('#' + this.options.fieldId + '_' + data.id + '_title').trigger('change');
            }
        },

        /**
         * Add custom option
         */
        addOption: function (event) {
            var data = {},
                widget = this,
                baseTmpl;

            data = event;
            this.options.itemCount = data['item_count'];

            baseTmpl = this.baseTmpl({
                data: data
            });

            $(baseTmpl)
                .appendTo(this.element.find('#product_options_container_top'))
                .find('.collapse').collapsable();
                
            if (data.type && data.type=="multiple") {
                // $('#' + this.options.fieldId + '_' + data.id + '_type').val(data.type).trigger('change', data);
                if (data.optionValues.length) { //eslint-disable-line max-depth
                    $.each(data.optionValues, function (key, value) {
                        widget.addTicketType(value);
                    });
                }
            }

            //set selected is_require value if set
            if (data['is_require']) {
                $('#' + this.options.fieldId + '_' + data.id + '_is_require').val(data['is_require']).trigger('change');
            }

            this.refreshSortableElements();
            this._bindCheckboxHandlers();
            this._bindReadOnlyMode();
            this.options.itemCount++;
            $('#' + this.options.fieldId + '_' + data.id + '_title').trigger('change');
        },

        /**
         * @return {Object}
         */
        refreshSortableElements: function () {
            if (!this.options.isReadonly) {
                this.element.sortable('refresh');
                this._updateOptionBoxPositions.apply(this.element);
                this._updateSelectionsPositions.apply(this.element);
                this._initSortableSelections();
            }

            return this;
        },

        /**
         * @param {String} id
         * @return {*}
         */
        getFreeOptionId: function (id) {
            return $('#' + this.options.fieldId + '_' + id).length ? this.getFreeOptionId(parseInt(id, 10) + 1) : id;
        }
    });

});
