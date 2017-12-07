#4910 Scheduling Application Project
## Synopsis
This code repository is for a web application that is designed to make scheduling group meeting times for academics faster and easier. Please note that the following code is not meant to be a fully functional website, but merely a means to demonstrate the functionality of a scheduling application in the context of an acedemic setting.
## Motivation
After conducting a cognitive task analysis, we confirmed that students have difficulty making group schedules quickly. In order to avoid spending unnecessary time on discussing when people can/cannot meet, we seek to automate this by running an algorithm over group members class times and Google (TM) calendar events. Ideally this will make finding group meeting times a cinch!

## Contributors
Sam Vensel \
Mathew Ferguson \
Tyler McCall \
Greg Francis \
Ben Adams \
Connor Bolick 

## Installation
1. You must install the Laravel Homestead environment for Laravel version 5.5. Instructions can be found here: https://laravel.com/docs/5.5/homestead

2. Once you have installed the Homestead environment, clone this repository into your folder 
that is mapped to your Homestead environment.

3. Change the name of the 'env' file in the root directory of this project to '.env' (you may need to use an application like and IDE to do this.)

4. Log into your Homestead environment virtual machine and run the following commands inside the project folder:

    php artisan key:generate \
    composer update \
    php artisan migrate

    *These commands must be run in this order before you start navigating the project in the browser. Create users by navigating to your project's /register uri.
5. Use your homestead database to add relationships to courses, users, and groups.

##Tips
Use the "Help" on the project (in browser) to see how to use the project. \
In general, you will click the groups page then click 'generate schedule?' \
If you want to add Google (TM) events to the scheduler, then navigate to the settings page, allow access, then run the scheduler like previously described.
Follow the directions on these pages for more specific actions.

##Resources/References
Bootstrap SB Admin: https://startbootstrap.com/template-overviews/sb-admin/ \
Day Pilot Event Calendar: https://doc.daypilot.org/

## License
Copyright 4910 Scheduling Application Project 2017 

ABSOLUTELY NO WARRANTY

You may install and use the code for your pleasure, but you do not have the right to make modifications or changes to the code. 
You also do not have the right to distribute the code for monetary gain of any kind.