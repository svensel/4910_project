<?php

namespace App\Project;
use DateTime;
use Google_Client;
use Google_Service_Calendar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GoogleApi
{
    private $refreshToken;
    private $accessToken;
    private $googleClient;
    private $calendarService;
    private $events = array();
    private $invalidState = false;
    private $uid;

    private function refresh_token()
    {
        //check if not expired
        if ($this->googleClient->isAccessTokenExpired()) {
            $this->googleClient->refreshToken($this->refreshToken);
            $this->accessToken = $this->googleClient->getAccessToken();
            DB::update('UPDATE users SET access_token = ? WHERE id = ?',
             [base64_encode(serialize($this->accessToken)), $this->uid]);
        }
    }

    public function __construct($uid)
    {
        //fetch the token from the user
        $user = DB::select('SELECT * FROM users where id = ?', [$uid]);

        try{
            if (!empty($user[0]->access_token)) {
                $this->uid = $uid;
                $this->accessToken = unserialize(base64_decode($user[0]->access_token));
                $this->refreshToken = $user[0]->refresh_token;
                $this->googleClient = new Google_Client();
                $this->googleClient->setAccessType('offline');
                $this->googleClient->setAuthConfig(base_path('client_secrets.json'));
                $this->googleClient->addScope(Google_Service_Calendar::CALENDAR);
                $this->googleClient->setAccessToken($this->accessToken);
                $this->refresh_token();
                $this->calendarService = new Google_Service_Calendar($this->googleClient);
            } else {
                $this->$uid = -1;
            }
        }catch(\Exception $e){
            $this->invalidState = true;
        }
    }

    private function get_current_week($offset = 0)
    {
        $dto = new DateTime();
        $year = $dto->format("Y");
        $week = $dto->format("W") + $offset;
        $dto->setTime(0,0,0);
        $result['start'] = $dto->setISODate($year, $week, 0)->format('Y-m-d\TH:i:sP');
        $dto->setTime(23,59,59);
        $result['end'] = $dto->setISODate($year, $week, 6)->format('Y-m-d\TH:i:sP');
        return $result;
    }

    public function fetch_events()
    {
        $totalEvents = array();
        if(!Auth::user()->google_cal_access || $this->uid == -1)
            return NULL;

        if($this->invalidState)
            return NULL;

        //refresh the current token
        $this->refresh_token();
        //get the start and end of the week (sunday to saturday)
        $week = $this->get_current_week();
        //set request params
        $params = [
            'singleEvents' => true,
            'timeMax' => $week['end'],
            'timeMin' => $week['start'],
        ];
        //fetch these events
        $events = $this->calendarService->events->listEvents('primary',$params);

        foreach($events->items as $event){
            list($startDate, $startTime) = preg_split('[T]', $event->start->dateTime);
            list($endDate, $endTime) = preg_split('[T]', $event->end->dateTime);

            $this->events[] = [
                'startDate' => $startDate,
                'endDate' => $endDate,
                'startDayName' => date('D', strtotime($startDate)),
                'endDayName' => date('D', strtotime($endDate)),
                'startTime' => preg_split('[-]', $startTime)[0],
                'endTime' => preg_split('[-]', $endTime)[0],
            ];
        }
        //add this week [0] to the array
        $totalEvents[] = $this->events;
        $this->events = [];

        //get the next week
        $week = $this->get_current_week(1);
        //set request params
        $params = [
            'singleEvents' => true,
            'timeMax' => $week['end'],
            'timeMin' => $week['start'],
        ];
        //fetch these events
        $events = $this->calendarService->events->listEvents('primary',$params);

        foreach($events->items as $event){
            list($startDate, $startTime) = preg_split('[T]', $event->start->dateTime);
            list($endDate, $endTime) = preg_split('[T]', $event->end->dateTime);

            $this->events[] = [
                'startDate' => $startDate,
                'endDate' => $endDate,
                'startDayName' => date('D', strtotime($startDate)),
                'endDayName' => date('D', strtotime($endDate)),
                'startTime' => preg_split('[-]', $startTime)[0],
                'endTime' => preg_split('[-]', $endTime)[0],
            ];
        }

        //add the other week [1] to the array
        $totalEvents[] = $this->events;

        //return the whole thing
        return $totalEvents;
    }
} 