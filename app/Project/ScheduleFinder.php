<?php

namespace App\Project;

use App\Course;

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

    public function generateCalendar($groupId){
        $users = ModelFinder::getUsersFromGroup($groupId);
        $allCourses = [];

        foreach($users as $user) {
            $coursesToAdd[] = ModelFinder::getCoursesFromUser($user)->get();
            $allCourses[] = array_merge_recursive($allCourses, $coursesToAdd);
            //$allCourses[] = ModelFinder::getCoursesFromUser($user)->get();
            $api = new GoogleApi($user->id);
            $googleTimes = $api->fetch_events();
        }

        //dd($allCourses);
        //dd($allCourses[0][0]);

        $times = ['available' => [[[]]]];
        //array_push(times['available'], ['day' => 'M','times' => [['start' => '08:00:00', 'end' => '23:59:00']]],
            //['day' => 'Tu','times' => [['start' => '08:00:00', 'end' => '23:59:00']]]);
        $times['available'][0] = ['day' => 'M','times' => [['start' => '08:00:00', 'end' => '23:59:00']]];
        $times['available'][1] = ['day' => 'Tu','times' => [['start' => '08:00:00', 'end' => '23:59:00']]];
        $times['available'][2] = ['day' => 'W','times' => [['start' => '08:00:00', 'end' => '23:59:00']]];
        $times['available'][3] = ['day' => 'Th','times' => [['start' => '08:00:00', 'end' => '23:59:00']]];
        $times['available'][4] = ['day' => 'F','times' => [['start' => '08:00:00', 'end' => '23:59:00']]];


        //dd($times);
        foreach($allCourses as $courseArray){
            foreach($courseArray[0] as $courseData){
                $course = $courseData->toArray();

                if($course['mon'] == 1){
                    foreach($times['available'][0]['times'] as $availTimeSlot){
                        //dd($availTimeSlot['start']);
                        if($this->compareTimeStr($availTimeSlot['start'], $course['start_time']) < 0 &&
                            $this->compareTimeStr($availTimeSlot['end'], $course['end_time']) > 0){ //completely inside (divide)
                            $curIndex = array_search($availTimeSlot, $times['available'][0]['times']);
                            $newTimeSlot = ['start' => $course['end_time'], 'end' => $availTimeSlot['end']];
                            $availTimeSlot['end'] = $course['start_time'];
                            array_splice($times['available'][0]['times'], $curIndex, 0, $newTimeSlot);
                        }
                        else if($this->compareTimeStr($availTimeSlot['start'], $course['start_time']) < 0 &&
                            $this->compareTimeStr($availTimeSlot['end'], $course['start_time']) > 0){ //partially inside trailing
                            $availTimeSlot['end'] = $course['start_time'];
                        }
                        else if($this->compareTimeStr($availTimeSlot['start'], $course['end_time']) < 0 &&
                            $this->compareTimeStr($availTimeSlot['start'], $course['start_time']) > 0){ //partially inside leading
                            $availTimeSlot['start'] = $course['end_time'];
                        }
                        //else = completely outside -> do nothing
                    }
                }
                if($course['tues'] == 1){
                    foreach($times['available'][1]['times'] as $availTimeSlot){
                        if($this->compareTimeStr($availTimeSlot['start'], $course['start_time']) < 0 &&
                            $this->compareTimeStr($availTimeSlot['end'], $course['end_time']) > 0){ //completely inside (divide)
                            $curIndex = array_search($availTimeSlot, $times['available'][0]['times']);
                            $newTimeSlot = ['start' => $course['end_time'], 'end' => $availTimeSlot['end']];
                            $availTimeSlot['end'] = $course['start_time'];
                            array_splice($times['available'][0]['times'], $curIndex, 0, $newTimeSlot);
                        }
                        else if($this->compareTimeStr($availTimeSlot['start'], $course['start_time']) < 0 &&
                            $this->compareTimeStr($availTimeSlot['end'], $course['start_time']) > 0){ //partially inside trailing
                            $availTimeSlot['end'] = $course['start_time'];
                        }
                        else if($this->compareTimeStr($availTimeSlot['start'], $course['end_time']) < 0 &&
                            $this->compareTimeStr($availTimeSlot['start'], $course['start_time']) > 0){ //partially inside leading
                            $availTimeSlot['start'] = $course['end_time'];
                        }
                        //else = completely outside -> do nothing
                    }
                }
                if($course['wed'] == 1){
                    foreach($times['available'][2]['times'] as $availTimeSlot){
                        if($this->compareTimeStr($availTimeSlot['start'], $course['start_time']) < 0 &&
                            $this->compareTimeStr($availTimeSlot['end'], $course['end_time']) > 0){ //completely inside (divide)
                            $curIndex = array_search($availTimeSlot, $times['available'][0]['times']);
                            $newTimeSlot = ['start' => $course['end_time'], 'end' => $availTimeSlot['end']];
                            $availTimeSlot['end'] = $course['start_time'];
                            array_splice($times['available'][0]['times'], $curIndex, 0, $newTimeSlot);
                        }
                        else if($this->compareTimeStr($availTimeSlot['start'], $course['start_time']) < 0 &&
                            $this->compareTimeStr($availTimeSlot['end'], $course['start_time']) > 0){ //partially inside trailing
                            $availTimeSlot['end'] = $course['start_time'];
                        }
                        else if($this->compareTimeStr($availTimeSlot['start'], $course['end_time']) < 0 &&
                            $this->compareTimeStr($availTimeSlot['start'], $course['start_time']) > 0){ //partially inside leading
                            $availTimeSlot['start'] = $course['end_time'];
                        }
                        //else = completely outside -> do nothing
                    }
                }
                if($course['thur'] == 1){
                    foreach($times['available'][3]['times'] as $availTimeSlot){
                        if($this->compareTimeStr($availTimeSlot['start'], $course['start_time']) < 0 &&
                            $this->compareTimeStr($availTimeSlot['end'], $course['end_time']) > 0){ //completely inside (divide)
                            $curIndex = array_search($availTimeSlot, $times['available'][0]['times']);
                            $newTimeSlot = ['start' => $course['end_time'], 'end' => $availTimeSlot['end']];
                            $availTimeSlot['end'] = $course['start_time'];
                            array_splice($times['available'][0]['times'], $curIndex, 0, $newTimeSlot);
                        }
                        else if($this->compareTimeStr($availTimeSlot['start'], $course['start_time']) < 0 &&
                            $this->compareTimeStr($availTimeSlot['end'], $course['start_time']) > 0){ //partially inside trailing
                            $availTimeSlot['end'] = $course['start_time'];
                        }
                        else if($this->compareTimeStr($availTimeSlot['start'], $course['end_time']) < 0 &&
                            $this->compareTimeStr($availTimeSlot['start'], $course['start_time']) > 0){ //partially inside leading
                            $availTimeSlot['start'] = $course['end_time'];
                        }
                        //else = completely outside -> do nothing
                    }
                }
                if($course['fri'] == 1){
                    foreach($times['available'][4]['times'] as $availTimeSlot){
                        if($this->compareTimeStr($availTimeSlot['start'], $course['start_time']) < 0 &&
                            $this->compareTimeStr($availTimeSlot['end'], $course['end_time']) > 0){ //completely inside (divide)
                            $curIndex = array_search($availTimeSlot, $times['available'][0]['times']);
                            $newTimeSlot = ['start' => $course['end_time'], 'end' => $availTimeSlot['end']];
                            $availTimeSlot['end'] = $course['start_time'];
                            array_splice($times['available'][0]['times'], $curIndex, 0, $newTimeSlot);
                        }
                        else if($this->compareTimeStr($availTimeSlot['start'], $course['start_time']) < 0 &&
                            $this->compareTimeStr($availTimeSlot['end'], $course['start_time']) > 0){ //partially inside trailing
                            $availTimeSlot['end'] = $course['start_time'];
                        }
                        else if($this->compareTimeStr($availTimeSlot['start'], $course['end_time']) < 0 &&
                            $this->compareTimeStr($availTimeSlot['start'], $course['start_time']) > 0){ //partially inside leading
                            $availTimeSlot['start'] = $course['end_time'];
                        }
                        //else = completely outside -> do nothing
                    }
                }
            }
        }


        dd($times);
        return($times);

    }

    public function compareTimeStr($t1, $t2){ // 0 if same, + if t1 is after t2, - if t1 is before t2
        if(intval(substr($t1, 0, 2)) != intval(substr($t2, 0, 2))){
            return intval(substr($t1, 0, 2)) - intval(substr($t2, 0, 2));
        }
        else if(intval(substr($t1, 3, 2)) != intval(substr($t2, 3, 2))){
            return intval(substr($t1, 3, 2)) - intval(substr($t2, 3, 2));
        }
        else if(intval(substr($t1, 6, 2)) != intval(substr($t2, 6, 2))){
            return intval(substr($t1, 6, 2)) - intval(substr($t2, 6, 2));
        }
        return 0;
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