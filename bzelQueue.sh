#! /bin/sh

pid=`ps -ef | grep think | grep "$*" | grep -c -v grep`

#HOUR=$(date +%k)
#MINUTE=$(date +%M)
#
#if [ $HOUR == 19 ] && [ $MINUTE -le 5 ]; then
#ps -ef | grep "$*" | grep -c -v grep | awk '{print $2}' | xargs kill -9
#fi

if [ $pid -ge 1 ]; then
   exit 0
fi

#if [ $# -lt 2 ]; then
#    exit 0
#fi

logfile=/data/www/default/bzel/xcx/runtime/cli/$1.$(date +"%Y%m%d").txt

if [ ! -f $logfile ]; then
   touch $logfile
   chown www:www $logfile
fi

# 保持与WEB一样的账号运行，以免文件权限产生问题
#if [ `whoami` != 'www' ] || [ `id -u` -lt 500 ]; then
#    sudo -u www -g www $0 $*
#    exit
#fi

sudo -u www -s /bin/sh -c "/data/app/php/bin/php -c /data/app/php/lib/php.ini -d memory_limit=256M /data/www/bzel/xcx/think queue:work --queue $* >> $logfile"