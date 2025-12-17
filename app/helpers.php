<?php

if (!function_exists('storge_asset')) {
    /**
     * Generate URL for files stored in the 'storge' directory
     *
     * @param string $path
     * @return string
     */
    function storge_asset($path)
    {
        return url('storge/' . ltrim($path, '/'));
    }
}
