J.A.R.V.I.S
=====================

**_Unfinished project, still in development_**

<p align="center">
<img src="https://raw.github.com/Mercandj/Jarvis/master/screenshot/1.png" width="250" />
</p>

## PROJECT DESCRIPTION

* Name : Jarvis (Just A Remote Very Intelligent System)
* Android App Description : Frontend, remote control
* PHP : Rest API
* Location : Paris
* Starting Date : October 2014


## REST API ROUTES

For each file request : basic-authentication token:empty

* File Controller

|Root             | Method   | Description                 | Input           | Output
|-----------------|----------|-----------------------------|-----------------|-----------------------------
| /file/          | GET 	 | Get list of files (bdd)     |                 | jsonArray files (include ids)
| /file/          | POST     | Add file (bdd + physic)     | 'json' + 'file' | json file id
| /file/:id       | GET      | Get file (physic)           |                 | real file (download)
| /file/:id       | PUT      | Update file (bdd + physic)  | 'json'          | 
| /file/:id       | DELETE   | Delete file (bdd + physic)  |                 |

* User Controller

|Root             | Method   | Description   | Input                           | Output
|-----------------|----------|---------------|---------------------------------|-----------
| /user/login     | POST     |               | basic-authentication login:pass | json token
| /user/register  | POST 	 |               | 'json'.'user'                   | json token


## ANDROID DESCRIPTION

Remote and secure file manager. (upload files + download files + management)

* Android : SDK supported : 14 (Ice Cream Sandwich) to 21 (Lollipop)
* Required : PHP server with PHP>=5.4 support
* Theme : Before Android 5.0 Lollipop Holo.Light, after Material.Light !!!!


## DEVELOPERS

* Mercandalli Jonathan (Front Android + Rest API)
* 7h1b0 (Front Web + Rest API)


## LICENSE

OpenSource : just mention developer name if you use the code.