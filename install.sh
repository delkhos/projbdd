#!/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )"

# initdb -D $HOME/postgre/data
ln -s $DIR/www $HOME/www
ln -s $DIR/config/lighttpd.conf $HOME/.local/etc/lighttpd/lighttpd.conf

