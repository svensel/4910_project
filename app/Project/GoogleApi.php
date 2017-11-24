<?php

namespace App\Project;
use DateTime;
use Google_Client;
use Google_Service_Calendar;
use Illuminate\Support\Facades\DB;

/*
 * This class should contain functions for interacting with the google api for calendars
 * You likely need a login function that passes the users' credentials to the api
 * */

 //TODO: Fix for if somebody doesn't have google calendar??
class GoogleApi
{
    private $refreshToken;
    private $accessToken;
    private $googleClient;
    private $calendarService;
    private $uid;

    private function refresh_token() {
        //check if not expired
        if ($this->googleClient->isAccessTokenExpired()) {
            $this->googleClient->refreshToken($this->refreshToken);
            $this->accessToken = $this->googleClient->getAccessToken();
            DB::update('UPDATE users SET access_token = ? WHERE id = ?',
             [base64_encode(serialize($this->accessToken)), $this->uid]);
        }
    }

    public function __construct($uid){
        //fetch the token from the user
        $user = DB::select('SELECT * FROM users where id = ?', [$uid]);

        if (!empty($user[0]->access_token)) {
            $this->uid = $uid;
            $this->accessToken = unserialize(base64_decode($user[0]->access_token));
            $this->refreshToken = $user[0]->refresh_token;
            $this->googleClient = new Google_Client();
            $this->googleClient->setAccessType('offline');
            $this->googleClient->setAuthConfig('../../client_secrets.json');
            $this->googleClient->addScope(Google_Service_Calendar::CALENDAR);
            $this->googleClient->setAccessToken($this->accessToken);
            $this->refresh_token();
            $this->calendarService = new Google_Service_Calendar($this->googleClient);
        } else {
            $this->$uid = -1;
        }
    }

    private function get_current_week() {
        $dto = new DateTime();
        $year = $dto->format("Y");
        $week = $dto->format("W");
        $dto->setTime(0,0,0);
        $result['start'] = $dto->setISODate($year, $week, 0)->format('Y-m-d\TH:i:sP');
        $dto->setTime(23,59,59);
        $result['end'] = $dto->setISODate($year, $week, 6)->format('Y-m-d\TH:i:sP');
    }

    public function fetch_events() {
        //no user (return NULL)
        if ($uid = -1) {
            return NULL;
        }
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
        $events = $this->calendarService->events->listEvents('primary', $params);
    }
} 