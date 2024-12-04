<p align="center"><a href="https://www.yieldexchange.ca" target="_blank"><img src="/public/assets/images/logo_light.png" width="400"></a></p>


## About Yield Exchange

Yield Exchange is a platform for Canadian organizations to get the best rates for their GICs from Canadian financial institutions.

## Installation

Its built on Laravel. [Laravel documentation](https://laravel.com/docs/contributions).
The following are the steps to install and run it:-
- Install git https://git-scm.com/book/en/v2/Getting-Started-Installing-Git
- Clone the project ```git clone https://github.com/Yield-Exchange/Yield-Exchange.git``` and checkout to dev branch {```git checkout dev```}
- Install [Docker ](https://www.docker.com/products/docker-desktop) and open the docker app {ensure its running}
- Go to the project directory via terminal and run 
``` 
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
```
- Create a ```.env file``` on the main directory and copy the contents of ```.env.example```
- Update this variables in the .env file to {Note: if they do not exist, add them}
    ```
    APP_PORT=8000
    DB_CONNECTION=mysql
    DB_HOST=mysql
    DB_PORT=3306
    DB_DATABASE=yield_exchange_db
    DB_USERNAME=root
    DB_PASSWORD=devLocal
    BROADCAST_DRIVER=pusher
    RECAPTCHA_KEY=6LcE8cYZAAAAAN6ddUhsK6uwcUXfZvGKBYEl-2Qx
    SECRET_KEY=6LcE8cYZAAAAAN6ddUhsK6uwcUXfZvGKBYEl-2Qx
    ```
    
    
- Run ```./vendor/bin/sail build```
- Run ```./vendor/bin/sail up```
- Run this on a new terminal ```./vendor/bin/sail artisan key:generate```
- Go to your favourite browser and access the project via http://localhost:8000

## Database migrations
- open a new terminal, cd to project directory
- change directory(cd) to v1 folder and run { ``` cd v1 ```}
    ```
    docker run --rm \
                -u "$(id -u):$(id -g)" \
                -v $(pwd):/var/www/html/v1 \
                -w /var/www/html/v1 \
                composer install --ignore-platform-reqs
  ```
 - cd back to main dir and run each of the following commands { ``` cd ../ ```}
    ``` 
    ./vendor/bin/sail artisan config:cache
    ./vendor/bin/sail php ./v1/vendor/bin/phinx migrate --configuration ./v1/phinx.php
    ./vendor/bin/sail artisan migrate
    ./vendor/bin/sail php ./v1/vendor/bin/phinx seed:run --configuration ./v1/phinx.php
    ./vendor/bin/sail artisan db:seed
    ./vendor/bin/sail artisan create-updated:system-permissions
   ```
- Access the phpmyadmin via http://localhost:8081
        ```username=yie and password=devLocal```

Laravel is accessible, powerful, and provides tools required for large, robust applications.
