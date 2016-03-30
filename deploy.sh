#!/bin/bash

cd /c/WTServer/WWW/magazine/wp-content/plugins
git add -A
git commit -m "MANUAL"
git push

cd /c/WTServer/WWW/magazine
git add -A
git commit -m "MANUAL"
git push

#DMag Staging
ssh dms << REMOTE
	cd /var/www/html
	git pull
	#MANUAL: enter GitHub credentials
	#MANUAL: enter GitHub credentials for submodule

	cd /var/www/html/wp-content/plugins/d-magazine
	npm install
	bower install --allow-root
	gulp
REMOTE