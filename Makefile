runserv:
	~/.local/sbin/lighttpd -f ~/.local/etc/lighttpd/lighttpd.conf

rundb:
	postgres -D ~/postgre/data &

log:
	less /home/gabrielj/.local/var/log/lighttpd/error.log
