CONTAINER_NAME = `basename $(CURDIR)`
PORT = 8000

.PHONY: build start stop ssh

build:
	@docker build -t $(CONTAINER_NAME) .

start:
	@docker run -d --name $(CONTAINER_NAME) -p $(PORT):80 -v $(PWD):/var/app $(CONTAINER_NAME)

stop:
	@docker stop $(CONTAINER_NAME)
	@docker rm $(CONTAINER_NAME)

ssh:
	@docker exec -it $(CONTAINER_NAME) bash

