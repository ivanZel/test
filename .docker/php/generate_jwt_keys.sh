#!/bin/bash

PK_DIR=$1
PK="${PK_DIR}/private.pem"
JWT_PASSPHRASE=$2
if [ ! -f "$PK" ]; then
  echo 'Generating keys pair...'
  mkdir "$PK_DIR"
  cd "$PK_DIR"
  openssl genrsa -passout pass:$JWT_PASSPHRASE -out private.pem 4096
  openssl rsa -in private.pem -outform PEM -passin pass:$JWT_PASSPHRASE -pubout -out public.pem
  chown www-data.www-data private.pem
  chown www-data.www-data public.pem
  chmod 0400 private.pem
  chmod 0400 public.pem
  echo 'key pair is generated';
else
  echo 'Keys already generated, skiping...'
fi
