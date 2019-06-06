# Wifi Statspage


## Installation
```
cd /var/www/wifi-statspage
composer install
```

The `.env.example` to `.env` and app key generation should be done automatically.
Edit the `.env` File to your needs (Local testing or production).

Then set up grump PHP to sniff commits.
```
 php ./vendor/bin/grumphp git:init --config=./grumphp.yml
```
To autofix possible Codestyle errors, use:
```
composer autofix-phpcs
```
Copy your service worker credentials (json File!) into the root directory of the project.

Start the debugserver with the command `php artisan serve` in the root directory of the Project.

## Usage
In a browser, open: 

```
127.0.0.1:8000/?objectId=XXXXX
```
where XXXX is a valid ObjectId.