# SingAlongJam

### Git
https://github.com/Damien-DeZurik/SingAlongJam.git

### Sheets API
https://console.cloud.google.com/apis/api/sheets.googleapis.com/metrics?project=singalongjam

### Development
Slim and Tools
`composer require slim/slim:"4.*"`
`composer require slim/psr7`
`composer require slim/php-view`
`composer require google/apiclient`
`composer require vlucas/phpdotenv`

VS Code with PHP
`brew services start php`

### Publishing 
In osilabs.com home dir there is a checkout called SingAlongJam

```sh
ssh ddezurik@osilabs.com
cd ~/SingAlongJam
git pull
composer install
composer update

cd ~/SingAlongJam/bin
bash ./publish.sh
```
