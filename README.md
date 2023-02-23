Project Setup Info

Requirements: PHP 8.1 or higher

Clone the project by using the following command.

git clone https://github.com/hamzairshaddev/Test-App.git

Now run the following command in project's directory

composer install

Create a .env file with and copy the contents from .env.example file to that .env file. Also copy your X_RapidAPI_Key to .env file in X_RapidAPI_Key path.

Now run the following command in project's directory

php artisan key:generate

php artisan config:clear

Database:

Create a database using mysqlAdmin. You can use any name and change it in your .env file by changing DB_HOST
and if you have any username or password set to access mysql, you need to change those in .env file too.

Now run the following commands in project's directory

php artisan migrate

php artisan serve

Go to http://localhost:8000, if your port is not 8000 use whatever port your command line is showing.

Queues:

For emails to work, you can change the credentials in .env file. I am using mailtrap for testing purposes. You need to change email credentials if you want it to work.

Run the background queues using this command to send emails.

php artisan queue:work --queue=emails

Testing:

To run both unit and feature test, use the following command in project's directory

php artisan test

To run only unit tests, use the following command

php artisan test --filter QuotesApiEmailTest

To run only feature tests, use the following command

php artisan test --filter HistoricalQuotesTest

Thanks!

