<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div class="wrap">
    <h1><?php esc_html_e('AI Commenter Settings', 'aicc'); ?></h1>
    <form method="post" action="options.php">
        <?php
        settings_fields('aicc_settings'); // Diese Funktion zeigt versteckte Sicherheitsfelder an
        do_settings_sections('aicc'); // Diese Funktion zeigt alle Einstellungen und Sektionen an
        submit_button(); // Diese Funktion zeigt den "Speichern"-Button an
        ?>
    </form>
</div>
