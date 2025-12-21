local_ip = 172.17.0.1

.PHONY: build dev test

build:
	docker compose build

dev: build
	docker compose up

test: build
	# 1. Prepare permissions
	mkdir -p tests/reports
	rm -f tests/reports/*.html
	rm -f tests/reports/*.json
	chmod -R 777 tests/reports
	
	docker build -t ijm-selenium-tests:latest ./tests
	
	LOCAL_IP=$(local_ip) docker compose -f docker-compose.yml -f docker-compose.test.yml up -d
	
	sleep 15
	
	# 2. THE FIX: DNS SPOOFING
	# Get the internal IP address of the Nginx container
	@WEB_IP=$$(docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' ijm-web-1); \
	echo "Web Container IP: $$WEB_IP"; \
	docker run \
		-i --rm \
		--network="ijm_default" \
		--add-host "microsimulation.pub:$$WEB_IP" \
		-v $(PWD)/tests/reports:/app/reports \
		--env HEADLESS_MODE="true" \
		--env WEB_URL="http://microsimulation.pub" \
		ijm-selenium-tests:latest || (docker compose logs && exit 1)
	
	docker compose stop