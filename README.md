# File sharing website
This is a file sharing website.
<img src="https://i.imgur.com/3a9k0c2.png" />

## Installation
```
git clone https://github.com/JamesMcAvoy/File-sharing-website.git
composer install
```
Since you can not change the ```upload_max_filesize``` parameter directly in the config file/script, you have to be sure that the value of ```"accountMaxSize"``` is smaller than ```upload_max_filesize``` on your php.ini file (by default 2MB).

## Dependencies
* PHP>=7
* Make sure you have your "rewrite engine" on (an .htaccess is in the public folder for Apache users)

### To-do
* Admin page
