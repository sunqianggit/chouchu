<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Serv extends CI_Controller {
    
    private $db;
    
    public function __construct() {
        parent::__construct();
        $this->db = $this->load->database('default', TRUE);
    }
    
    public function exec_tasks(){
        if(empty($_POST['tasks'])){
            json_out(array('msg' => '请选择抽出任务后，在点击执行'));
        }else{
            $tasks = explode(',', rtrim($_POST['tasks'], ','));
            $insert_data = array();
            foreach ($tasks as $ccbh) {
                $insert_data[]['ccbh'] = $ccbh;
            }
            $this->db->insert_batch('task_queue', $insert_data);
            $url = base_url()."pt/exec_sql_task";
            $ch = curl_init();
            curl_setopt($ch,CURLOPT_URL,$url);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch,CURLOPT_TIMEOUT,1);
            $result = curl_exec($ch);
            curl_close($ch);
            json_out(array('status' => true, 'msg' => '后台正在执行抽出SQL任务!'));
        }
    }
}
