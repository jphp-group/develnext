<?php

/**
 * Turn on output buffering.
 *
 * @param callable $output_callback
 * @param int $chunk_size
 * @param bool $erase [optional]
 */
function ob_start ($output_callback = null, $chunk_size = null, $erase = null) {}

/**
 * Flush (send) the output buffer.
 */
function ob_flush () {}

/**
 * Clean (erase) the output buffer.
 */
function ob_clean () {}

/**
 * Flush (send) the output buffer and turn off output buffering.
 * @return bool
 */
function ob_end_flush () {}

/**
 * Clean (erase) the output buffer and turn off output buffering.
 * @return bool
 */
function ob_end_clean () {}

/**
 * Flush the output buffer, return it as a string and turn off output buffering.
 * @return string
 */
function ob_get_flush () {}

/**
 * Get current buffer contents and delete current output buffer
 * @return string
 */
function ob_get_clean () {}

/**
 * Return the length of the output buffer.
 * @return int
 */
function ob_get_length () {}

/**
 * Return the nesting level of the output buffering mechanism.
 * @return int
 */
function ob_get_level () {}

/**
 * Get status of output buffers.
 * @param null $full_status
 * @return array
 */
function ob_get_status ($full_status = null) {}

/**
 * Return the contents of the output buffer.
 * @return string
 */
function ob_get_contents () {}

/**
 * Turn implicit flush on/off.
 * @param int $flag [optional]
 */
function ob_implicit_flush ($flag = null) {}

/**
 * List all output handlers in use.
 * @return array
 */
function ob_list_handlers () {}