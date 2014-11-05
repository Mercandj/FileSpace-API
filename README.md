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

|Root             | Method   | Description   | Input                           | Output
|-----------------|----------|---------------|---------------------------------|-----------
| /file/          | GET 	 | List          |                                 |  
| /file/          | POST     | Add file      | json file                       | json id file
| /file?id=       | GET      | Get file      | file id                         | ddl file
| /file?id=       | PUT      | Update file   | file id                         |  
| /file?id=       | DELETE   | Delete file   | file id                         |  

* User Controller

|Root             | Method   | Description   | Input                           | Output
|-----------------|----------|---------------|---------------------------------|-----------
| /user/login     | POST     |               | basic-authentication login:pass | token
| /user/register  | POST 	 |               | json user                       | token


## ANDROID DESCRIPTION

Remote and secure file manager. (upload files + download files + management)

* Android : API supported : 14 to 21 (Lollipop)
* Required : PHP server with PHP>5 support
* Theme : API<21 Holo.Light and API>=21 Material !!!!


## DEVELOPERS

* Mercandalli Jonathan (Front Android + Rest API)
* Maybe : 7h1b0 (Front Web + Rest API)


## LICENSE

OpenSource : just mention developer name if you use the code.