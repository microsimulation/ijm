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
	
	# 1. Start Environment
	LOCAL_IP=$(local_ip) docker compose -f docker-compose.yml -f docker-compose.test.yml up -d
	
	# 2. WAIT for Nginx/PHP to be fully ready
	sleep 15
	
	# 3. Run Tests
	# We use 'ijm_default' because your logs confirmed this is the network name.
	# We use 'http://journal' because that is the service name in docker-compose.
	docker run \
		-i --rm \
		--network="ijm_default" \
		-v $(PWD)/tests/reports:/app/reports \
		--env HEADLESS_MODE="true" \
		--env WEB_URL="http://journal" \
		ijm-selenium-tests:latest || (docker compose logs && exit 1)
	
	docker compose stop