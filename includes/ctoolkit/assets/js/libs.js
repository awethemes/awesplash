/*
 * cToolkit library
 * 
 * @license: GPLv3
 * @author: tuanvu
 */


jQuery(function ($) {

    'use strict';

    var $document = $(document);

    /**
     * @sine 1.1
     */
    $.fn.ctoolkitImagePicker = function () {

        var file_frames = {};

        var get_ids = function (input_value) {
            var ids = [];
            if (input_value != '') {
                var arr = input_value.split(',');
                for (var i in arr) {
                    var obj = arr[i].split('|');
                    ids.push(obj[0]);
                }
            }
            return ids;
        }

        $document.on('click', '.ctoolkit-image_picker .add_images', function (e) {

            e.preventDefault();
            var $this = $(this);
            var $field = $this.closest('.ctoolkit-image_picker');
            var $input = $field.find('input[type="hidden"]');
            if (file_frames[$field.attr('id')]) {
                file_frames[$field.attr('id')].open();
                return;
            }

            file_frames[$field.attr('id')] = wp.media.frames.file_frame = wp.media({
                title: 'Add Images',
                button: {
                    text: 'Add Images'
                },
                library: {
                    type: 'image'
                },
                multiple: $field.data('multiple')
            });

            file_frames[$field.attr('id')].on('open', function () {

                var ids, selection;
                ids = get_ids($input.val());
                if ('' != ids) {
                    selection = file_frames[$field.attr('id')].state().get('selection');
                    $(ids).each(function (index, element) {
                        var attachment;
                        attachment = wp.media.attachment(element);
                        attachment.fetch();
                        selection.add(attachment ? [attachment] : []);
                    });
                }
            });

            file_frames[$field.attr('id')].on('select', function () {

                var result, selection;
                result = [];
                selection = file_frames[$field.attr('id')].state().get('selection');
                var ids = get_ids($input.val());

                var item = '';
                selection.map(function (attachment) {

                    attachment = attachment.toJSON();
                    var src = attachment.sizes.hasOwnProperty('thumbnail') ? attachment.sizes.thumbnail.url : attachment.url;
                    if (ids == '' || $.inArray(attachment.id.toString(), ids) === -1) {
                        item += '<li class="added" data-id="' + attachment.id + '">\n\
                                    <div class="inner">\n\
                                        <img alt="' + attachment.title + '" src="' + src + '"/>\n\
                                    </div>\n\
                                    <a href="#" class="remove"></a>\n\
                                </li>';
                        src = src.replace(ctoolkit_var.upload_url, '');
                        result.push(attachment.id + '|' + encodeURIComponent(src));
                    }

                });


                if (result.length > 0) {
                    if ($field.data('multiple')) {
                        if (ids != '') {
                            result = ids.concat(result);
                        }
                        $field.find('.image_list').append(item);
                    } else {
                        $field.find('.image_list').html(item);
                    }

                    $input.val(result).change();
                }
            });

            file_frames[$field.attr('id')].open();
        });


        $document.on('click', '.ctoolkit-image_picker .remove', function (e) {
            e.preventDefault();
            var $this = $(this);
            var $input = $this.closest('.ctoolkit-image_picker').find('input[type="hidden"]');
            var ids = $input.val();
            var index = $this.closest('li').index();
            if (ids != '') {
                ids = ids.split(',');
                delete ids[index];
                ids = ids.filter(function (val) {
                    return val;
                });
            }

            $input.val(ids).trigger('change');
            $this.closest('li').remove();
        });


        if ($.fn.sortable) {
            $('.ctoolkit-image_picker .image_list').sortable({
                stop: function (e, ui) {
                    var ids = [];
                    var $list = $(ui.item[0]).parent();
                    $list.find('li').each(function () {
                        ids.push($(this).attr('data-id'));
                    });
                    $list.closest('.ctoolkit-image_picker').find('input[type="hidden"]').val(ids).change();
                }
            });
        }
    }


    $.fn.ctoolkitLink = function () {

        $document.on('click', '.ctoolkit-link .link_button', function (e) {

            e.preventDefault();
            var $block, $input, $url_label, $title_label, value_object, $link_submit, $ctoolkit_link_submit, $ctoolkit_link_nofollow, dialog;
            $block = $(this).closest(".ctoolkit-link");
            $input = $block.find("input.ctoolkit_value");
            $url_label = $block.find(".url-label");
            $title_label = $block.find(".title-label");
            value_object = $input.data("json");
            $link_submit = $("#wp-link-submit");
            $ctoolkit_link_submit = $('<input type="button" name="ctoolkit_link-submit" id="ctoolkit_link-submit" class="button-primary" value="Set Link">');
            $link_submit.hide();
            $("#ctoolkit_link-submit").remove();
            $ctoolkit_link_submit.insertBefore($link_submit);
            $ctoolkit_link_nofollow = $('<div class="link-target ctoolkit-link-nofollow"><label><span></span> <input type="checkbox" id="ctoolkit-link-nofollow"> Add nofollow option to link</label></div>');
            $("#link-options .ctoolkit-link-nofollow").remove();
            $ctoolkit_link_nofollow.insertAfter($("#link-options .link-target"));
            setTimeout(function () {
                var currentHeight = $("#most-recent-results").css("top");
                $("#most-recent-results").css("top", parseInt(currentHeight) + $ctoolkit_link_nofollow.height())
            }, 200);
            dialog = window.wpLink;
            dialog.open('content');

            if (typeof value_object.url == 'string' && $("#wp-link-url").length) {
                $("#wp-link-url").val(value_object.url);
            } else {
                $("#url-field").val(value_object.url);
            }

            if (typeof value_object.url == 'string' && $("#wp-link-text").length) {
                $("#wp-link-text").val(value_object.title);
            } else {
                $("#link-title-field").val(value_object.title);
            }

            if ($("#wp-link-target").length) {

                $("#wp-link-target").prop("checked", value_object.target.length);
            } else {
                $("#link-target-checkbox").prop("checked", value_object.target.length);
            }

            if ($("#ctoolkit-link-nofollow").length) {
                $("#ctoolkit-link-nofollow").prop("checked", value_object.rel.length);
            }


            $ctoolkit_link_submit.unbind("click.ctoolkitLink").bind("click.ctoolkitLink", function (e) {

                e.preventDefault();
                e.stopImmediatePropagation();
                var string, options = {};
                options.url = $("#wp-link-url").length ? $("#wp-link-url").val() : $("#url-field").val();
                options.title = $("#wp-link-text").length ? $("#wp-link-text").val() : $("#link-title-field").val();
                var $checkbox = $($("#wp-link-target").length ? "#wp-link-target" : "#link-target-checkbox");
                options.target = $checkbox[0].checked ? " _blank" : "";
                options.rel = $("#ctoolkit-link-nofollow")[0].checked ? "nofollow" : "";

                string = $.map(options, function (value, key) {
                    return typeof value == 'string' && 0 < value.length ? key + ":" + encodeURIComponent(value) : void 0
                }).join("|");

                $input.val(string).change();
                $input.data("json", options);
                $url_label.html(options.url + options.target);
                $title_label.html(options.title);
                dialog.close('noReset');
                window.wpLink.textarea = "";
                $link_submit.show();
                $ctoolkit_link_submit.unbind("click.ctoolkitLink");
                $ctoolkit_link_submit.remove();
                $("#wp-link-cancel").unbind("click.ctoolkitLink");
                $checkbox.attr("checked", false);
                $("#most-recent-results").css("top", "");
                $("#ctoolkit-link-nofollow").attr("checked", false);
                return false;
            });
            $("#wp-link-cancel").unbind("click.ctoolkitLink").bind("click.ctoolkitLink", function (e) {
                e.preventDefault();
                dialog.close('noReset');
                $ctoolkit_link_submit.unbind("click.ctoolkitLink");
                $ctoolkit_link_submit.remove();
                $("#wp-link-cancel").unbind("click.ctoolkitLink");
                $("#wp-link-close").unbind("click.ctoolkitCloseLink");
                window.wpLink.textarea = "";
                return false;
            });
            $('#wp-link-close').unbind('click').bind('click.ctoolkitCloseLink', function (e) {
                e.preventDefault();
                dialog.close('noReset');
                $ctoolkit_link_submit.unbind("click.ctoolkitLink");
                $ctoolkit_link_submit.remove();
                $("#wp-link-cancel").unbind("click.ctoolkitLink");
                $("#wp-link-close").unbind("click.ctoolkitCloseLink");
                window.wpLink.textarea = "";
                return false;
            });
        });
    }

    $.fn.ctoolkitTypography = function () {

        var typography_data = {};

        var is_font_changed = false;

        var font_changed = function ($wrapper, data, data2) {

            var font_formated = {
                'font-family': data.value
            };

            var $subsets = $wrapper.find('.subsets select');
            var $variants = $wrapper.find('.variants select');
            var $subsets_selectize = $subsets[0].selectize;
            var $variants_selectize = $variants[0].selectize;
          
            if (data.hasOwnProperty('variants') && data.variants != '') {

                var variants = data.variants.split(',');
                var options = [];
                var _variants = ctoolkit_var.variants;


                for (var i in data.variants) {
                    var text = _variants.hasOwnProperty(variants[i]) ? _variants[variants[i]] : variants[i];
                    options.push({text: text, value: variants[i]});
                }

                $variants_selectize.enable();
                $variants_selectize.clearOptions();
                $variants_selectize.addOption(options);

                if (typeof data2 == 'object' && data2.hasOwnProperty('variants')) {
                    var selected_variants = data2.variants.split(',');
                    $variants_selectize.addItems(selected_variants);
                } else {

                    $variants_selectize.addItems(variants);
                }


                font_formated['variants'] = data.variants;
            } else {
                $variants_selectize.clearOptions();
                $variants_selectize.disable();
            }

            if (data.hasOwnProperty('subsets') && data.subsets != '') {

                var subsets = data.subsets.split(',');
                var options = [];
                var _subsets = ctoolkit_var.subsets;

                for (var i in subsets) {
                    var text = _subsets.hasOwnProperty(subsets[i]) ? _subsets[subsets[i]] : subsets[i];
                    options.push({text: text, value: subsets[i]});
                }

                $subsets_selectize.enable();
                $subsets_selectize.clearOptions();
                $subsets_selectize.addOption(options);

                if (typeof data2 == 'object' && data2.hasOwnProperty('subsets')) {
                    var selected_subsets = data2.subsets.split(',');
                    $subsets_selectize.addItems(selected_subsets);
                } else {
                    if ($.inArray('latin', subsets) >= 0) {
                        $subsets_selectize.addItem('latin');
                        font_formated['subsets'] = 'latin';
                    }
                }



            } else {
                $subsets_selectize.clearOptions();
                $subsets_selectize.disable();
            }

            if (typeof data2 == 'function') {
                data2(font_formated);
            }
        }

        var $typography = $(this);

        var $typo_font_family = $typography.find('.font_family select');

        $typography.find('.variants select').selectize({
            plugins: ['remove_button'],
            create: false,
            onChange: function (value) {
                if (!is_font_changed) {
                    var $field = $(this)[0].$wrapper.closest('.ctoolkit-typography');

                    var id = $field.data('id');

                    var _typography_data = typography_data[id];

                    var text = $field.data('value');

                    if (text != '') {

                        if (_typography_data.hasOwnProperty('variants')) {

                            if (typeof value == 'object' && value != null) {
                                _typography_data.variants = value.join(',');

                            } else {
                                _typography_data.variants = value;
                            }

                            var val = encodeURIComponent(JSON.stringify(_typography_data));

                            typography_data[id] = _typography_data;
                            
                            $field.find('.ctoolkit_value').val(val).change();

                        }
                    }
                }
            }
        });

        $typography.find('.subsets select').selectize({
            plugins: ['remove_button'],
            create: false,
            onChange: function (value) {
                if (!is_font_changed) {
                    var $field = $(this)[0].$wrapper.closest('.ctoolkit-typography');

                    var id = $field.data('id');

                    var _typography_data = typography_data[id];

                    var text = $field.data('value');

                    if (text != '') {
                        if (_typography_data.hasOwnProperty('subsets')) {
                            if (typeof value == 'object' && value != null) {
                                _typography_data.subsets = value.join(',');
                            } else {
                                _typography_data.subsets = value;
                            }
                            var val = encodeURIComponent(JSON.stringify(_typography_data));
                            typography_data[id] = _typography_data;
                            $field.find('.ctoolkit_value').val(val).change();
                        }
                    }

                }
            }
        });

        $typo_font_family.selectize({
            labelField: "label",
            valueField: "value",
            searchField: "label",
            create: false,
            options: ctoolkit_var.fonts,
            render: {
                option: function (item, escap) {
                    return "<div class='option' data-value='" + item.value + "' data-variants='" + item.variants + "' data-subsets='" + item.subsets + "'>" + item.label + " </div>";
                }
            },
            onInitialize: function () {

                var $field = $(this)[0].$wrapper.closest('.ctoolkit-typography');

                var id = $field.data('id');

                typography_data[id] = {};

                var value = $field.data('value');

                if (value != '') {

                    var data = JSON.parse(decodeURIComponent(value));

                    if (data.hasOwnProperty('font-family')) {
                        typography_data[id] = data;
                        $(this)[0].addItem(data['font-family']);
                    }
                }
            },
            onChange: function (value) {

                is_font_changed = true;

                var $field = $(this)[0].$wrapper.closest('.ctoolkit-typography');

                var id = $field.data('id');

                var _typography_data = typography_data[id];

                if (_typography_data.hasOwnProperty('font-family') && _typography_data['font-family'] === value) {

                    font_changed($field, this.options[value], _typography_data);

                } else {

                    var input = this.options.hasOwnProperty(value) ? this.options[value] : {};

                    font_changed($field, input, function (data) {

                        _typography_data['font-family'] = data['font-family'];
                        _typography_data['subsets'] = data['subsets'];
                        _typography_data['variants'] = data['variants'];

                        var val = encodeURIComponent(JSON.stringify(_typography_data));

                        $field.find('.ctoolkit_value').val(val).change();

                        typography_data[id] = _typography_data;

                    });
                }

                is_font_changed = false;

            }
        });

        if ($typography.find('.subrow .color').length) {
            $typography.find('.subrow .color input').wpColorPicker({
                change: function (e, ui) {
                    $(e.target).val(ui.color.toString()).change();
                }
            });
        }

        $typography.on('change', '.subrow input, .subrow select', function (e) {

            var key = $(this).data('key');

            var $this = $(this);

            var $field = $this.closest('.ctoolkit-typography');

            var id = $field.data('id');

            var value = $this.val();

            typography_data[id][key] = value;

            var val = encodeURIComponent(JSON.stringify(typography_data[id]));

            $field.find('.ctoolkit_value').val(val).change();

            e.preventDefault();
        });
    }

    $.fn.ctoolkitAutocomplete = function () {
        $(this).selectize({
            valueField: 'value',
            searchField: 'label',
            labelField: 'label',
            options: [],
            create: false,
            plugins: ['remove_button', 'drag_drop'],
            render: {
                option: function (item, escape) {
                    return '<div class="option" data-value="' + item.value + '">#' + item.value + ' - ' + escape(item.label) + '</div>';
                }
            },
            load: function (query, callback) {

                var $container = $(this)[0].$wrapper.closest('.ctoolkit-field');

                var min_length = $container.data('min_length');

                if (query.length < parseInt(min_length))
                    return callback();

                var type = $container.data('ajax_type');

                var values = $container.data('ajax_value');

                $.ajax({
                    url: ajaxurl,
                    type: 'GET',
                    data: {
                        action: 'ctoolkit_autocomplete_' + type,
                        types: values,
                        s: query
                    },
                    error: function () {
                        callback();
                    },
                    success: function (data) {
                        callback(data);
                    }
                });
            }
        });
    };

    $.fn.ctoolkitMultitext = function () {


        var $el = $(this);

        var $template = $el.find('.multitext-item').clone();
        $template.find('input').val('');

        var update_value = function (e, val) {
            var value = [];
            var $parent = $(e.target).closest('.ctoolkit-multitext');
            var $items = $parent.find('li');
            var $input = $parent.find('.ctoolkit_value');

            if (typeof val != 'undefined') {

                $input.val('').change();

            } else {

                $items.each(function () {
                    var _val = $(this).find('input').val();
                    if (_val != '') {
                        value.push($(this).find('input').val());
                    }
                });

                if (value.length) {
                    $input.val(encodeURIComponent(JSON.stringify(value))).change();
                } else {
                    $input.val('').change();
                }
            }

        };

        $el.on('change', '.multitext-item input', function (e) {
            update_value(e);
        });

        $el.on('click', '.addnew', function (e) {
            var $this = $(this);
            var $list = $this.closest('.ctoolkit-multitext').find('ul');
            $list.append('<li class="multitext-item">' + $template.html( ) + '</li>');
            e.preventDefault();
        });

        $el.on('click', '.remove', function (e) {
            var $this = $(this);
            var $list = $this.closest('ul');
            var $item = $this.closest('.multitext-item');
            if ($list.find('.multitext-item').length > 1) {
                $item.remove();
                update_value(e);
            } else {
                $item.find('input').val('').focus();
                update_value(e, '');
            }


            e.preventDefault();
        });

        if ($.fn.sortable) {
            $el.find('ul').sortable({
                items: '.multitext-item',
                handle: '.short',
                stop: function (e, ui) {
                    update_value(e);
                }
            });
        }
    }

});
