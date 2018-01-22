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

var Register = function () {


    var initFileInput = function () {
        /* input file */
        $(".file .btn,.file input:text").click(function () {
            var block = $(this).parents('.file');
            block.find('input:file').click();
            block.find('input:file').change(function () {
                block.find('input:text').val(block.find('input:file').val());
            });
        });
        /* eof input file */
    }


    var initDatePicker = function () {
        if ($(".datepicker").length > 0) {

            $("#birthdate").datepicker({
                firstDay: 1,
                nextText: "",
                prevText: "",
                changeMonth: true,
                changeYear: true,
                yearRange: "-80:+0",
                dateFormat: "dd-mm-yy"
            });

        }
    };

    return {
        //main function to initiate the module
        init: function () {
            initFileInput();
            initDatePicker();
        }
    }
}();
