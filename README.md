## Backend Challenge

Here is a repo containing two microservices, the UserService or the Publisher and the NotificationService or the Subscriber. 
Bellow is a step by step guide of what the application does

- The UserService microservice contains a /users POST api.
- When a request to create a user with body email, firstName and lastName is recieved, the user is stored in the MYSQL database.
- An event get dispatched after the user is created.
- The event generated is dispatched and sent to the NotificationService using a brooker.
- The brooker service used is Activemq.
- The event sent to and received by the NotificationService contains the newly created user and its locked.

## Set up

NB: Host machine must have docker and docker-compose installed
NB: Maintain same code structure, NotificationService and UserService are being referenced in the docker-compose.yml file

- run "docker-compose up", this usually take a few minutes.
- Using any testing tool e.g postman, create a user, api is - http://127.0.0.1:8081/api/users
- Check daily logs on the NotificationService for the user that was just created.