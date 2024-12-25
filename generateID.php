<?php
    function generateNumericId($length) {
        $randomNum = mt_rand(0, pow(10, $length) - 1); 
        return str_pad($randomNum, $length, '0', STR_PAD_LEFT); 
    }
?>