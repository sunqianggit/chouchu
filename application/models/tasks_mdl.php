<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of info4sModel
 *
 * @author Administrator
 */
class Tasks_mdl extends CI_Model {

    //put your code here
    private $table = 'tasks';
    public $db;

    public function __construct() {
        parent::__construct();
        $this->db = $this->load->database('default', TRUE);
        $this->db2 = $this->load->database('chouchu_db', TRUE);
    }

    public function save($data, $ccbh = '') {
        foreach ($data['query'] as $key => $val) {
            $save_arr['query_' . $key] = $val;
        }
        foreach ($data['insert'] as $key => $val) {
            $save_arr['insert_' . $key] = $val;
        }
        foreach ($data['delete'] as $key => $val) {
            $save_arr['delete_' . $key] = $val;
        }
        $save_arr['back_ab'] = $data['back']['ab'];
        $save_arr['cwxz'] = isset($data['cwxz']) ? implode(',', $data['cwxz']) : '';
        $save_arr['cwbh'] = $data['cwbh'];
        $save_arr['blocks'] = isset($data['block']) ? implode(',', $data['block']) : '';
        $save_arr['run_block_validate'] = isset($data['run_block_validate']) ? $data['run_block_validate'] : '0';
        $save_arr['cc_name'] = $data['cc_name'];
        $save_arr['ccbh'] = $data['ccbh'];
        $save_arr['fw_note'] = $this->_get_sql_notes($data['query']['a']);
        $save_arr['cw_note'] = $this->_get_sql_notes($data['query']['b']);
        if($ccbh === ''){
            $this->db->insert($this->table, $save_arr);
        }else{
            $this->db->where('ccbh', $ccbh)->update($this->table, $save_arr);
//            echo $this->db->last_query();exit;
        }
    }

    public function get_init_data($ccbh = ''){
        $init_data = array();
        $init_cwxz = array('D', 'E', 'F', 'G', 'H');
        $init_blocks = array('1','2','3','4','5','6','7','8','9');
        if($ccbh != ''){
            $init_data = $this->db->where('ccbh', $ccbh)->get('sn_tasks')->row_array();
            $init_data['cwxz'] = $this->_init_select_data($init_cwxz, $init_data['cwxz']);
            $init_data['blocks'] = $this->_init_select_data($init_blocks, $init_data['blocks']);
        }else{
            $fields = $this->db->get('sn_tasks')->list_fields();
            foreach($fields as $field){
                $arr = explode('_', $field);
                if(in_array($arr[0], array('query','delete','back','insert'))){
                    $init_data[$field] = $this->load->view("sql_tpl/pt/{$field}.sql", null, TRUE);
                }else if($field == 'cwxz'){
                    $init_data[$field] = $init_cwxz;
                }else{
                    $init_data[$field] = null;
                }
            }
        }
//        echo '<pre>';
//        print_r($init_data);exit;
        return $init_data;
    }
    
    private function _init_select_data($init_data, $data) {
        $data = explode(',', $data);
        $diff = array_diff($init_data, $data);
        foreach($diff as $key => $val){
            $init_data[$key] = '';
        }
        return $init_data;
    }
    
    private function _get_sql_notes($param) {
        $list = explode(chr('13'), $param);
        $notes = array();
        foreach ($list as $key => $val) {
            if (substr(trim($val), 0, 2) == '--') {
                $notes[] = substr(trim($val), 2);
            }
        }
        return implode(chr('13'), $notes);
    }

}
