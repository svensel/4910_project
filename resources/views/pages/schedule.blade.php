@extends('layouts.general')

@section('content')
<!DOCTYPE html>
<html>
<script type="text/javascript">
    function AlertIt(rows,cols) {
        var day;
        switch(cols) {
            case 1:
                day = "Sunday";
                break;
            case 2:
                day = "Monday";
                break;
            case 3:
                day = "Tuesday";
                break;
            case 4:
                day = "Wednesday";
                break;
            case 5:
                day = "Thursday";
                break;
            case 6:
                day = "Friday";
                break;
            case 7:
                day = "Saturday";
                break;
        }
        var time;
        switch(rows)
        {
            case 0:
                time = "6:00AM";
                break;
            case 1:
                time = "7:00AM";
                break;
            case 2:
                time = "8:00AM";
                break;
            case 3:
                time = "9:00AM";
                break;
            case 4:
                time = "10:00AM";
                break;
            case 5:
                time = "11:00AM";
                break;
            case 6:
                time = "12:00PM";
                break;
            case 7:
                time = "1:00PM";
                break;
            case 8:
                time = "2:00PM";
                break;
            case 9:
                time = "3:00PM";
                break;
            case 10:
                time = "4:00PM";
                break;
            case 11:
                time = "5:00PM";
                break;
            case 12:
                time = "6:00PM";
                break;
            case 13:
                time = "7:00PM";
                break;
            case 14:
                time = "8:00PM";
                break;
            case 15:
                time = "9:00PM";
                break;
            case 16:
                time = "10:00PM";
                break;
            case 17:
                time = "11:00PM";
                break;
            case 18:
                time = "12:00AM";
                break;
            case 19:
                time = "1:00AM";
                break;
            case 20:
                time = "2:00AM";
                break;
            case 21:
                time = "3:00AM";
                break;
            case 22:
                time = "4:00AM";
                break;
            case 23:
                time = "5:00AM";
                break;

        }
        //display alert to the user when they click on a highlighted box giving them Time/Day info and amount of time available
        var answer = confirm ("Your group has time to meet at " + time + " on " + day + "for X amount of minutes!");

    }
</script>
<head>
    <?php

    //get the current date
    $dto = new DateTime();
    $year = $dto->format("Y");
    $week = $dto->format("W");
    $dto->setTime(0,0,0);

    //starting day of the week
    $result['start'] = $dto->setISODate($year, $week, 0)->format('Y-m-d\-TH:i:sP');
    $dto->setTime(23,59,59);

    //ending day of the week
    $result['end'] = $dto->setISODate($year, $week, 6)->format('Y-m-d\-TH:i:sP');

    $numDaysInMonth = 0;
    $date = date("F j, Y, g:i a");
    $today = (explode(" ", $date, 3));
    $startDate = (explode("-",$result['start'],4));
    $today[1] = rtrim($today[1], ',');

    //figure out how many days of the month there are so you know when to rollover month end dates
    //for example when displaying a week that overlaps 2 months, I need to know when to start a new month
    switch($startDate[1])
    {
        case 1:
            $numDaysInMonth = 31;
            break;
        case 2:
            $numDaysInMonth = 28;
            break;
        case 3:
            $numDaysInMonth = 31;
            break;
        case 4:
            $numDaysInMonth = 30;
            break;
        case 5:
            $numDaysInMonth = 31;
            break;
        case 6:
            $numDaysInMonth = 30;
            break;
        case 7:
            $numDaysInMonth = 31;
            break;
        case 8:
            $numDaysInMonth = 31;
            break;
        case 9:
            $numDaysInMonth = 30;
            break;
        case 10:
            $numDaysInMonth = 31;
            break;
        case 11:
            $numDaysInMonth = 30;
            break;
        case 12:
            $numDaysInMonth = 31;
            break;

    }

    //array of the dates for the week... ie: For november the array may contain 26,27,28,29,30,1,2 for the dates that will occur
    //for the current week
    $arrayDates;
    $index = 0;
    $count = 1;
    $rows = 0;
    $cols = 0;
    //populate the array with the dates
    for($index = 0; $index < 7; $index++)
    {
            //if current day is greater than the # days in month then the month is over
            // use the count variable to signify a new month and use this value as the date from now on
            if(($startDate[2] + $index) > $numDaysInMonth)
            {
                    $arrayDates[$index] = $count;
                    $count++;
            }
            else
            {
               $arrayDates[$index] =  $startDate[2] + $index;
            }
    }
    for($rows = 0; $rows < 24; $rows++)
    {
        for($cols = 0; $cols < 8; $cols++)
        {
            $testArray[$rows][$cols] = $rows . $cols;
           //this is where I can insert data into the table spots
        }
    }

    ?>
<!---
//random css i was able to scrape off the net
-->
    <style>
        * {box-sizing: border-box;}
        ul {list-style-type: none;}
        body {font-family: Verdana, sans-serif;}

        h1 {
            padding: 25px 25px;
            width: 100%;
            background: #ffff;
            text-align: center;
        }
        .month {
            padding: 30px 25px;
            width: 100%;
            background: #1abc9c;
            text-align: center;
        }

        .month ul {
            margin: 0;
            padding: 0;
        }

        .month ul li {
            color: white;
            font-size: 40px;
            text-transform: uppercase;
            letter-spacing: 3px;
        }

        .month .prev {
            float: left;
            padding-top: 10px;
        }

        .month .next {
            float: right;
            padding-top: 10px;
        }



        /* Add media queries for smaller screens */
        @media screen and (max-width:720px) {
            .weekdays li, .days li {width: 13.1%;}
        }

        @media screen and (max-width: 420px) {
            .weekdays li, .days li {width: 12.5%;}
            .days li .active {padding: 2px;}
        }

        @media screen and (max-width: 290px) {
            .weekdays li, .days li {width: 12.2%;}
        }
        table {
            padding: 70px 25px;
            width: 100%;
        }

        th {

            text-align: center;
            height: 50px;
        }


    </style>
</head>
<body>

<h1>Recommended Group Meeting Times</h1>

<div class="month">
    <ul>

        <li>
            <?=$today[0]?><br>
            <span style="font-size:18px"><?=$today[1]?></span>
        </li>
    </ul>
</div>


<style type="text/css" >
    table.GeneratedTable {
        width:100%;
        background-color:#FFFFFF;
        border-collapse:collapse;border-width:1px;
        border-color:#336600;
        border-style:solid;
        color:#009900;
    }
    table.GeneratedTable tr {
        padding:3px;
        text-align: center;
        height: 100px;
    }
    table.GeneratedTable td, table.GeneratedTable th {
        border-width:1px;
        border-color:#336600;
        border-style:solid;
        padding:3px;
        text-align: center;
    }
    table.GeneratedTable td.valid
    {
        background-color:#000000;
    }
    table.GeneratedTable thead {
        background-color:#CCFF99;
    }
</style>

<!-- HTML Code -->
<table class="GeneratedTable">
    <thead>
    <tr>
        <th>Day</th>
        <th>Sunday</th>
        <th>Monday</th>
        <th>Tuesday</th>
        <th>Wednesday</th>
        <th>Thursday</th>
        <th>Friday</th>
        <th>Satuday</th>

    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Date/Time</td>
        <td><?=$arrayDates[0]?></td>
        <td><?=$arrayDates[1]?></td>
        <td><?=$arrayDates[2]?></td>
        <td><?=$arrayDates[3]?></td>
        <td><?=$arrayDates[4]?></td>
        <td><?=$arrayDates[5]?></td>
        <td><?=$arrayDates[6]?></td>

    </tr>

    <?php

        //generate the html table with the acceptable meeting times from the array of times from above
        $rows = 0;
        $cols = 0;
        $time = 6;
        $AmPm = "AM";
            for($rows = 0; $rows < 24; $rows++)
            {
                echo "<tr>";
                for($cols = 0; $cols < 8; $cols++)
                {
                    if($cols == 0)
                    {
                        if($time == 12)
                        {
                            if($AmPm == "AM")
                            {
                                $AmPm = "PM";
                            }
                            else
                            {
                                $AmPm = "AM";
                            }
                            echo "<td id = " .$rows . $cols . ">" . $time . ":00 " . $AmPm . "</td>";
                            $time = 0;
                        }
                        else
                        {
                            echo "<td id = " . $rows . $cols . ">" . $time . ":00 " . $AmPm . "</td>";

                        }
                    }
                    else
                    {
                        if($testArray[$rows][$cols]%3 == 0)
                        {

                            //this is where I use the data from above
                            //highlight groups of time that the group could meet and provide a link
                            //that opens an alert describing the event
                            echo "<td class = \"valid\" id = " .$rows . $cols . "><a href=\"javascript:AlertIt($rows,$cols);\">click me</a> </td>";

                        }
                        else
                        {
                            echo "<td id = " . $rows . $cols . ">" ."</td>";
                        }
                    }


                }
                echo "</tr>";
                $time += 1;
            }


        ?>
    </tbody>
</table>
</body>

</html>

@endsection