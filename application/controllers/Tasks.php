<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class Tasks extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('tasks_mdl');
    }
    
    public function index($ccbh = ''){
        $this->pt($ccbh);
    }

    public function pt($ccbh = '') { 
        $init_data = $this->tasks_mdl->get_init_data($ccbh);
        if (isset($_POST['save'])) {
            $this->tasks_mdl->save($_POST);
            redirect('pt/sql_view/' . $_POST['ccbh']);
        }else if (isset($_POST['update'])){
            if($ccbh === ''){
                show_error('丢失主键ID');
            }
            $this->tasks_mdl->save($_POST, $ccbh);
            redirect('pt/sql_view/' . $ccbh);
        }
        $this->load->view('tasks', $init_data);
    }
}
