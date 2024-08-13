CONTAINER_NAME = "filedispo-www"
PORT = 8000
DEBUG ?= true

.PHONY: build start stop ssh log

build:
	@docker build -t $(CONTAINER_NAME) .

start: build
	@docker run -de DEBUG=$(DEBUG) --name $(CONTAINER_NAME) -p $(PORT):80 -v $(PWD):/var/www $(CONTAINER_NAME)

stop:
	@docker stop $(CONTAINER_NAME)
	@docker rm $(CONTAINER_NAME)

ssh:
	@docker exec -it $(CONTAINER_NAME) bash

log:
	@docker logs -f $(CONTAINER_NAME)
