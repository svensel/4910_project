<?php

namespace App\Project;


class ScheduleFinder

{

    /*
     * This class should contain the algorithm to generate the schedules
     * This class should use the GoogleApi class when appropriate to include in the
     * schedule creation
     * It should return the schedules so that we can use it in a view.
     * You can pass data to a view by using view('pageName', [$data]);
     * THIS CLASS DOES NOT RETURN A VIEW! IT JUST RETURNS DATA THAT CAN BE USED IN A VIEW LATER!
     * */
    public function __construct()
    {
    }

    public function generateCsv(array $times)
    {
        # sheet row 2
        $rowNum = 2;

        /** Include PHPExcel */
        require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';


        try {
            $csv = new PHPExcel();
            echo date('H:i:s'), " Add some data", EOL;

            # sheet title
            $csv->getProperties()->setTitle($times['name'] . ' Time sheet');
            # Option 1 filling in available and unavailable times (going by column)
            $csv->setActiveSheetIndex(0)
                ->setCellValue('A2', $times['available'][0] . $times['available'][1])
                ->setCellValue('B2', $times['unavailable'][0] . $times['unavailable'][1]);

            # increment down rows on the sheet
//            for($i = 0; $i < count($times['available']); $i++){
//                $csv->getActiveSheet()->setCellValue('A'. $rowNum++, $times['available'][0].$times['available'][1]);
//            }

            $csv->setActiveSheetIndex(0);
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");;
            header("Content-Disposition: attachment;filename='times'");
            header("Content-Transfer-Encoding: binary ");

//            $filename = 'text.xlsx';
//            $objWriter = new PHPExcel_Writer_Excel2007($csv);
//            $objWriter->save($filename);
            // Save Excel file
            /*$objWriter = PHPExcel_IOFactory::createWriter($csv, 'Excel2007');
            $objWriter->save('php://output');*/
            $objWriter = PHPExcel_IOFactory::createWriter($csv, 'Excel5');
            $objWriter->save(str_replace('.php', '.xls', __FILE__));

            $objWriter->save(storage_path(‘reports’));


        } catch (\PHPExcel_Exception $e) {
        }
        //$writer->save(storage('/reports/filename.xls);
        # saving as excel file
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet

    }
}

