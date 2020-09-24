<?php

namespace Roots\Soil;

/**
 * Checks whether two URLs share the same base URL.
 *
 * This checks whether the following URL properties match:
 * - scheme
 * - domain
 * - host
 * - port
 *
 * @param string $base_url
 * @param string $input_url
 * @param bool $strict
 * @return bool
 */
function compare_base_url($base_url, $input_url, $strict_scheme = true)
{
    $base_url = trailingslashit($base_url);
    $input_url = trailingslashit($input_url);

    if ($base_url === $input_url) {
        return true;
    }

    $input_url = parse_url($input_url);

    if (!isset($input_url['host'])) {
        return true;
    }

    $base_url = parse_url($base_url);

    if (!isset($base_url['host'])) {
        return false;
    }

    if (!$strict_scheme || !isset($input_url['scheme']) || !isset($base_url['scheme'])) {
        $input_url['scheme'] = $base_url['scheme'] = 'soil';
    }

    if (($base_url['scheme'] !== $input_url['scheme'])) {
        return false;
    }

    if ($base_url['host'] !== $input_url['host']) {
        return false;
    }

    if ((isset($base_url['port']) || isset($input_url['port']))) {
        return isset($base_url['port'], $input_url['port']) && $base_url['port'] === $input_url['port'];
    }

    return true;
}


/**
 * Determine whether current environment type is production.
 *
 * Use wp_get_environment_type() if available (WordPress 5.5 or newer), otherwise check whether WP_ENV constant is set.
 *
 * Assume production as default environment type when neither wp_get_environment_type() nor WP_ENV are available.
 *
 * @return bool
 */
function is_production_environment()
{
    if (function_exists('wp_get_environment_type')) {
        return wp_get_environment_type() === 'production';
    } elseif (defined('WP_ENV')) {
        return \WP_ENV === 'production';
    } else {
        return true;
    }
}
