
local_ip = $(shell hostname -I | awk '{print $$1}')

.PHONY: build dev test

build:
	docker-compose build

dev: build
	docker-compose up

test: build
	docker build -t ijm-selenium-tests:latest ./tests
	LOCAL_IP=$(local_ip) docker-compose -f docker-compose.yml -f docker-compose.test.yml up -d
	chmod 777 tests/reports
	docker run \
		-i --rm \
		-v $(PWD)/tests/reports:/app/reports \
		--env HEADLESS_MODE="true" \
		--env WEB_URL="http://$(local_ip):8080/" \
		ijm-selenium-tests:latest
	docker-compose stop