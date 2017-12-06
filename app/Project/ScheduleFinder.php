<?php

namespace App\Project;

class ScheduleFinder
{
    private $times = ['available' => [[[]]]];
    private $allCourses = [];
    private $googleTimes;
    private $courseDays = ['mon', 'tues', 'wed', 'thur', 'fri'];
    private $dayIndex = ['Mon' => 0 , 'Tue' => 1, 'Wed' => 2, 'Thu' => 3, 'Fri' => 4];

    public function __construct(){
        $this->times['available'][0] = ['day' => 'M','times' => [['start' => '08:00:00', 'end' => '23:59:00']]];
        $this->times['available'][1] = ['day' => 'Tu','times' => [['start' => '08:00:00', 'end' => '23:59:00']]];
        $this->times['available'][2] = ['day' => 'W','times' => [['start' => '08:00:00', 'end' => '23:59:00']]];
        $this->times['available'][3] = ['day' => 'Th','times' => [['start' => '08:00:00', 'end' => '23:59:00']]];
        $this->times['available'][4] = ['day' => 'F','times' => [['start' => '08:00:00', 'end' => '23:59:00']]];
    }

    public function generateCalendar($groupId){
        $users = ModelFinder::getUsersFromGroup($groupId);
        foreach($users as $user) {
            $coursesToAdd = ModelFinder::getCoursesFromUser($user)->get()->toArray();
            $this->allCourses = array_merge($this->allCourses, $coursesToAdd);
            if ($user->google_cal_access == 1) {
                $api = new GoogleApi($user->id);
                $googleTimes = $api->fetch_events();
                //dd($googleTimes);
            }
        }

        foreach($this->allCourses as $course){
            $i = 0;
            foreach($this->courseDays as $day){
                if($course[$day] == 1) {
                    $this->findCourseOverlaps($course, $i);
                }
                $i++;
            }
        }

        $week2Times = unserialize(serialize($this->times));

        //dd($week2Times);

        foreach($googleTimes[0] as $busyTime){//first week
            $this->trimAvailableTimeWithEvent($busyTime, $this->dayIndex[$busyTime['startDayName']], $this->times);
        }

        foreach($googleTimes[1] as $busyTime){//second week
            $this->trimAvailableTimeWithEvent($busyTime, $this->dayIndex[$busyTime['startDayName']], $week2Times);
        }

        $combinedTimes = [];
        $combinedTimes[0] = $this->times;
        $combinedTimes[1] = $week2Times;
        //dd($combinedTimes);
        return $combinedTimes; //combinedTimes[0] is current week, combinedTimes[1] is next week

    }

    private function trimAvailableTimeWithEvent($event, $dayIndex, $times){
        //dd($event);
        foreach($times['available'][$dayIndex]['times'] as &$availTimeSlot){
            if($this->compareTimeStr($availTimeSlot['start'], $event['startTime']) < 0 &&
                $this->compareTimeStr($availTimeSlot['end'], $event['endTime']) > 0){ //completely inside (divide)
                $newTimeSlot = ['start' => $event['endTime'], 'end' => $availTimeSlot['end']];
                $availTimeSlot['end'] = $event['startTime'];
                $times['available'][$dayIndex]['times'][] = $newTimeSlot;
            }
            else if($this->compareTimeStr($availTimeSlot['start'], $event['startTime']) < 0 &&
                $this->compareTimeStr($availTimeSlot['end'], $event['startTime']) > 0){ //partially inside trailing
                $availTimeSlot['end'] = $event['startTime'];
            }
            else if($this->compareTimeStr($availTimeSlot['start'], $event['endTime']) < 0 &&
                $this->compareTimeStr($availTimeSlot['start'], $event['startTime']) > 0){ //partially inside leading
                $availTimeSlot['start'] = $event['endTime'];
            }
            //else = completely outside -> do nothing
        }
    }

    private function findCourseOverlaps($course, $dayIndex){
        foreach($this->times['available'][$dayIndex]['times'] as &$availTimeSlot){
            if($this->compareTimeStr($availTimeSlot['start'], $course['start_time']) < 0 &&
                $this->compareTimeStr($availTimeSlot['end'], $course['end_time']) > 0){ //completely inside (divide)
                $newTimeSlot = ['start' => $course['end_time'], 'end' => $availTimeSlot['end']];
                $availTimeSlot['end'] = $course['start_time'];
                $this->times['available'][$dayIndex]['times'][] = $newTimeSlot;
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

    private  function compareTimeStr($t1, $t2){ // 0 if same, + if t1 is after t2, - if t1 is before t2
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