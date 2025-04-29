#!/usr/bin/env sh

# PROJECT=${PWD##*/}  # get project directory name
HOST=ubuntu@server.memoreview.net
WEBROOT=/etc/easypanel/projects/apps/index-journal/volumes

rsync -r -p -t -u -z --checksum --exclude=".*" -P -h -i --delete $HOST:$WEBROOT/content ./

