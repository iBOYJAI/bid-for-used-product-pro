<?php

/**
 * Helper Functions
 */

function format_currency($amount)
{
    return '₹' . number_format($amount, 2);
}

function format_date($date)
{
    return date('d M Y, h:i A', strtotime($date));
}

function is_bid_active($start, $end)
{
    $now = date('Y-m-d H:i:s');
    return ($now >= $start && $now <= $end);
}

function get_time_remaining($end_date)
{
    $now = new DateTime();
    $end = new DateTime($end_date);
    $interval = $now->diff($end);

    if ($now > $end) {
        return 'Ended';
    }

    if ($interval->d > 0) {
        return $interval->d . 'd ' . $interval->h . 'h remaining';
    }

    return $interval->h . 'h ' . $interval->i . 'm remaining';
}
