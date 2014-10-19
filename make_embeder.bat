@echo off

set APP_NAME=embeder
set EMBEDER=php embeder.php

echo ** %APP_NAME% - Creating %APP_NAME%.exe **

del %APP_NAME%.exe
%EMBEDER% new %APP_NAME%
%EMBEDER% main %APP_NAME% embeder.php
%EMBEDER% add %APP_NAME% out/console.exe
