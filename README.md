# FusionPBX-Api
RestAPI for fusionpbx

## Install
cd into /var/www/fusionpbx
git clone this repo

cd into /var/www/fusionpbx/app
make a directory called 'api' and place an empty file named 'api_config.php' in there.  
So you should have a directory like this:  
/var/www/fusionpbx/app/api/api_config.php

Log into your fusionpbx, go to the user you want to have api access, where it says API Key, click generate.

That is now your key 

## Usage
Return all extensions accross all domains  
https://your-fusion-address/restapi/extensions.php?key=YOURKEY  

## API Calls
WIP

