<?php

if (!function_exists('display_rating_stars')) {
    function display_rating_stars($rating) {
        $html = '';
        $fullStars = floor($rating);
        $halfStar = ($rating - $fullStars) >= 0.5;
        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
        
        for ($i = 0; $i < $fullStars; $i++) {
            $html .= '★';
        }
        
        if ($halfStar) {
            $html .= '⯨';
        }
        
        for ($i = 0; $i < $emptyStars; $i++) {
            $html .= '☆';
        }
        
        return $html;
    }
}