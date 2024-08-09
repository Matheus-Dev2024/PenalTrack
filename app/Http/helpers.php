<?php

if (!function_exists('formatar_data_postgres')) {
    function formatar_data_postgres($data) {
        return implode('-', array_reverse(explode('/', $data)));
    }

}