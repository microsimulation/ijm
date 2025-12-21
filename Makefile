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
	
	# 2. WAIT for Nginx to be ready
	sleep 10
	
	# 3. Auto-detect the network name (Runtime calculation)
	# We use $$() to tell Make to run this inside the shell, not at compile time.
	# We look up the container ID for 'journal' service first to be safe.
	@JOURNAL_ID=$$(docker compose ps -q journal); \
	NETWORK_NAME=$$(docker inspect $$JOURNAL_ID -f '{{range .NetworkSettings.Networks}}{{.Name}}{{end}}'); \
	echo "Detected Network Name: $$NETWORK_NAME"; \
	docker run \
		-i --rm \
		--network="$$NETWORK_NAME" \
		-v $(PWD)/tests/reports:/app/reports \
		--env HEADLESS_MODE="true" \
		--env WEB_URL="http://journal" \
		ijm-selenium-tests:latest || (docker compose logs && exit 1)
	
	docker compose stop