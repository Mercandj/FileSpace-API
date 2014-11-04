J.A.R.V.I.S
=====================

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

* /file/ 			GET 	= List
* /file/ 			POST 	= add file (intput json file, output json id file)
* /file/:id 		GET 	= get file (output json bdd infos include ddl url)
* /file/:id 		PUT 	= update file
* /file/:id  		DELETE 	= delete file

For each file request : basic-authentication token:empty

* /user/login 		POST 	= (input basic-authentication login:pass, output token)
* /user/register 	POST 	= (input json user, output token)


## ANDROID DESCRIPTION

Remote and secure file manager. (upload files + download files + management)

* Android : API supported : 14 to 21 (Lollipop)
* Required : PHP server with PHP>5 support
* Theme : API<21 Holo.Light and API>=21 Material !!!!


## DEVELOPER

* Mercandalli Jonathan (Front Android + Rest API)
* Thibault Patois (Front Web + Rest API)


## LICENSE

OpenSource : just mention developer name if you use the code.