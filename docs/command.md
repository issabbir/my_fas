DB Grant:
----------
grant select  on  PMIS.L_GEO_THANA to ams 

Clear
-----------------------
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
composer dump-autoload



Command to start serve
-----------------------
php artisan serve --host=192.168.78.10 --port 5131 &
php artisan serve --host=210.4.76.133 --port=5131 &

exit

Command to kill/stop serve:
ps -ef | grep "$PWD/server.php"

[root@dev-deeds brta_arch]# root      6817  6813  0 17:14 ?        00:00:00 /usr/bin/php -S 192.168.78.10:5000 /var/www/brta_arch/server.php
bash: root: command not found...
[root@dev-deeds brta_arch]# root      7032  6973  0 17:17 pts/2    00:00:00 grep --color=auto /var/www/brta_arch/server.php

kill 6817



---------------------
http://210.4.76.133:5307/api/v1/mail-send-api
http://210.4.76.133:5307/api/v1/sms-send-api
