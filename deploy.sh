#!/bin/bash

#DMag Staging
ssh ore@159.203.172.115 << REMOTE
	cd /var/www/html
	git pull
    git submodule update --recursive

	cd /var/www/html/wp-content/plugins/d-magazine
	git pull origin master
	npm install
	bower install
	gulp
REMOTE