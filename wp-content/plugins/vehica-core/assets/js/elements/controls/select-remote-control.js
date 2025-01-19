"use strict"

jQuery(window).on('elementor:init', function () {
    var ControlDaSelectTerm = elementor.modules.controls.BaseData.extend({
        onReady: function () {
            var daSelect = this.ui.select;
            var dataSource = daSelect.data('source');

            daSelect.select2({
                allowClear: true,
                placeholder: {
                    id: '0',
                    text: daSelect.data('placeholder')
                },
                ajax: {
                    url: dataSource,
                    dataType: 'json',
                    type: 'GET',
                    data: function (params) {
                        var query = {
                            search: params.term,
                        };
                        var selected = daSelect.val();
                        if (jQuery.isArray(selected) && daSelect.length > 0) {
                            query['exclude'] = selected.join(',');
                        }
                        return query;
                    },
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('X-WP-Nonce', window.wpApiSettings.nonce);
                    },
                    processResults: function (data) {
                        return {
                            results: jQuery.map(data, function (item) {
                                var name
                                if (typeof item.title !== 'undefined' && typeof item.title.rendered !== 'undefined') {
                                    name = item.title.rendered;
                                } else if (typeof item.name !== 'undefined') {
                                    name = item.name;
                                } else {
                                    name = '(no name)';
                                }

                                if (name === '') {
                                    name = '(no name)'
                                }

                                return {
                                    text: name,
                                    id: item.id
                                }
                            })
                        };
                    }
                }
            });

            var selected = daSelect.data('selected');
            if (selected !== '') {
                jQuery.ajax({
                    type: 'GET',
                    url: dataSource + (dataSource.indexOf('?') === -1 ? '?' : '&') + 'include=' + selected,
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('X-WP-Nonce', window.wpApiSettings.nonce);
                    },
                }).then(function (items) {
                    jQuery.each(items, function (index, item) {
                        if (typeof item.title !== 'undefined' && typeof item.title.rendered !== 'undefined') {
                            var name = item.title.rendered;
                        } else if (typeof item.name !== 'undefined') {
                            var name = item.name;
                        } else {
                            var name = 'test';
                        }
                        var option = new Option(name, item.id, true, true);
                        daSelect.append(option);
                    });
                    daSelect.trigger('change');
                });
            }
        },
        onBeforeDestroy: function () {
            if (this.ui.select.data('select2')) {
                this.ui.select.select2('destroy');
            }
        },
    });
    elementor.addControlView('vehica_select_remote', ControlDaSelectTerm);
});