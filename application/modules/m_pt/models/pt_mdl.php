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
class Pt_mdl extends CI_Model implements DatatableModel{

        public function appendToSelectStr() {
                return array(
                    'task_chk' => 'concat(\'<input class="qt_task" type="checkbox" value="\', id, \'" />\')',
                );
        }

        public function fromTableStr() {
            return 'sn_tasks';
        }

        public function joinArray(){
            return NULL;
        }

        public function whereClauseArray(){
            return NULL;
        }
        
        public function test($data){
            print_r($data);exit;
        }
   }