REST Api for LNO mobile app

Built with Yii 2.0.42.1

Changelog
------------
7/18/21 Venue search filter has lat/lng

4/6/21 Bootstrap 4  
For bs4 support, need to edit config/params.php

2018/10/08

- Scrape reverbnat1on
- Overall improvement

2018/10/05

- Attr for venue, band and event, to save JSON data into Mysql8

INSTALL
--------
Copy .gitignored files: `config/db.php` `config/params.php` `api/config/params.php` `web/index.php` 
`api/index.php`


`chmod -R 777 web/assets && chmod -R 777 runtime`  
`cp -aR vendor/bower-asset ./vendor/bower`  
`cp -aR vendor/npm-asset ./vendor/npm`

If yii2helper is not installed automatically:
`cd yii2helper` and `git clone git@github.com:brian3t/yii2helper.git .`

```
mkdir ./soc
git submodule add git@gitlab.com:brian3t/yii-user.git soc/yiiuser
```
In composer.json , configure autoload PSR

Refer to DEVNOTE.md for additional setup

CRONJOBS
--------
```
#05/21/24:
0 23 * * * /var/www/lnoapi/yii oshin/prune-data
10 0 * * * /var/www/lnoapi/yii oshin/daily-tasks
0 0 * * * /var/www/lnoapi/yii dl/scrape-reverb-allcities #Brian note future: fix code so that scrape-reverb-all also works
15 0 * * * /var/www/lnoapi/yii dl/scrape-tickmas
30 0 * * * /var/www/lnoapi/yii dl/scrape-sdr-all
```

Screenshot:

![alt tag](http://i.imgur.com/NyNASU9.png)

+ Steps to scrape SDR:
  + dl/scrape-sdr
  + dl/pull-event-sdr
  + dl/venue-addr-sdr
  + magic/pull-lat-lng

+ Steps to scrape Reverb:
  + dl/scrape-reverb-all

+ tool:
  + bbot populate event_comment
+ API endpoints:
  + user/signup: 
  + user/signin: {username, pw}
