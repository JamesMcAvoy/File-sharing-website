# File sharing website
This is a file sharing website.
<img src="https://i.imgur.com/3a9k0c2.png" />

## How to run it
First of all, install the website with these commands
```
git clone https://github.com/JamesMcAvoy/File-sharing-website.git
composer install
```
Change the config.json file and the index as you want.<br />
Since you cannot change the ```upload_max_filesize``` parameter directly in the config file/script, you have to be sure that the value of ```"uploadMaxSize"``` is smaller than ```upload_max_filesize``` on your php.ini file (by default 2MB). <br />
Then, run the database creation script.

## Dependencies
* PHP>=7
* MySQL database
* Make sure you have your "rewrite engine" on (an .htaccess is in the public folder for Apache users)

## Some cool features
* Per account upload limit
* Files are hashed to detect identical files. A blob is created for every file uploaded, if two identical files are uploaded, the file created in the database will link to the same blob.
* File caching from database
* API for uploading, deleting, and more, files (see below)

## API
* ```/api/upload```<br />
  POST method<br />
  Content-type : multipart/form-data<br />
  Parameters :
	* file : your file to upload
	* apikey : your apikey

  Example : <img src="https://i.imgur.com/oMpjKpt.png" width="450" />

### To-do
* Admin page
* Delete auto files
* Mark as important your files