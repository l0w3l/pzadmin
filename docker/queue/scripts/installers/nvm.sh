#!/bin/bash

set -e
# Install node and npm via nvm - https://github.com/creationix/nvm

# Run this script like - bash script-name.sh

# Define versions
INSTALL_NODE_VER=20.11.0
INSTALL_NVM_VER=0.39.7

echo "==> Ensuring .bashrc exists and is writable"
touch /root/.bashrc

echo "==> Installing node version manager (NVM). Version $INSTALL_NVM_VER"
# Removed if already installed
rm -rf /root/.nvm
# Unset exported variable
export NVM_DIR=

# Install nvm
curl -o- https://raw.githubusercontent.com/creationix/nvm/v$INSTALL_NVM_VER/install.sh | bash
# Make nvm command available to terminal
source /root/.nvm/nvm.sh

echo "==> Installing node js version $INSTALL_NODE_VER"
nvm install $INSTALL_NODE_VER

echo "==> Make this version system default"
nvm alias default $INSTALL_NODE_VER
nvm use default

#echo -e "==> Update npm to latest version, if this stuck then terminate (CTRL+C) the execution"
#npm install -g npm

echo "==> Checking for versions"
nvm --version
node --version
npm --version

echo "==> Print binary paths"
which npm
which node

nvm cache clear
