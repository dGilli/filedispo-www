CONTAINER_NAME = `basename $(CURDIR)`
PORT = 8000

start:
	@docker run -d --name $(CONTAINER_NAME) -p $(PORT):80 -v $(PWD)/src:/var/www/html php:8.3-apache
stop:
	@docker stop $(CONTAINER_NAME)
	@docker rm $(CONTAINER_NAME)

