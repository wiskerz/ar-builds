AR_BUILD?=domain.com:/path/to/site
default:
	php build.php > site/index.html
cache:
	php cache.php
deploy:
	rsync -ua --progress site/* ${AR_BUILD}
