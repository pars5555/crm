<?php
/**
 * Smarty plugin
 *
 * @package    Smarty
 * @subpackage PluginsModifier
 */

/**
 * Smarty date_format modifier plugin
 * Type:     modifier<br>
 * Name:     date_format<br>
 * Purpose:  format datestamps via strftime<br>
 * Input:<br>
 *          - string: input date string
 *          - format: strftime format for output
 *          - default_date: default date if $string is empty
 *
 * @link   http://www.smarty.net/manual/en/language.modifier.date.format.php date_format (Smarty online manual)
 * @author Monte Ohrt <monte at ohrt dot com>
 *
 * @param string $string       input date string
 * @param string $format       strftime format for output
 * @param string $default_date default date if $string is empty
 * @param string $formatter    either 'strftime' or 'auto'
 *
 * @return string |void
 * @uses   smarty_make_timestamp()
 */
function smarty_modifier_date_format($string, $format = null, $default_date = '', $formatter = 'auto')
{
    if ($format === null) {
        $format = Smarty::$_DATE_FORMAT;
    }
    /**
     * require_once the {@link shared.make_timestamp.php} plugin
     */
    require_once(SMARTY_PLUGINS_DIR . 'shared.make_timestamp.php');
    if ($string != '' && $string != '0000-00-00' && $string != '0000-00-00 00:00:00') {
        $timestamp = smarty_make_timestamp($string);
    } elseif ($default_date != '') {
        $timestamp = smarty_make_timestamp($default_date);
    } else {
        return;
    }
    if ($formatter == 'strftime' || ($formatter == 'auto' && strpos($format, '%') !== false)) {
        // Convert strftime format to date() format for PHP 8.2+ compatibility
        $_strftime_to_date = array(
            '%D' => 'm/d/y', '%h' => 'M', '%n' => "\n", '%r' => 'h:i:s A',
            '%R' => 'H:i', '%t' => "\t", '%T' => 'H:i:s',
            '%e' => 'j', '%l' => 'g',
            '%B' => 'F', '%b' => 'M', '%m' => 'm', '%d' => 'd',
            '%Y' => 'Y', '%y' => 'y', '%H' => 'H', '%I' => 'h',
            '%M' => 'i', '%S' => 's', '%p' => 'A', '%A' => 'l',
            '%a' => 'D', '%j' => 'z', '%Z' => 'T', '%c' => 'D M j H:i:s Y',
            '%x' => 'm/d/y', '%X' => 'H:i:s',
            '%%' => '%'
        );
        $date_format = strtr($format, $_strftime_to_date);
        return date($date_format, $timestamp);
    } else {
        return date($format, $timestamp);
    }
}
