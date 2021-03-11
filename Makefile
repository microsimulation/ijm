.PHONY: build dev

build:
	docker-compose build

dev: build
	docker-compose up
