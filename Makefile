local_ip = 172.17.0.1

.PHONY: build dev test

build:
	docker compose build

dev: build
	docker compose up

test: build
	rm -f tests/reports/*.html
	rm -f tests/reports/*.json
	docker build -t ijm-selenium-tests:latest ./tests
	LOCAL_IP=$(local_ip) docker compose -f docker-compose.yml -f docker-compose.test.yml up -d
	chmod 777 tests/reports
	
	# FIX 1: Increase wait time to 45 seconds for slow CI runners
	sleep 45
	
	# FIX 2: Use --network="host" so Selenium can see 'localhost'
	# FIX 3: Change WEB_URL to localhost
	docker run \
		-i --rm \
		--network="host" \
		-v $(PWD)/tests/reports:/app/reports \
		--env HEADLESS_MODE="true" \
		--env WEB_URL="http://localhost:8080/" \
		ijm-selenium-tests:latest
	docker compose stop