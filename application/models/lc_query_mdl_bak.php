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
class Lc_query_mdl extends CI_Model{
    //put your code here
    private $table = 'query_code';
    public $db;
    
    public function __construct() {
        parent::__construct();
        $this->db = $this->load->database('default', TRUE);
    }
    
    public function insert1($data){
        foreach($data['query'] as $key => $val){
            $insert_arr['query_'.$key] = $val;
        }
        foreach($data['insert'] as $key => $val){
            $insert_arr['insert_'.$key] = $val;
        }
        foreach($data['delete'] as $key => $val){
            $insert_arr['delete_'.$key] = $val;
        }
        $insert_arr['back_ab'] = $data['back']['ab'];
        $insert_arr['cwxz'] = isset($data['cwxz']) ? implode(',', $data['cwxz']) : '';
        $insert_arr['cwbh'] = $data['cwbh'];
        $insert_arr['blocks'] = isset($data['block']) ? implode(',', $data['block']) : '';
        $insert_arr['run_block_validate'] = isset($data['run_block_validate']) ? $data['run_block_validate'] : '0';
        $insert_arr['cc_name'] = $data['cc_name'];
        $insert_arr['ccbh'] = $data['ccbh'];
        $insert_arr['fw_note'] = $this->get_sql_notes($data['query']['a']);
        $insert_arr['cw_note'] = $this->get_sql_notes($data['query']['b']);
        $this->db->insert($this->table, $insert_arr);
        return $this->db->insert_id();
    }
    
    public function insert2($data){
        $this->db->insert_batch('sn_task_detail', $data);
    }
    
    public function generate_sql($id){
        $data = $this->db->where('id', $id)->get($this->table)->row_array();
        $query_arr = array('a','b','d','e','f','g','h');
        $sql_arr = array();
        foreach($query_arr as $val){
            $sql_arr['query_'.$val] = str_replace('{$@_cwbh_@$}', $data['cwbh'], $data['query_'.$val]);
            if(isset($data['insert_'.$val])){
                $sql_arr['insert_'.$val] = str_replace('{$@_cwbh_@$}', $data['cwbh'], $data['insert_'.$val]);
            }
        }
        $sql_arr['back_ab'] = $data['back_ab'];
        $sql_arr['delete_ab_bak'] = $data['delete_ab_bak'];
        $sql_arr['query_c'] = $this->load->view('sql_tmp/query_c', $data, TRUE);
        $sql_arr['query_ah'] = $this->load->view('sql_tmp/query_ah', $data, TRUE);
        $data['chouchu'] = '';
        $sql_arr['dl_sql'] = $this->load->view('sql_tmp/query_ah', $data, TRUE);
        $sql_arr['dl_sql'] = $this->filer_sql_notes($sql_arr['dl_sql']);
        if(isset($data['block'])){
            foreach ($data['block'] as $key => $val) {
                $sql_arr['block_sql'.$val] = $sql_arr['dl_sql'].chr('13').' and A.BLOCKNO = \'0'.$val.'\' ';
            }
        }
        return $sql_arr;
//        echo '<pre>';
//        echo $insert_arr['chouchu_sql'];exit;
//        $this->db->insert($this->table, $insert_arr);
    }
    
    public function exec_qtchouchu_task($id){
        $this->db2 = $this->load->database('chouchu_db', TRUE);
        $this->load->dbutil($this->db2);
        $this->load->helper('file');
        $task = $this->db->where('id', $id)->get('sn_tasks')->row_array();
        $task_sql = $this->db->where('relate_id', $id)->get('sn_task_detail')->result_array();
        $Date = date("Ymd");
        $FileDir = FCPATH."/{$Date}/{$task['ccbh']}";
        $inifile_content = "";
        
        $query_arr = array('A');
        $exc_status = 1;
        foreach($task_sql as $key => $val){
            if(in_array($val['name'], $query_arr)){
                $query = $this->db2->query($val['sql_content']);
                if($query){
                    // 记录结果集到对应的CSV文件
                    $data = $this->dbutil->csv_from_result_jp($query);
                    $this->db->where('id', $val['id'])->update('sn_task_detail', array('status' => 1, 'num_rows' => $data['num_rows']));
                    $this->mk_not_exist_dir($FileDir);
                    $Filename = $FileDir."/{$val['name']}.csv";
                    write_file($Filename, $data['csv_content']);
                    // Excel VBA 配置文件制作， 存储条数，备注信息
                    $note = $this->load->view('excel_notes/'.$val['name'], array('task' => $task, 'task_sql' => $val, 'num_rows' => $data['num_rows']), TRUE);
                    $note = str_replace(PHP_EOL, INIFILE_LINE, $note);
//                    $note = iconv("utf-8","SJIS",$note);
                    $inifile_content .= "[{$val['name']}]".PHP_EOL."num_rows={$data['num_rows']}".PHP_EOL."note=$note".PHP_EOL;
                }else{
                    $this->db->where('id', $val['id'])->update('sn_task_detail', array('status' => 0, 'num_rows' => 0));
                    $exc_status = 0;
                }
            }
        }
        $this->write_note_ini($FileDir."/notes.ini", $inifile_content);
        $this->db->where('id', $id)->update('sn_tasks', array('status' => $exc_status));
    }
    
    public function mk_not_exist_dir($path){
        if (!is_dir($path)){
            $res=mkdir(iconv("UTF-8", "GBK", $path),0777,true);
	}
    }
    
    public function filer_sql_notes($param) {
        $list = explode(chr('13'), $param);
        foreach($list as $key => $val){
            if(substr(trim($val), 0, 2) == '--'){
                unset($list[$key]); 
            }
        }
        return implode('', $list);
    }
    
    public function write_note_ini($file_name, $ini_content) {
        write_file($file_name, iconv("utf-8","SJIS//ignore",$ini_content));
    }
    
    public function get_sql_notes($param) {
        $list = explode(chr('13'), $param);
        $notes = array();
        foreach($list as $key => $val){
            if(substr(trim($val), 0, 2) == '--'){
                $notes[] = substr(trim($val), 2); 
            }
        }
        return implode(chr('13'), $notes);
    }
}

