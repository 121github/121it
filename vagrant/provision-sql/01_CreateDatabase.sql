# Add any SQL to this directory that should execute as part of provisioning server
#
# Files in the provision-sql directory are executed in alphabetical order, so prefixing
# script names with a number is recommended


# For example, could create development database, setup base schema, or import data
# CREATE database development;

CREATE USER '121it'@'%' IDENTIFIED BY '121it';

CREATE database 121it;
GRANT ALL PRIVILEGES ON 121it.* TO '121it'@'%';

CREATE database 121sys;
GRANT ALL PRIVILEGES ON 121sys.* TO '121it'@'%';

CREATE database uk_postcodes;
GRANT ALL PRIVILEGES ON uk_postcodes.* TO '121it'@'%';

CREATE database calldev;
GRANT ALL PRIVILEGES ON calldev.* TO '121it'@'%';

CREATE database cronjobs;
GRANT ALL PRIVILEGES ON cronjobs.* TO '121it'@'%';

FLUSH PRIVILEGES;