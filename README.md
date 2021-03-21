# scalable-containerized-php
Self-deploying and highly scalable PHP project meant to run in Fargate with NGINX and performance tuning capable of tens of thousands of concurrent connections.

* Uses gitlab-ci to deploy to fargate automatically
* Routes 404s to router.php so you can make customer /alias paths or handle the 404s based on the initial request
* Has a permission system
* Update
- includes/connection.inc.php
- includes/system.lib.php
- .gitlab-ci.yml
- appspec.yml

* Based on consuming the user api provided in https://github.com/ericmacdougall/jsonapi-lambda-express-serverless-cognito
* NOTE: The HTML/logic was quickly stripped from a real project to make this a demo, I have not executed this demo version so there could be errors and the HTML/css will render poorly
