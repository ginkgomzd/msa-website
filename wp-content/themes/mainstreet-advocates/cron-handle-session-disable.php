<?php
include( '../../../wp-load.php' );
global $wpdb;

$wpdb->query('UPDATE session_info SET is_active = 0 WHERE end_date <  DATE_ADD(NOW(),INTERVAL 1 MONTH) AND is_active = 1');
