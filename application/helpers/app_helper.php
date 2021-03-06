<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Array Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		EllisLab Dev Team
 * @link		https://codeigniter.com/user_guide/helpers/array_helper.html
 */

// ------------------------------------------------------------------------

if ( ! function_exists('csv_filter_str'))
{
	/**
	 * Element
	 *
	 * Lets you determine whether an array index is set and whether it has a value.
	 * If the element is empty it returns NULL (or whatever you specify as the default value.)
	 *
	 * @param	string
	 * @param	array
	 * @param	mixed
	 * @return	mixed	depends on what the array contains
	 */
	function csv_filter_str($str)
	{
            $str = str_replace(array("\r\n", "\r", "\n"), CSVFILE_LINE, $str);
//            $str = str_replace(chr(13), CSVFILE_LINE, $str);
            $str = str_replace(',', CSVFILE_SPLIT, $str);
            return $str;
	}
}
if ( ! function_exists('set_my_checkbox'))
{
	/**
	 * Element
	 *
	 * Lets you determine whether an array index is set and whether it has a value.
	 * If the element is empty it returns NULL (or whatever you specify as the default value.)
	 *
	 * @param	string
	 * @param	array
	 * @param	mixed
	 * @return	mixed	depends on what the array contains
	 */
	function set_my_checkbox($value = '', $default = FALSE)
	{
            if($value === $default){
                return ' checked="checked"';
            }else{
                return '';
            }
	}
}

if ( ! function_exists('json_out'))
{
	/**
	 * Element
	 *
	 * Lets you determine whether an array index is set and whether it has a value.
	 * If the element is empty it returns NULL (or whatever you specify as the default value.)
	 *
	 * @param	string
	 * @param	array
	 * @param	mixed
	 * @return	mixed	depends on what the array contains
	 */
	function json_out($data = array())
	{
            $init_data = array(
                'status' => false,
                'msg' => '',
                'return' => array(),
            );
            echo json_encode(array_merge($init_data, $data));
            exit;
	}
}
