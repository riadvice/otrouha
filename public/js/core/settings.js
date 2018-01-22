/**
 * Copyright (C) 2018 RIADVICE SUARL <otrouha@riadvice.tn>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

var Settings = function () {

    var initThemeSwitcher = function () {
        $('select[name=theme]').on('change', function () {
            var theme = $("link[id='theme-style']");
            var themeName = $('select[name=theme] option:selected').text();
            theme.attr('href', '/css/themes/' + $(this).val() + '.css');
            noty({text: Locale.msg('settings', 'theme_changed').replace('{0}', themeName), type: 'message'});
        });
    };


    var editMenuHandler = function () {

        $('#edit_menu').on('click', function (evt) {
            $('#lbl_save_menu').css("display", "block");
            makeLabelsEditable();
        });

        $('#btn_save_menu').on('click', function (evt) {
            location.reload();
        });

    };

    var makeLabelsEditable = function () {
        $('.list-group-item').attr('href', '#');

        $('.editable-menu-item').editable({
            mode: 'popup',
            type: 'text',
            ajaxOptions: {
                type: 'put',
                dataType: 'json',
                sourceCache: 'false'
            },
            pk: 1,
            params: function (params) {
                params.locale = Locale.currentLocale;
                return params;
            },
            url: '/menu/edit',

            success: function (data, config) {
                noty({text: Locale.msg('settings', 'menu_item_edit_success'), type: 'info'});
            },
            error: function (response, newValue) {
                alert(response);
            }
        });
    };

    return {
        //main function to initiate the module
        init: function () {
            initThemeSwitcher();
            editMenuHandler();
        }
    }
}();
