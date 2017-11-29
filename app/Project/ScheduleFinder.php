<?php

namespace App\Project;


use PHPExcel;
use PHPExcel_IOFactory;

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
        $dateTime = strtotime(date('d-m-Y'));
        $rowNum = 2;

        try {
            $csv = new PHPExcel();

            $csv->getProperties()->setTitle(' Time sheet');

            $csv->setActiveSheetIndex(0);
            $csv->getActiveSheet()->fromArray(['Available', 'Semi-Available'], null, 'A1');
            $objWriter = PHPExcel_IOFactory::createWriter($csv, 'Excel5');
            $objWriter->save();

            $objWriter->save(storage_path('reports/Schedule'.$dateTime.'.xls'));

            return 'reports/Schedule'.$dateTime.'.xls';

        } catch (\PHPExcel_Exception $e) {
        }
    }
}

