#!/bin/bash

# This function is called at the very bottom of the file
main() {
	symfony_actions
	restart_apache
}

restart_apache() {
	echo "Restarting apache"
	service apache2 restart
}

symfony_actions() {
    echo "Access to the app path"
    cd /vagrant/

	echo "Clear symfony cache"
	php app/console cache:clear --env=dev --no-warmup

	echo "Dump assetic assets"
	php app/console assetic:dump --env=dev

	echo "Clearing apc cache"
	#Already done restarting Apache

	echo "Running asset:install"
    php app/console assets:install --env=dev
}

main
exit 0
