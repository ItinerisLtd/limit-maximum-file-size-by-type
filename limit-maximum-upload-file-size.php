<?php
declare(strict_types=1);

/**
 * Plugin Name:     Limit Maximum Upload File Size
 * Plugin URI:      https://github.com/itinerisltd/limit-maximum-upload-file-size
 * Description:     Limit maximum upload file size
 * Version:         0.2.0
 * Author:          Itineris Limited
 * Author URI:      https://www.itineris.co.uk/
 * Text Domain:     limit-maximum-upload-file-size
 */

namespace Itineris\LimitMaximumUploadFileSize;

// If this file is called directly, abort.
if (! defined('WPINC')) {
    die;
}

add_filter( 'upload_size_limit', function (): int {
    return 5242880; // 5 * 1024 * 1024 = 5MB.
}, 999);

add_filter('wp_handle_upload_prefilter', function (array $file): array {
    $imageSizeLimit = 2097152; // 2 * 1024 * 1024 = 2MB

    // TODO: Allow configuration.
    if (! str_starts_with($file['type'], 'image/')) {
        return $file;
    }

    if ($imageSizeLimit > $file['size']) {
        return $file;
    }

    // Translators: %1$d is the maximum file upload size , %2$s is its unit.
    $errorMessagePattern = __('Image files must be smaller than %1$d %2$s', 'limit-maximum-upload-file-size');
    $file['error'] = sprintf(
        $errorMessagePattern,
        $imageSizeLimit / 1048576,
        'MB'
    );

    return $file;
}, 9999);
