<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once 'application/libraries/PHPExcel.php';
require_once 'application/libraries/PHPExcel/IOFactory.php';

class Excel extends CI_Controller {

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
    }

    public function test() {
        ini_set('memory_limit', '800M');
        $file = fopen(FCPATH . "20161104A.csv", "r");
        $excel_data = array();
        $line = 0;
        while ($data = fgetcsv($file)) {
            $line > 0 && $excel_data[] = $data;
            $line ++;
        }
        $objPHPExcel = PHPExcel_IOFactory::load(FCPATH . "exam.xlsx");
        $objPHPExcel->setActiveSheetIndex(0); //设置第一个工作表为活动工作表
        $active_sheet = $objPHPExcel->getActiveSheet();
        // 所有数据拷贝到Sheet页面中
        $active_sheet->fromArray($excel_data, NULL, 'A2');
        // 设置单元格边框
        $styleThinBlackBorderOutline = array(
            'borders' => array(
                'outline' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN, //设置border样式
                    //'style' => PHPExcel_Style_Border::BORDER_THICK,  另一种样式
                    'color' => array('argb' => 'FF000000'), //设置border颜色
                ),
            ),
        );
        $page_end_num = $line + 1;
        $objPHPExcel->getActiveSheet()->getStyle("A2:W{$page_end_num}")->applyFromArray($styleThinBlackBorderOutline);
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle("A({$line}件)");
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
//$objPHPExcel->setActiveSheetIndex(0);
// Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="01simple.xlsx"');
        header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
// If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(FCPATH . "exam.xlsx");
        exit;
    }

    public function index() {
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();
        //大概翻译  创建一个富文本框  office有效  wps无效
        $objRichText = new PHPExcel_RichText();
        $objRichText->createText('This invoice is ');    //写文字
        //添加文字并设置这段文字粗体斜体和文字颜色
        $objPayable = $objRichText->createTextRun('payable within thirty days after the end of the month');
        $objPayable->getFont()->setBold(true);
        $objPayable->getFont()->setItalic(true);
        $objPayable->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_DARKGREEN));
        $objRichText->createText(', unless specified otherwise on the invoice.');
        //将文字写到A18单元格中
        $objPHPExcel->getActiveSheet()->getCell('A18')->setValue($objRichText);


        $styleThinBlackBorderOutline = array(
            'borders' => array(
                'outline' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN, //设置border样式
                    //'style' => PHPExcel_Style_Border::BORDER_THICK,  另一种样式
                    'color' => array('argb' => 'FF000000'), //设置border颜色
                ),
            ),
        );
        $objPHPExcel->getActiveSheet()->getStyle('A4:E10')->applyFromArray($styleThinBlackBorderOutline);


//给单元格添加批注
        $objPHPExcel->getActiveSheet()->getComment('E13')->setAuthor('PHPExcel');     //设置作者
        $objCommentRichText = $objPHPExcel->getActiveSheet()->getComment('E13')->getText()->createTextRun('PHPExcel:');  //添加批注
        $objCommentRichText->getFont()->setBold(true);  //将现有批注加粗
        $objPHPExcel->getActiveSheet()->getComment('E13')->getText()->createTextRun("\r\n");      //添加更多批注
        $objPHPExcel->getActiveSheet()->getComment('E13')->getText()->createTextRun('Total amount on the current invoice, including VAT.');
        $objPHPExcel->getActiveSheet()->getComment('E13')->setWidth('100pt');      //设置批注显示的宽高，在office中有效在wps中无效
        $objPHPExcel->getActiveSheet()->getComment('E13')->setHeight('100pt');
        $objPHPExcel->getActiveSheet()->getComment('E13')->setMarginLeft('150pt');
        $objPHPExcel->getActiveSheet()->getComment('E13')->getFillColor()->setRGB('EEEEEE');      //设置背景色，在office中有效在wps中无效
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="01simple.xlsx"');
        header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }

}
