@echo off

set APP_NAME=test
set EMBEDER=..\embeder.exe

echo ** %APP_NAME% - Creating %APP_NAME%.exe **

del %APP_NAME%.exe
%EMBEDER% new %APP_NAME%
%EMBEDER% main %APP_NAME% main.php
%EMBEDER% add %APP_NAME% include.inc
%EMBEDER% add %APP_NAME% dat/data.dat
