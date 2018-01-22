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

var Plugins = function () {

    var initNoty = function () {
        $.noty.defaults.theme = 'defaultTheme';
        $.noty.defaults.timeout = 3000;
        $.noty.defaults.layout = 'topCenter';
        $.noty.defaults.animation = {
            open: 'animated flipInX',
            close: 'animated flipOutX',
            speed: 150
        };
    };

    var initPopover = function () {
        $("[data-toggle=popover]").popover();
    };

    return {
        //main function to initiate the module
        init: function () {
            initNoty();
            initPopover();
        }
    }
}();
