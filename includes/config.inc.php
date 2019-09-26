<?php
//detect if the code is running on localhost or the live server
if($_SERVER['SERVER_NAME'] == "localhost"){
    //settings for the dev environment
    define("PROJECT_DIR", "/my-new-site/");
}else{
    //settings for live site
    define("PROJECT_DIR", "/");
}