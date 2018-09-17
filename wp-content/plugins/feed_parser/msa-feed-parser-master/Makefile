
this-dir := $(dir $(lastword $(MAKEFILE_LIST)))
rsync := rsync -a --delete --copy-links

ENV ?= STAGE

$(this-dir)dist:
	[ -d dist ] || mkdir dist

feed-parser-dist: $(this-dir)dist gsl-dao feed-parser-clean
	cd $(this-dir) && \
	$(rsync) \
	--exclude .git --exclude .gitignore \
	--exclude phpunit --exclude tests \
	--exclude '*.mk' --exclude Makefile \
	--exclude dist \
	--exclude sql \
	. dist/

gsl-dao:
	cd $(this-dir).. && \
	( [ -d dao ] || git clone git@bitbucket.org:mainstreetadvocates/dao.git dao) && \
	cd dao && composer install --no-dev

feed-parser-clean:
	cd $(this-dir) && rm -rf dist vendor && \
	composer install --no-dev

include $(this-dir)conf/$(ENV).env

configure:
	$(call m4-defines) | \
	cat - $(this-dir)conf/pdo_connection.m4 | \
	m4 -P - > $(this-dir)conf/pdo_connection.conf
