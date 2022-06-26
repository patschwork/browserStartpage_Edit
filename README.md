# browserStartpage_Edit
Web UI for edit content (data.json) for the tool browserStartpage (GitHub: https://github.com/saschadiercks/browserStartpage)

# Why
I wanted to be able to edit the data.json and to download images for the items more comfortable.

# Tech-Stack
- Yii2 framework
- Yii2 extension dmstr/yii2-json-editor

# Screenshots
![Screenshot 1](attic/screenshots/001_edit.png)
![Screenshot 2](attic/screenshots/002_download_image_example.png)

# Install
You need to have setup PHP and composer

Open a cmd-shell (actually only Linux and Mac was used)
```sh
cd <folder_you_want>

# Get vanilla Yii2
composer create-project --prefer-dist yiisoft/yii2-app-basic browserStartpage_edit

# Get extension
cd browserStartpage_edit
composer require --prefer-dist dmstr/yii2-json-editor "*"

# Change to a temp folder
cd /tmp

# Clone this repo
git clone https://github.com/patschwork/browserStartpage_Edit.git browserStartpage_Edit_github_tmp

# Copy + overwrite the content (controller, views, ...)
cd browserStartpage_Edit_github_tmp
cp -R * <folder_you_want>/browserStartpage_edit/
```

You may erase `/tmp/browserStartpage_Edit_github_tmp` now

In my environment the content of browserStartpage_edit is in the subfolder of browserStartpage:
```
├── assets
│   ├── css
│   ├── js
│   ├── qr-codes
│   └── thumbnails
├── browserStartpage_edit   <-------------
│   ├── assets
│   ├── commands
│   ├── config
│   ├── controllers
│   ├── mail
│   ├── messages
│   ├── models
│   ├── runtime
│   ├── tests
│   ├── vagrant
│   ├── vendor
│   ├── views
│   ├── web
│   └── widgets
├── config
└── data
```
# Setup
To work properly, the tool need some adjustments

Open the file `/browserStartpage_edit/config/params.php`
and set the parameter to your environment
```php
<?php

return [
    'adminEmail' => 'admin@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'data_file_path' => '/tmp/data.json',    // <--------------------- e.g. <folder_of_browserStartpage>/data/data.json
    'downloadpath_logos' => '/tmp/',         // <--------------------- e.g. <folder_of_browserStartpage>/assets/thumbnails/
];
```
# Test / Run
PHP is able to run a local dev server.

Go to the folder of <browserStartpage_edit>

```sh
./yii serve
```

Open a browser with the adress http://localhost:8080

# Notes
The tool makes use of the json-editor. This may not be perfect, but it's enough of the most cases. You can also copy (and re-paste) the raw JSON to an IDE which has good JSON capabilities 😉
