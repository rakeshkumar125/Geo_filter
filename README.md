# Geo_filter

I have create the code that provide the location data within your radius. In this development i have use the following component.

+ Slim framework for the api services
+ google location suggestion & geo data.

# how to deploy application

+ download the zip 
+ create the folder (geo_data) on localhost.
+ create the database on menu & upload the mysql database dump.
+ service index.php configure the database configuration.

The Api services create in two scenario one of the non secure. Accessing non secure use index.html

+ enter the pincode or location
+ enter the radius (in KM)
+ then press the get data button

you will see the following changes below.

+ The URL that provide the actual service
+ The json data that actual required that can be use for the various purpose.

For securing the api various ways. I write the techniques which i know as follows.
+ Token based with time 
+ Header security
+ request limit
 
Presently in this application i only implement token based with time. For accessing secure way i have created the file secure.html. When you execute that url. then following fields are shown.
+ login panel
+ Enter username (ravi)
+ Enter Password (test)

Then a panel are shown. which is same like the index.html.But there is token pre filled. With the help of token you can get the data.
The token valid for the 30 minute.

Contact Us
+ rakeshkumar_125@yahoo.in (email)
+ 8800201217 (India)
+ rakeshkumar_125 (Skype)

