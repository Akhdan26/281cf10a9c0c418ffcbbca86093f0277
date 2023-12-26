Hello, my name is Muhammad Hifzhan Silmi (Akhdan).

Thank you Levart.id for giving me the opportunity to continue the Technical Coding Test. From the existing requirements, I have tried my best, but I am stuck on the Auth section for consuming API and Docker. Because this was my first experience, and previously I had only been a Backend Junior based on the Laravel Lumen framework. Hopefully you will continue this recruitment process, because I hope to be able to learn better and delve deeper into the world of Backend.


Here is short documentation for running the REST API that I have created:

- clone this project to your local
- open terminal in root folder, type 'composer install'
- type 'cp .env.exampe .env' it will create the new .env file with my credentials. Or adjust the contents of the .env file with your local credentials.
- open PG Admin, or use psql command. create new 'emails' table using this query :

  
- to run the api, you need to import the Levart.id.postman_collection.json to your Postman.
- open terminal again in root folder, type 'php -S localhost:3000 -t public'. so it will running PHP local server in port:3000 getting index.php file in /public folder.
- the REST API only for send email and listing all emails data from DB. so how this API works?
  1. If we send new email, the emais data such (email_to, subject, and message) are store in PSQL DB. And set the new Queueing message json data in RabbitMQ. So if you don't run the   
     worker.php file. the emails won't sending to recepient, but only store in PSQL DB.
