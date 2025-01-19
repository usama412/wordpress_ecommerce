<?php

use Vehica\Core\Notification;

return [
    [
        'key' => Notification::MAIL_CONFIRMATION,
        'label' => esc_html__('Confirmation Email', 'vehica-core'),
        'description' => esc_html__('Require new users to verify account via email.', 'vehica-core'),
        'title' => esc_html__('Confirm your e-mail', 'vehica-core'),
        'message' => esc_html__('Hello {userDisplayName},

Thank you for registration! Please confirm your email by clicking this link: {confirmationLink}

Best wishes,
CEO', 'vehica-core'),
        'vars' => ['confirmationLink', 'userDisplayName'],
        'optional' => false,
        'enabled' => true,
    ],
    [
        'key' => Notification::WELCOME_USER,
        'label' => esc_html__('Welcome Message', 'vehica-core'),
        'description' => esc_html__('Everyone who register on your website will get this email. If you turned on the option: Registration - require new users to verify account via email (Basic Setup) this email will be send after user confirm email address.', 'vehica-core'),
        'title' => esc_html__('Welcome to our website', 'vehica-core'),
        'message' => esc_html__('Hello {userDisplayName},

Thank you for registering to our website.

Best wishes,
CEO', 'vehica-core'),
        'vars' => ['userDisplayName'],
        'optional' => true,
        'enabled' => false,
    ],
    [
        'key' => Notification::RESET_PASSWORD,
        'label' => esc_html__('Reset Password', 'vehica-core'),
        'description' => esc_html__('Email that your users will receive if they use Forgotten password? function', 'vehica-core'),
        'title' => esc_html__('Reset your password', 'vehica-core'),
        'message' => esc_html__('Hello {userDisplayName},

You can reset your password here: {resetPasswordLink}

Best wishes,
CEO', 'vehica-core'),
        'vars' => ['resetPasswordLink', 'userDisplayName'],
        'optional' => false,
        'enabled' => true,
    ],
    [
        'key' => Notification::CAR_APPROVED,
        'label' => esc_html__('Listing Approved', 'vehica-core'),
        'description' => esc_html__('If you turned on Basic Setup > Moderation - admin needs to approve listings this email will be send to the user if you approve listing', 'vehica-core'),
        'title' => esc_html__('Your listing {listingName} was approved', 'vehica-core'),
        'message' => esc_html__('Hello {userDisplayName},

Your listing {listingName} was approved! You can view it here: {listingLink}

Best wishes,
CEO', 'vehica-core'),
        'vars' => ['userDisplayName', 'listingLink', 'listingName'],
        'optional' => true,
        'enabled' => false,
    ],
    [
        'key' => Notification::CAR_DECLINED,
        'label' => esc_html__('Listing Declined', 'vehica-core'),
        'description' => esc_html__('If you turned on Basic Setup > Moderation - admin needs to approve listings this email will be send to the user if you declined listing', 'vehica-core'),
        'title' => esc_html__('Your listing {listingName} was declined', 'vehica-core'),
        'message' => esc_html__('Hello {userDisplayName},

Your listing {listingName} was declined! We are very sorry.

Best wishes,
CEO', 'vehica-core'),
        'vars' => ['userDisplayName', 'listingName'],
        'optional' => true,
        'enabled' => false,
    ],
    [
        'key' => Notification::CAR_PENDING,
        'label' => esc_html__('Listing Pending', 'vehica-core'),
        'description' => esc_html__('If you turned on Basic Setup > Moderation - admin needs to approve listings this email will be send to the user just after submiting listing', 'vehica-core'),
        'title' => esc_html__('New Listing Pending Review', 'vehica-core'),
        'message' => esc_html__('Hello {userDisplayName},

Your listing {listingName} is pending review. We will inform you by email when it will be approved / declined.

Best wishes,
CEO', 'vehica-core'),
        'vars' => ['userDisplayName', 'listingName'],
        'optional' => true,
        'enabled' => false,
    ],
    [
        'key' => Notification::NEW_CAR_PENDING,
        'label' => esc_html__('Admin: New Listing Pending', 'vehica-core'),
        'description' => esc_html__('If you turned on Basic Setup > Moderation - admin needs to approve listings this email will be send to administration email address. You can set this email address it in the /wp-admin/ > Settings > General > Administration Email Address', 'vehica-core'),
        'title' => esc_html__('New listing is pending review', 'vehica-core'),
        'message' => esc_html__('Hello,

New listing "{listingName}" by {userDisplayNameWithLink} is pending review

Best wishes,
CEO', 'vehica-core'),
        'vars' => ['userDisplayName', 'userDisplayNameWithLink', 'listingName', 'expire', 'featuredExpire' ],
        'optional' => true,
        'enabled' => false,
    ],
];