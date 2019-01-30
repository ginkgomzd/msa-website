##In case of guzzle issue with cert on windows for feedparser
The php.ini entry curl.cainfo has to point to a cacert.pem

So: curl.cainfo = "PATH_TO/cacert.pem"

Get the pem cert here ( there should also be one in the cURL dir ) : https://curl.haxx.se/ca/cacert.pem



****
### crontab 1:
 - executing mail fetching on each 1h:
 `0 * * * * php /var/www/solr/wp-content/plugins/feed_parser/msa-feed-parser-master/mail_checker.php`
### crontab 2:
