<?php


class M_tasks_dt extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $this->load->view('tasks_dt_view');
    }

    public function dataTable() {
        $this->load->database();
        //Important to NOT load the model and let the library load it instead.  
        $this -> load -> library('Datatable', array('model' => 'pt_mdl', 'rowIdCol' => 'id'));
                    

        //format array is optional, but shown here for the sake of example
        $this -> datatable -> setPreResultCallback(
            function(&$json, &$obj) {
                $rows =& $json['data'];
                foreach($rows as &$r) {
                    $record = $obj->db->where('relate_id', $r['id'])->get('sn_task_detail')->result_array();
                    $r['detail_view'] = $obj->load->view('task_detail_view', array('tasks' => $record), true);
                }
            });
        $json = $this -> datatable->datatableJson(
            array(
                'status' => 'run-status'
            )
        );
//        $this -> datatable->setPreResultCallback('test');
        $this -> output -> set_header("Pragma: no-cache");
        $this -> output -> set_header("Cache-Control: no-store, no-cache");
        $this -> output -> set_content_type('application/json') -> set_output(json_encode($json));
    }
    

}
