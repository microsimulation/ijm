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
	# Change: We force the LOCAL_IP to 172.17.0.1 for the host, 
	# but we will ensure the containers can see each other.
	LOCAL_IP=172.17.0.1 docker compose -f docker-compose.yml -f docker-compose.test.yml up -d
	chmod 777 tests/reports
	# Increase sleep to 30 seconds - Symfony/Java apps take time to warm up
	sleep 30
	docker run \
		-i --rm \
		--network="host" \
		-v $(PWD)/tests/reports:/app/reports \
		--env HEADLESS_MODE="true" \
		--env WEB_URL="http://localhost:8080/" \
		ijm-selenium-tests:latest
	docker compose stop
 