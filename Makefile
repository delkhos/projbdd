runserv:
	~/.local/sbin/lighttpd -f ~/.local/etc/lighttpd/lighttpd.conf

rundb:
	postgres -D ~/postgre/data &

runcgi:
	/home/gabrielj/.local/bin/php-cgi -b 1030

log:
	less /home/gabrielj/.local/var/log/lighttpd/error.log
clearlog:
	true > /home/gabrielj/.local/var/log/lighttpd/error.log
