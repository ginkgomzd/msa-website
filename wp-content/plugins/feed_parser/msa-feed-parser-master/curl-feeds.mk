
feed-upload-url := http://msa.ginkgostreet.com/feeds/index.php

curl-feeds:
	IFS=$$'\n'; \
	for f in `ls -1`; do \
		curl -F "data=@$$(realpath $$f)" -F"filename=$$(basename $$f)" $(feed-upload-url); \
		sleep 3; \
	done
