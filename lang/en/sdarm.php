<?php

return [

    /*
    |--------------------------------------------------------------------------
    | SDARM Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used SDARM specific messages.
    | Regardless where it is placed, a button can be listed here so it is easily
    | found in a intuitive way.
    |
    */

    'backend' => [
        'access' => [
            'permissions' => [
                'create_error' => 'There was a problem creating this permission. Please try again.',
                'delete_error' => 'There was a problem deleting this permission. Please try again.',

                'groups' => [
                    'associated_permissions' => 'You can not delete this group because it has associated permissions.',
                    'has_children' => 'You can not delete this group because it has child groups.',
                    'name_taken' => 'There is already a group with that name',
                ],

                'not_found' => 'That permission does not exist.',
                'system_delete_error' => 'You can not delete a system permission.',
                'update_error' => 'There was a problem updating this permission. Please try again.',
            ],

            'roles' => [
                'already_exists' => 'That role already exists. Please choose a different name.',
                'cant_delete_admin' => 'You can not delete the Administrator role.',
                'create_error' => 'There was a problem creating this role. Please try again.',
                'delete_error' => 'There was a problem deleting this role. Please try again.',
                'has_users' => 'You can not delete a role with associated users.',
                'needs_permission' => 'You must select at least one permission for this role.',
                'not_found' => 'That role does not exist.',
                'update_error' => 'There was a problem updating this role. Please try again.',
            ],

            'users' => [
                'cant_deactivate_self' => 'You can not do that to yourself.',
                'cant_delete_self' => 'You can not delete yourself.',
                'create_error' => 'There was a problem creating this user. Please try again.',
                'delete_error' => 'There was a problem deleting this user. Please try again.',
                'email_error' => 'That email address belongs to a different user.',
                'mark_error' => 'There was a problem updating this user. Please try again.',
                'not_found' => 'That user does not exist.',
                'restore_error' => 'There was a problem restoring this user. Please try again.',
                'role_needed_create' => 'You must choose at lease one role. User has been created but deactivated.',
                'role_needed' => 'You must choose at least one role.',
                'update_error' => 'There was a problem updating this user. Please try again.',
                'update_password_error' => 'There was a problem changing this users password. Please try again.',
            ],
        ],
    ],

    'frontend' => [
        'auth' => [
            'confirmation' => [
                'already_confirmed' => 'Your account has already been confirmed. You may login now.',
                'confirm' => "Your account hasn't been confirmed. Click the link in the verification e-mail to confirm your account.",
                'created_confirm' => 'Your account was successfully created. A verification e-mail has been sent. Click the link in the e-mail to confirm your account.',
                'created_pending' => 'Your account has been successfully created. We will soon send you an e-mail to confirm your account as a translator.',
                'wait_confirm_mail' => 'Your account is not confirmed. Please wait till we send you the confirmation e-mail.',
                'mismatch' => 'Your confirmation code does not match.',
                'not_found' => 'The confirmation code/link is invalid.',
                'resend' => 'Your account has not been confirmed. Please click the confirmation link in your e-mail, or <a href="' . route('frontend.auth.account.confirm.resend', ':user_id') . '">click here</a> to resend the confirmation e-mail. If you cannot click the link, copy and paste the following link: ' . route('frontend.auth.account.confirm.resend', ':user_id'),
                'success' => 'Your account has been successfully confirmed! You may login now.',
                'resent' => 'The confirmation e-mail has been sent again to the address on file. Click the link in the e-mail to confirm your account and then try logging in. Check your Spam/Junk Mail folder too.',
            ],

            'deactivated' => 'Your account has been deactivated.',
            'email_taken' => 'The e-mail address you have entered has already been registered. Try logging in <a href="' . route('frontend.auth.login') . '">here</a> or use a different e-mail address to register.;',

            'password' => [
                'change_mismatch' => 'That is not your old password.',
            ],


        ],
    ],
];
