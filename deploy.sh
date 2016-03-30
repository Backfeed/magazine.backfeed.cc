#!/bin/bash

#DMag Staging
ssh ore@159.203.172.115 << REMOTE
	cd /var/www/html
	git pull

	cd /var/www/html/wp-content/plugins/d-magazine
	npm install
	bower install
	gulp
REMOTE