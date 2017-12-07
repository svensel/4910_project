<?php

namespace App\Project;

use PHPExcel;
use PHPExcel_IOFactory;

class ScheduleFinder
{
    private $times = ['available' => [[[]]]];
    private $allCourses = [];
    private $courseDays = ['mon', 'tues', 'wed', 'thur', 'fri'];
    private $dayIndex = ['Mon' => 0 , 'Tue' => 1, 'Wed' => 2, 'Thu' => 3, 'Fri' => 4, 'Sat' => 5, 'Sun' => 6];

    public function __construct()
    {
        $this->times['available'][0] = ['day' => 'M','times' => [['start' => '08:00:00', 'end' => '23:59:00']]];
        $this->times['available'][1] = ['day' => 'Tu','times' => [['start' => '08:00:00', 'end' => '23:59:00']]];
        $this->times['available'][2] = ['day' => 'W','times' => [['start' => '08:00:00', 'end' => '23:59:00']]];
        $this->times['available'][3] = ['day' => 'Th','times' => [['start' => '08:00:00', 'end' => '23:59:00']]];
        $this->times['available'][4] = ['day' => 'F','times' => [['start' => '08:00:00', 'end' => '23:59:00']]];
    }

    public function generateCalendar($groupId)
    {
        $users = ModelFinder::getUsersFromGroup($groupId);
        $googleTimes = array();

        foreach($users as $user) {
            $coursesToAdd = ModelFinder::getCoursesFromUser($user)->get()->toArray();
            $this->allCourses = array_merge($this->allCourses, $coursesToAdd);
            if ($user->google_cal_access == 1) {
                $api = new GoogleApi($user->id);
                $googleTimes[] = $api->fetch_events();
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

        $this->times['available'][5] = ['day' => 'Sat','times' => [['start' => '08:00:00', 'end' => '23:59:00']]];
        $this->times['available'][6] = ['day' => 'Sun','times' => [['start' => '08:00:00', 'end' => '23:59:00']]];
        $week2Times = unserialize(serialize($this->times));

        foreach($googleTimes as $week){//first week
            foreach($week[0] as $busyTime)
                $this->trimAvailableTimeWithEvent($busyTime, $this->dayIndex[$busyTime['startDayName']], $this->times);
        }

        foreach($googleTimes as $week){//second week
            foreach($week[1] as $busyTime){
                $this->trimAvailableTimeWithEvent($busyTime, $this->dayIndex[$busyTime['startDayName']], $week2Times);
            }
        }

        $combinedTimes = [];
        $sun1 = $this->times['available'][6];
        $sun2 = $week2Times['available'][6];
        unset($this->times['available'][6]);
        unset($week2Times['available'][6]);
        array_unshift($this->times['available'], $sun1);
        array_unshift($week2Times['available'], $sun2);
        $combinedTimes[0] = $this->times;
        $combinedTimes[1] = $week2Times;
        return $combinedTimes; //combinedTimes[0] is current week, combinedTimes[1] is next week

    }

    private function trimAvailableTimeWithEvent($event, $dayIndex, &$times)
    {
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

    private function findCourseOverlaps($course, $dayIndex)
    {
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

    private  function compareTimeStr($t1, $t2)
    { // 0 if same, + if t1 is after t2, - if t1 is before t2
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

    public function generateSpreadsheet(array $times, $startDate, $endDate)
    {
        $headers = [];
        $this->times['available'][0] = ['day' => 'M','times' => [['start' => '08:00:00', 'end' => '23:59:00']]];
        $filename = strtotime(date('d-m-Y H:i:s')).'.xls';

        for($i = $startDate; $i < $endDate; $i += 86400)
            $headers[] = date('d-M-Y', $i);

        try {
            $csv = new PHPExcel();
            $csv->getProperties()->setTitle('Group Available Times');
            $csv->setActiveSheetIndex(0)->fromArray($headers, 'A1');

            $dayNum = 0;
            foreach($times as $week){
                $rowNum = 2;
                foreach($week['available'] as $day){
                    if($startDate < $endDate){

                        foreach($day['times'] as $time){
                            $csv->getActiveSheet()->setCellValueByColumnAndRow($dayNum, $rowNum++, $time['start'].'-'.$time['end']);
                        }
                        $rowNum = 2;
                        $dayNum++;
                        $startDate += 86400;
                    }
                    else
                        break;
                }
            }

            $objWriter = PHPExcel_IOFactory::createWriter($csv, 'Excel5');
            $objWriter->save(public_path('reports/'.$filename));

        } catch (\PHPExcel_Exception $e) {
            return null;
        }

        return $filename;
    }
}