# OT2project
Project for Ohjelmistotuotanto 2 course.

## DEV-environment

To test your code, use docker-based web server.
- Install Docker (and possibly docker-compose)
- Run ```docker compose up -d --force-recreate```
- Now the folder ```src``` can be accessed by [http://localhost](http://localhost)
- Files inside ```src```-folder can be accessed, for example [http://localhost/index.html](http://localhost/index.html)
- Local database can be viewed using ```phpMyAdmin``` sql-client running on [http://localhost:8080](http://localhost:8080)