# File sharing website
A file sharing website written in PHP. You can find it here : https://pleaserape.me/<br />
<img src="https://i.imgur.com/YwXKhIb.png" />

- [Requirements](#requirements)
- [Installation](#how-to-install-it)
- [Features](#some-cool-features)
- [API](#api)

## Requirements
* A web server (developped with Apache, you will find a .htaccess file), make sure you have your "rewrite engine" on
* PHP>=7.0
* MySQL server

## How to install it
* First of all, install the website with these commands
  ```
  git clone https://github.com/JamesMcAvoy/File-sharing-website.git
  composer install
  ```
* Change the parameters in the config.json file and in the index.php file (in the public folder) as you want.
  Since you cannot change the ```upload_max_filesize``` parameter directly in the config file/script, you have to be sure that the value of ```"uploadMaxSize"``` is smaller than ```upload_max_filesize``` on your php.ini file (by default 2MB).
* Add a public and a private recaptcha key.
* Then, run ```schema.sql``` into your MySQL database.

## Some cool features
* Per account upload limit
* Files are hashed to detect identical files. A blob is created for every file uploaded, if two identical files are uploaded, the file created in the database will link to the same blob.
* File caching from database
* Progress bar when file uploading
* API for uploading, deleting, and more, files (see below)

## API
* ```/api/upload```<br />
  POST method<br />
  Content-type : multipart/form-data<br />
  Parameters :
	* file : your file to upload
	* apikey : your apikey

  Example : <img src="https://i.imgur.com/oMpjKpt.png" width="450" />
* ```/api/getUploads```<br />
  GET method<br />
  Parameters :
	* offset : the index to get your files
  * A cookie header named from your config.json with your API key

  Example : /api/getUploads?offset=1

### To-do
* Admin page
* Delete auto files
* Mark as important your files
* Reset API key/password
* Info popup
* Search for a file