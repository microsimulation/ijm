LOCAL_IP := $(shell python3 -c "import socket; s=socket.socket(socket.AF_INET, socket.SOCK_DGRAM); s.connect(('8.8.8.8', 80)); print(s.getsockname()[0]); s.close()" 2>/dev/null)

.PHONY: build dev test

build:
	docker compose build

dev: build
	docker compose up

test: build
	rm -f tests/reports/*.html
	rm -f tests/reports/*.json
	docker build -t ijm-selenium-tests:latest ./tests
	# Pass the IP to docker-compose and the subsequent test run
	LOCAL_IP=$(LOCAL_IP) docker compose -f docker-compose.yml -f docker-compose.test.yml up -d
	chmod 777 tests/reports
	docker run \
		-i --rm \
		-v $(PWD)/tests/reports:/app/reports \
		--env HEADLESS_MODE="true" \
		--env WEB_URL="http://$(LOCAL_IP):8080/" \
		ijm-selenium-tests:latest
	docker compose stop
