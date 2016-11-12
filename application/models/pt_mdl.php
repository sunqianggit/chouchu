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
class Pt_mdl extends CI_Model {

    //put your code here
    public $db;
    public $db2;

    public function __construct() {
        parent::__construct();
        $this->db = $this->load->database('default', TRUE);
        $this->db2 = $this->load->database('chouchu_db', TRUE);
    }

    public function save($data, $ccbh) {
        $this->db->where('ccbh', $ccbh)->delete('sn_task_detail'); 
        $this->db->insert_batch('sn_task_detail', $data);
    }

    public function generate_sql($ccbh) {
        $data = $this->db->where('ccbh', $ccbh)->get('tasks')->row_array();
        $query_arr = array('a', 'b');
        $sql_arr = array();
        $sql_arr['cwxz'] = explode(',', strtolower($data['cwxz']));
        $query_arr = array_merge($query_arr, $sql_arr['cwxz']);
        foreach ($query_arr as $val) {
            $sql_arr['delete_'. $val] = "delete from FMD180EXCEL1 where ccbh ='{$data['cwbh']}".  strtoupper($val). "'";
            $sql_arr['query_' . $val] = str_replace('{$@_cwbh_@$}', $data['cwbh'], $data['query_' . $val]);
            if (isset($data['insert_' . $val])) {
                $sql_arr['insert_' . $val] = str_replace('{$@_cwbh_@$}', $data['cwbh'], $data['insert_' . $val]);
            }
            $sql_arr['show_' . $val] = true;
        }
        $sql_arr['back_ab'] = $data['back_ab'];
        $sql_arr['delete_ab_bak'] = $data['delete_ab_bak'];
        $sql_arr['query_c'] = $this->load->view('sql_tmp/query_c', $data, TRUE);
        $sql_arr['query_ah'] = $this->load->view('sql_tmp/query_ah', $data, TRUE);
        $data['chouchu'] = '';
        $sql_arr['dl_sql'] = $this->load->view('sql_tmp/query_ah', $data, TRUE);
        $sql_arr['dl_sql'] = $this->_filer_sql_notes($sql_arr['dl_sql']);
        $block_arr = explode(',', $data['blocks']);
        if (!empty($block_arr)) {
            foreach ($block_arr as $key => $val) {
                $sql_arr['block_sql' . $val] = $sql_arr['dl_sql'] . chr('13') . ' and A.BLOCKNO = \'0' . $val . '\' ';
            }
        }
        unset($data);
        return $sql_arr;
//        echo '<pre>';
//        echo $save_arr['chouchu_sql'];exit;
//        $this->db->insert('tasks', $save_arr);
    }

    public function exec_qtchouchu_task($ccbh, $queue_id='') {
        $this->load->model('email_model');
        if($queue_id !== ''){
            // 修改队列状态
            $this->_chg_queue_status($queue_id, 2);
            // 判断是否停止该队列操作
            $is_stop = $this->_is_stopqueue($queue_id);
            if($is_stop){
                return;
            }
        }
        $this->load->dbutil($this->db2);
        $this->load->helper('file');
        $task = $this->db->where('ccbh', $ccbh)->get('sn_tasks')->row_array();
        $task_sql = $this->db->where('ccbh', $ccbh)->get('sn_task_detail')->result_array();
        $Date = date("Ymd");
        $FileDir = FCPATH . "/{$Date}/{$task['ccbh']}";

        $query_arr = array('A', 'B', 'D', 'E', 'F', 'G', 'H', 'AH');
        $exc_status = 1;
        $excel_notes = array();
        foreach ($task_sql as $key => $val) {
            $num_rows   = 0;
            $query = $this->db2->query($val['sql_content']);
            // 记录执行结果到数据库
            if ($query) {
                // 把 A-H 的查询结果导入到 CSV 文件
                if (in_array($val['name'], $query_arr)) {
                    // 记录结果集到对应的CSV文件
                    $data = $this->dbutil->csv_from_result_jp($query);
                    $num_rows = $data['num_rows'];
                    $this->_mk_not_exist_dir($FileDir);
                    $Filename = $FileDir . "/{$val['name']}.csv";
                    write_file($Filename, $data['csv_content']);
                    // 记录备注信息到csv文件
                    $note = $this->load->view('excel_notes/' . $val['name'], array(
                        'fw_note' => csv_filter_str($task['fw_note']),
                        'cw_note' => csv_filter_str($task['cw_note']),
                        'task_sql' => csv_filter_str($val['sql_content']),
                        'num_rows' => $num_rows), TRUE);
                    $excel_notes[] = array($num_rows, iconv("utf-8", "SJIS//ignore", $note));
                }
                $this->_detail_suc_record($val['id'], $num_rows);
            } else {
                // 执行失败记录LOG，跳出循环
                $this->_detail_fail_record($val['id'], $task['ccbh'], $val['name'], $val['sql_content']);
                $exc_status = 2;
                break;
            }
        }
        $this->_write_note_csv($FileDir . "/notes.csv", $excel_notes);
        $this->db->where('ccbh', $ccbh)->update('sn_tasks', array('status' => $exc_status));
        // 修改队列状态
        if($queue_id !== ''){
            $queue_status = $exc_status == '1' ? 3 : 4;
            $this->_chg_queue_status($queue_id, $queue_status);
        }
        // 邮件通知执行结果
        $mail_body = "[{$task['ccbh']}] {$task['cc_name']} ->";
        $mail_body .= $exc_status == '1' ? "执行成功" : '执行失败';
        $res = $this->email_model->send_email('1114581129@qq.com', $mail_body, '抽出报告'); // 发送EMAIL给总经理
    }
    
    public function get_task_queue() {
        return $this->db->where('status', 1)->get('task_queue')->result_array();
    }

    private function _chg_queue_status($queue_id, $status){
        $this->db->where('id', $queue_id)->update('task_queue', array('status' => $status));
    }
    
    private function _is_stopqueue($queue_id){
        $queue = $this->db->where('id', $queue_id)->get('task_queue')->row_array();
        return $queue['status'] == 5 ? TRUE : FALSE;
    }
            
    private function _detail_suc_record($id, $num) {
        $this->db->where('id', $id)->update('sn_task_detail', array(
            'status' => 1,
            'num_rows' => $num));
    }

    private function _detail_fail_record($id, $ccbh, $xxbh, $sql) {
        $this->db->where('id', $id)->update('sn_task_detail', array('status' => 2, 'num_rows' => 0));
        $error = $this->db2->error();
        log_message('error', 'tasks_mdl.php' . chr(13) .
                'SQL[' . $ccbh . '][' . $xxbh . ']' . chr(13) .
                'SQL:' . $sql . chr(13) .
                '[error code]' . $error['code'] . '[error message]' . $error['message']);
    }

    private function _mk_not_exist_dir($path) {
        if (!is_dir($path)) {
            $res = mkdir(iconv("UTF-8", "GBK", $path), 0777, true);
        }
    }

    private function _filer_sql_notes($param) {
        $list = explode(chr('13'), $param);
        foreach ($list as $key => $val) {
            if (substr(trim($val), 0, 2) == '--') {
                unset($list[$key]);
            }
        }
        return implode('', $list);
    }

    private function _write_note_csv($file_name, $data) {
        $newline = "\n";
        $csv_content = '';
        foreach ($data as $key => $value) {
            $csv_content .= implode(',', $value) . $newline;
        }
        write_file($file_name, $csv_content);
    }

}
