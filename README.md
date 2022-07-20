## Clints README
This is a simple Curl/XML endpoint API activity

## Endpoints

http://localhost:8000/users/5

GET or POST (Via POSTMAN) this URL/Endpoint
Limit is optional so http://localhost:8000/users works
and http://localhost:8000/users/foot works and defaults to 10 when not a number
Easily could add a cap for the limit so be safe with your powers

## Possible Additions

- php -S for docker (php built in server)
- pthread for concurrent connections
- add unit tests