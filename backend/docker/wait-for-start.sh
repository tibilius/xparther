#!/bin/bash

set -e

until [[ $(curl --write-out %{http_code} --silent --output /dev/null https://${INSTANCE_NAME}.aitarget.com/user/login) = '200' ]]; do
  >&2 echo "Instance is unavailable - sleeping"
  sleep 5
done

>&2 echo "Instance is up"
