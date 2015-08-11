#!/bin/bash

mkdir phalcon
pushd phalcon > /dev/null

apt-get -y update
apt-get -y upgrade
apt-get -y install libpcre3-dev

echo "Downloading Phalcon"
git clone --depth=1 git://github.com/phalcon/cphalcon.git

echo "Installing Phalcon"
cd cphalcon/build
./install

echo "Configuring Phalcon"
echo 'extension=phalcon.so' > /etc/php.d/phalcon.ini

popd >> /dev/null