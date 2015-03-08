J.A.R.V.I.S - API
=====================

**_Unfinished project, still in development_**


## PROJECT DESCRIPTION

* Name : Jarvis (Just A Remote Very Intelligent System)
* Description : Remote file manager + Home automation with Raspberry Pi
* Front : [Android](https://github.com/Mercandj/Jarvis-Android) & [Web](https://github.com/Mercandj/Jarvis-Angular)
* Back : Rest API PHP
* Location : Paris
* Starting Date : October 2014
* Configuration : /config/config.json
* Install data base : /bdd/script.sql
* Tested on Apache
* Optional for home automation : Raspberry Pi with this [project](https://github.com/projectweekend/Pi-GPIO-Server)


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

* Home Controller

|Root             | Method   | Description                   | Input            	| Output
|-----------------|----------|-------------------------------|----------------------|-----------
| /home/:id	  	  | GET      | Get the raspberry pin status  | pin id (url)       	| json
| /home/:id	      | PUT      | Set the raspberry pin status  | pin id (url)       	| json

* Information Controller

|Root             | Method   | Description   | Input                           	| Output
|-----------------|----------|---------------|----------------------------------|-----------
| /information	  | GET      |               |                               	| json


## DEVELOPERS

* Mercandalli Jonathan ([Front Android](https://github.com/Mercandj/Jarvis-Android) + [Front Web Beta](https://github.com/Mercandj/Jarvis-Angular) + Rest API)
* 7h1b0 (Rest API)


## LICENSE

OpenSource : just mention developer name if you use the code.