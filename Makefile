local_ip = 172.17.0.1

.PHONY: build dev test

build:
	docker compose build

dev: build
	docker compose up

test: build
	# 1. Prepare the reports directory with full permissions
	mkdir -p tests/reports
	rm -f tests/reports/*.html
	rm -f tests/reports/*.json
	chmod -R 777 tests/reports
	
	docker build -t ijm-selenium-tests:latest ./tests
	
	LOCAL_IP=$(local_ip) docker compose -f docker-compose.yml -f docker-compose.test.yml up -d
	
	sleep 15
	
	# 2. Run Tests (Permissions fixed)
	docker run \
		-i --rm \
		--network="ijm_default" \
		-v $(PWD)/tests/reports:/app/reports \
		--env HEADLESS_MODE="true" \
		--env WEB_URL="http://journal" \
		ijm-selenium-tests:latest || (docker compose logs && exit 1)
	
	docker compose stop