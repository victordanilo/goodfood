<?php

use Illuminate\Support\Facades\DB;

function db_connected(): bool
{
    try {
        DB::connection()->getPdo();

        return true;
    } catch (Exception $exception) {
        return false;
    }
}
