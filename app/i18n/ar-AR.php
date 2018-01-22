<?php

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

return [
    'i18n' => [
        'label'   => [
            'core' => [
                'email'                 => 'Email',
                'password'              => 'Password',
                'password_hint'         => 'Password (8 characters at minimum)',
                'first_name'            => 'First Name',
                'last_name'             => 'Last Name',
                'back'                  => 'Back',
                'submit'                => 'Submit',
                'confirm'               => 'Approve',
                'cancel'                => 'Cancel',
                'logo'                  => 'Logo',
                'first'                 => 'First',
                'last'                  => 'Last {0}',
                'password_confirmation' => 'Confirm password',
                'save_password'         => 'Ssave password',
                'add'                   => 'Add',
                'edit'                  => 'Edit',
                'save'                  => 'Save',
                'view_more'             => 'View More...',
                'id'                    => 'ID',
                'search'                => 'Search...',
                'please_select'         => 'Please Select',
                'at'                    => 'at'
            ],

            'menu' => [
                'users'           => 'Users'
            ],

            'dashboard' => [
            ],

            'user' => [
                'users'              => 'Users',
                'user'               => 'User',
                'status'             => 'Status',
                'gender'             => 'Gender',
                'first_name'         => 'First name',
                'last_name'          => 'Last name',
                'title'              => 'Title',
                'edit_user'          => 'Edit user',
                'new_user'           => 'New user',
                'add_user'           => 'Add user',
                'my_profile'         => 'My profile',
                'new_password'       => 'New Password',
                'reset_password'     => 'Reset password',
                'current_password'   => 'Current Password',
                'confirm_password'   => 'Confirm Password',
                'change_password'    => 'Change my password',
            ],

            'settings' => [
                'edit_settings'         => 'Edit Settings',
                'organisation'          => 'Organisation',
            ],
        ],
        'message' => [
            'core' => [
                'login'               => 'Log me in',
                'password_forgot'     => 'Forgot your password?',
                'logout'              => 'logout',
                'delete_confirm'      => 'Do you want to continue ?',
                'yes'                 => 'Yes',
                'no'                  => 'No',
                'cancel'              => 'Cancel',
                'all_rights_reserved' => 'All rights reserved',
                'copyright'           => 'Copyright'
            ],

            'dashboard' => [

            ],

            'user' => [
                'login_success'           => 'Welcome back {0}!',
                'add_success'             => 'User successfully added',
                'edit_user'               => 'Edit user',
                'delete_user'             => 'Delete user',
                'edit_success'            => 'User {0} successfully edited',
                'profile_edit_success'    => 'Profile successfully edited',
                'delete_success'          => 'User {0} successfully deleted',
                'change_password_success' => 'Password successfully changed',
                'users_imported'          => '{0} users imported from {1} records'
            ],

            'settings' => [
                'edit_success'                  => 'Settings successfully saved'
            ],
        ],
        'error'   => [
            'core' => [
                'server_error'   => 'Unexpected server error',
                'empty'          => 'This field is required'
            ],

            'login' => [
                'password' => 'Invalid password'
            ],

            'user' => [
            ],

            'settings' => [
                'organisation' => 'Organisation name cannot be empty',
                'email'        => 'Invalid email or password, try again',
                'phone'        => 'Invalid telephone number',
                'website'      => 'Please provide a valid website URL',
                'address'      => 'Please enter your address',
                'locale'       => 'Please set your language',
                'edit_error'   => 'Error saving settings. Please check provided information'
            ],

        ],
        'list'    => [
            // From https://github.com/umpirsky/country-list/tree/master/country/icu
            'countries'    => [],

            'locales' => [
                'en-GB' => 'English',
                'ar-AR' => 'Arabic'
            ],

            'roles' => [
                'admin'       => 'Administrator'
            ],

            'statuses' => [
                'inactive' => 'Inactive',
                'active'   => 'Active',
            ],

            'days'         => [
                'monday'    => 'Monday',
                'tuesday'   => 'Tuesday',
                'wednesday' => 'Wednesday',
                'thursday'  => 'Thursday',
                'friday'    => 'Friday',
                'saturday'  => 'Saturday',
                'sunday'    => 'Sunday',
            ],

            'months'       => [
                'january'   => 'January',
                'february'  => 'February',
                'march'     => 'March',
                'april'     => 'April',
                'may'       => 'May',
                'june'      => 'June',
                'july'      => 'July',
                'august'    => 'August',
                'september' => 'September',
                'october'   => 'October',
                'november'  => 'November',
                'december'  => 'December',
            ],
        ],
    ],
];
