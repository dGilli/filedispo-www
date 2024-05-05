CONTAINER_NAME = "filedispo-www"
PORT = 8000

.PHONY: build start stop ssh log

build:
	@docker build -t $(CONTAINER_NAME) .

start:
	@docker run -de DEBUG=true --name $(CONTAINER_NAME) -p $(PORT):80 -v $(PWD):/var/www $(CONTAINER_NAME)

stop:
	@docker stop $(CONTAINER_NAME)
	@docker rm $(CONTAINER_NAME)

ssh:
	@docker exec -it $(CONTAINER_NAME) bash

log:
	@docker logs -f $(CONTAINER_NAME)
