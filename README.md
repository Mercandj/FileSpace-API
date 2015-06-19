FileSpace - API
=====================

**_Unfinished project, still in development_**


## PROJECT DESCRIPTION

* Name : FileSpace
* Description : Remote file manager + Home automation with Raspberry Pi
* Front : [Android](https://github.com/Mercandj/FileSpace-Android) & [Web](https://github.com/Mercandj/FileSpace-Angular)
* Back : Rest API PHP
* Location : Paris
* Starting Date : October 2014
* Configuration : /config/config.json
* Install data base : /database/script.sql
* Tested on Apache
* Optional robotics : Raspberry Pi with this [project](https://github.com/projectweekend/Pi-GPIO-Server)


## REST API ROUTES

For each file request : basic-authentication token:empty

* File Controller

|Root             | Method   | Description                 | Input                      | Output
|-----------------|----------|-----------------------------|----------------------------|-----------------------------
| /file/          | GET 	 | Get list of files (bdd)     |                            | jsonArray files (include ids)
| /file/          | POST     | Add file (bdd + physic)     | 'url','visibility','file'  | json file id
| /file/:id       | GET      | Get file (physic)           |                            | real file (download)
| /file/:id       | PUT      | Update file (bdd + physic)  |                            | 
| /file/:id       | DELETE   | Delete file (bdd + physic)  |                            |

* User Controller

|Root             | Method   | Description   | Input                           	| Output
|-----------------|----------|---------------|----------------------------------|-----------
| /user     	  | GET      |               | basic-authentication login:pass 	| json
| /user			  | POST 	 |               | 'username','password'            | json token

* Robotics Controller

|Root             | Method   | Description                   | Input            	| Output
|-----------------|----------|-------------------------------|----------------------|-----------
| /robotics/:id	  	  | GET      | Get the raspberry pin status  | pin id (url)       	| json
| /robotics/:id	      | PUT      | Set the raspberry pin status  | pin id (url)       	| json

* Information Controller

|Root             | Method   | Description   | Input                           	| Output
|-----------------|----------|---------------|----------------------------------|-----------
| /information	  | GET      |               |                               	| json


## DEVELOPERS

* Mercandalli Jonathan ([Front Android](https://github.com/Mercandj/FileSpace-Android) + [Front Web Beta](https://github.com/Mercandj/FileSpace-Angular) + Rest API)
* 7h1b0 (Rest API)


## LICENSE

OpenSource : just mention developer name if you use the code.