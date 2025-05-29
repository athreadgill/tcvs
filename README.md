# Treasury Check Verification System (TCVS) API Program
This is a program written in PHP and it connects to the Treasury Check Verification System's API in order to pull check data.

## Obtaining a TCVS API Key
To obtain a Treasury Check Verification System (TCVS) API key, financial institutions need to review the TCVS Terms and Conditions, provide their institution's name, complete the signature page, and submit the request via email to paymentintegrity@fiscal.treasury.gov. This process is necessary to gain access to the API portal, which allows for enhanced check verification, including payee name validation.

## Using the TCVS API Key
Ideally you should store your API Key in the httpd conf file on your server (if using Apache) using SetEnv API_KEY "XXXXXXXXXXXXXXXXXXXXX"
If you do not wish to store it there save it to the API/API_KEY.txt file as plain text and the program will grab it there instead. 

## Requirements
PHP Version 8 - Apache or NGINX

## Installation
Copy the tcvs folder to your servers httpd folder, set your API Key, and then go to the server URL/tcvs to start using the script.

