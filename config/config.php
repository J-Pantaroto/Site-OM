<?php
return [
    'colors' => [
        'header' => env('COLOR_HEADER', '#24252a'),
        'header_hover' => env('COLOR_HEADER_HOVER', '#a8a29e'),
        'header_text' => env('COLOR_HEADER_TEXT', '#ffffff'),
        'banner_background' => env('COLOR_BANNER_BACKGROUND', 'rgba(0, 0, 0, 0.5)'),
        'banner_text' => env('COLOR_BANNER_TEXT', '#ffffff'),
        'card_produto_text' => env('COLOR_CARD_PRODUTO_TEXT', '#000000'),
        'card_produto_background' => env('COLOR_CARD_PRODUTO_BACKGROUND', 'rgba(255, 255, 255, 0.9)'),
        'menu_lateral_text' => env('COLOR_MENU_LATERAL_TEXT', '#000000'),
        'menu_lateral_background' => env('COLOR_MENU_LATERAL_BACKGROUND', '#f5f5f5'),
        'menu_lateral_background_hover' => env('COLOR_MENU_LATERAL_BACKGROUND_HOVER', '#a8a29e'),
        'menu_lateral_border' => env('COLOR_MENU_LATERAL_BORDER', 'rgba(0, 0, 0, 0.1)'),
        'cart_floating_background' => env('COLOR_CART_FLOATING_BACKGROUND', '#000000'),
        'cart_floating_icon' => env('COLOR_CART_FLOATING_ICON', '#ffffff'),
        'cart_floating_background_hover' => env('COLOR_CART_FLOATING_BACKGROUND_HOVER', '#ffc107'),
        'cart_floating_icon_hover' => env('COLOR_CART_FLOATING_ICON_HOVER', '#000000'),
        'cart_floating_counter_background' => env('COLOR_CART_FLOATING_COUNTER_BACKGROUND', 'red'),
        'cart_floating_counter' => env('COLOR_CART_FLOATING_COUNTER', '#ffffff'),
        'cart_modal_plus_minus_background' => env('COLOR_CART_MODAL_PLUS_MINUS_BACKGROUND', '#ffffff'),
        'cart_modal_plus_minus_border' => env('COLOR_CART_MODAL_PLUS_MINUS_BORDER', '#DDD'),
        'footer_background' => env('COLOR_FOOTER_BACKGROUND', '#24252a'),
        'black_90' => env('COLOR_BLACK_90', '#171717'),
        'gray_30' => env('COLOR_GRAY_30', '#a8a29e'),
        'white_90' => env('COLOR_WHITE_90', '#f5f5f5'),
        'black_70' => env('COLOR_BLACK_70', '#24252a'),
        'yellow' => env('COLOR_YELLOW', 'yellow'),
        'danger' => env('COLOR_DANGER', '#dc3545'),
        'danger_hover' => env('COLOR_DANGER_HOVER', '#c82333'),
        'white' => env('COLOR_WHITE', '#ffffff'),
        'white_gray' => env('COLOR_WHITE_GRAY', '#DDD'),
        'black' => env('COLOR_BLACK', '#000000'),
        'button_primary' => env('COLOR_BUTTON_PRIMARY', '#ffc107'),
        'button_primary_hover' => env('COLOR_BUTTON_PRIMARY_HOVER', '#e0a800'),
        # DASHBOARD:HEADER
        'dashboard_header_background' => env('COLOR_DASHBOARD_HEADER_BACKGROUND', null),
        'dashboard_header_user_button_background' => env('COLOR_DASHBOARD_HEADER_USER_BUTTON_BACKGROUND', null),
        'dashboard_header_text' => env('COLOR_DASHBOARD_HEADER_TEXT', null),
        # DASHBOARD:TABLE
        'dashboard_table_background' => env('COLOR_DASHBOARD_TABLE_BACKGROUND', '#000000'),
        'dashboard_table_background_text' => env('COLOR_DASHBOARD_TABLE_BACKGROUND_TEXT', '#FFFFFF'),
        #DASHBOARD:BANNERS
        'dashboard_banners_background' => env('COLOR_DASHBOARD_BANNERS_BACKGROUND', '#FFFFFF'),
        'dashboard_banners_text' => env('COLOR_DASHBOARD_BANNERS_TEXT', '#000000'),
        #DASHBOARD:GERAL
        'background' => env('COLOR_BACKGROUND', '#F3F4F6'),
    ],
    'config' => [
        'nome_site' => env('APP_NAME', 'A definir nome_site'),
        'site_url' => env('URL_APP', 'A definir site_url' ),
        'metodo_email'=>env('MAIL_MAILER', 'smpt'),
        'email_host'=>env('MAIL_HOST', 'A definir email_host'),
        'email_porta'=>env('MAIL_PORT','587'),
        'email_usuario'=>env('MAIL_USERNAME', 'A definir email usuario'),
        'email_senha'=>env('MAIL_USERNAME', 'A definir email usuario'),
        'email_encryption'=>env('MAIL_ENCRYPTION', 'A definir email encrypton'),
        'email_remetente'=>env('MAIL_FROM_ADDRESS', 'A definir remetente'),
        'instagram'=>env('INSTAGRAM_URL', 'A definir link instagram empresa'),
        'facebook'=>env('FACEBOOK_URL', 'A definir link facebook empresa'),
        'whatsapp'=>env('WHATSAPP_URL', 'A definir link whatsapp empresa'),
    ],
    'imgs' =>[
        
    ]
];
