# PHP-Mailer

This project uses php and a mailing vendor library to allow logged in users to schedule email sending.

This Project was a class project for COMP 484 (web development) taught by Ilia Benson at California State University, Northridge.

## How to install and run

In order to run PHP-Mailer we must deploy an apache or NginX server using a service such as WAMP/MAMP. Additionally we have to set up a database to store user credentials, and scheduled emails.

Once your server is set up, create a database named mailerDB and run the mailer.sql file on it to restore the necessary tables.

In order to establish a proper connection you must add your database credentials to mailer.php on lines 19 & 20 and mailerForm.php on lines 49 & 50.

Once your database connection is set up, you have to add your login credentials to an email service of your choosing on the mailer.php file. To do this add your email to line 8 and your one time password you set up with your gmail on line 10. If you prefer to use another service you must also change the 'smtp.gmail.com' on line 12 to the service you choose.

Once your forms are properly functioning and pushing data to mailerDB, in order for emails to be sent the mailer.php file must be ran. This can be done by manually loading the file on a client page, or using a CRON job to automatically run mailer.php ever 30 minutes.

Details for creating a CRON job: http://www.thesitewizard.com/general/set-cron-job.shtml

## Demo

https://user-images.githubusercontent.com/99862145/209279675-a0236e66-a39c-4f98-926e-9174946e5fd0.mov
