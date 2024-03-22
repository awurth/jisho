#!/usr/bin/env bash
set -eo pipefail

uid=$(stat -c %u /project)
gid=$(stat -c %g /project)

sed -ie "s/$(id -u node):$(id -g node)/$uid:$gid/g" /etc/passwd

chown -R node:node /home/node

if [ $# -eq 0 ]; then
    sleep 9999d
else
    su node -s /bin/bash -c "$*"
fi
