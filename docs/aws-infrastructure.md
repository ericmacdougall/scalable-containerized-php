# Building the AWS ECS infrastructure with Nginx+FPM Docker containers

We will be creating a service that uses blue/green deployment

## Prerequisites

* The latest version of the AWS CLI is installed and configured
* You have a VPC and security group created to use
* The Amazon ECS CodeDeploy IAM role is created

## Step 1: Create an Application Load Balancer

1. Use the create-load-balancer command to create an Application Load Balancer. Specify two subnets that aren't from the same Availability Zone as well as a security group.

    ```
    aws elbv2 create-load-balancer \
        --name bluegreen-alb \
        --subnets subnet-abcd1234 subnet-abcd5678 \
        --security-groups sg-abcd1234 \
        --region ca-central-1
    ```

2. Use the create-target-group command to create a target group. This target group will route traffic to the original task set in your service.

    ```
    aws elbv2 create-target-group \
        --name bluegreentarget1 \
        --protocol HTTP \
        --port 80 \
        --target-type ip \
        --vpc-id vpc-abcd1234 \
        --region ca-central-1
    ```


3. Use the create-listener command to create a load balancer listener with a default rule that forwards requests to the target group.

    ```
    aws elbv2 create-listener \
        --load-balancer-arn arn:aws:elasticloadbalancing:region:aws_account_id:loadbalancer/app/bluegreen-alb/e5ba62739c16e642 \
        --protocol HTTP \
        --port 80 \
        --default-actions Type=forward,TargetGroupArn=arn:aws:elasticloadbalancing:region:aws_account_id:targetgroup/bluegreentarget1/209a844cd01825a4 \
        --region ca-central-1
    ```

## Step 2: Create an Amazon ECS cluster

Use the create-cluster command to create a cluster named wts-cluster to use.

```
aws ecs create-cluster \
     --cluster-name wts-cluster \
     --region ca-central-1
```

## Step 3: Register a task definition

Use the register-task-definition command to register a task definition that is compatible with Fargate. It requires the use of the awsvpc network mode. The following is the example task definition used for this manual.

First, create a file named fargate-task.json with the following contents. Ensure that you use the ARN for your task execution role.

```
{
    "family": "wts-task-def",
        "networkMode": "awsvpc",
        "containerDefinitions": [
        {
            "name": "web",
            "image": "202002617726.dkr.ecr.ca-central-1.amazonaws.com/web-tournament-system-nginx:latest",
            "cpu": 0,
            "links": [],
            "portMappings": [
                {
                    "containerPort": 80,
                    "hostPort": 80,
                    "protocol": "tcp"
                }
            ],
            "essential": true,
            "environment": [],
            "mountPoints": [],
            "volumesFrom": [],
            "logConfiguration": {
                "logDriver": "awslogs",
                "options": {
                    "awslogs-group": "/ecs/web-tournaments-system",
                    "awslogs-region": "ca-central-1",
                    "awslogs-stream-prefix": "ecs"
                }
            }
        },
        {
            "name": "php",
            "image": "202002617726.dkr.ecr.ca-central-1.amazonaws.com/web-tournament-system-php-fpm:latest",
            "cpu": 0,
            "portMappings": [],
            "essential": true,
            "environment": [],
            "mountPoints": [],
            "volumesFrom": [],
            "logConfiguration": {
                "logDriver": "awslogs",
                "options": {
                    "awslogs-group": "/ecs/web-tournaments-system",
                    "awslogs-region": "ca-central-1",
                    "awslogs-stream-prefix": "ecs"
                }
            }
        },
        "requiresCompatibilities": [
            "FARGATE"
        ],
        "cpu": "256",
        "memory": "512",
        "executionRoleArn": "arn:aws:iam::aws_account_id:role/ecsTaskExecutionRole"
}
```

Then register the task definition using the fargate-task.json file that you created.

```
aws ecs register-task-definition \
     --cli-input-json file://fargate-task.json \
     --region ca-central-1
```

## Step 4: Create an Amazon ECS service

Use the create-service command to create a service.

First, create a file named service-webapp-bluegreen.json with the following contents.

```
{
    "cluster": "wts-cluster",
    "serviceName": "service-webapp-bluegreen",
    "taskDefinition": "wts-task-def",
    "loadBalancers": [
        {
            "targetGroupArn": "arn:aws:elasticloadbalancing:region:aws_account_id:targetgroup/bluegreentarget1/209a844cd01825a4",
            "containerName": "web",
            "containerPort": 80
        }
    ],
    "launchType": "FARGATE",
    "schedulingStrategy": "REPLICA",
    "deploymentController": {
        "type": "CODE_DEPLOY"
    },
    "platformVersion": "LATEST",
    "networkConfiguration": {
       "awsvpcConfiguration": {
          "assignPublicIp": "ENABLED",
          "securityGroups": [ "sg-abcd1234" ],
          "subnets": [ "subnet-abcd1234", "subnet-abcd5678" ]
       }
    },
    "desiredCount": 1
}
```

## Step 5: Create the AWS CodeDeploy resources

Use the following steps to create your CodeDeploy application, the Application Load Balancer target group for the CodeDeploy deployment group, and the CodeDeploy deployment group.

### To create CodeDeploy resources

1. Use the create-application command to create an CodeDeploy application. Specify the ECS compute platform.

    ```
    aws deploy create-application \
     --application-name wts-bluegreen-app \
     --compute-platform ECS \
     --region ca-central-1
    ```

2. Use the create-target-group command to create a second Application Load Balancer target group, which will be used when creating your CodeDeploy deployment group.

    ```
    aws elbv2 create-target-group \
     --name bluegreentarget2 \
     --protocol HTTP \
     --port 80 \
     --target-type ip \
     --vpc-id "vpc-0b6dd82c67d8012a1" \
     --region ca-central-1
    ```

3. Use the create-deployment-group command to create an CodeDeploy deployment group.

First, create a file named wts-deployment-group.json with the following contents. This example uses the resource that you created. For the serviceRoleArn, specify the ARN of your Amazon ECS CodeDeploy IAM role.

```
{
  "applicationName": "wts-bluegreen-app",
  "autoRollbackConfiguration": {
      "enabled": true,
      "events": [ "DEPLOYMENT_FAILURE" ]
  },
  "blueGreenDeploymentConfiguration": {
      "deploymentReadyOption": {
        "actionOnTimeout": "CONTINUE_DEPLOYMENT",
        "waitTimeInMinutes": 0
      },
      "terminateBlueInstancesOnDeploymentSuccess": {
        "action": "TERMINATE",
        "terminationWaitTimeInMinutes": 5
      }
  },
  "deploymentGroupName": "wts-bluegreen-dg",
  "deploymentStyle": {
      "deploymentOption": "WITH_TRAFFIC_CONTROL",
      "deploymentType": "BLUE_GREEN"
  },
  "loadBalancerInfo": {
      "targetGroupPairInfoList": [
        {
          "targetGroups": [
            {
                "name": "bluegreentarget1"
            },
            {
                "name": "bluegreentarget2"
            }
          ],
          "prodTrafficRoute": {
              "listenerArns": [
                  "arn:aws:elasticloadbalancing:region:aws_account_id:listener/app/bluegreen-alb/e5ba62739c16e642/665750bec1b03bd4"
              ]
          }
        }
      ]
  },
  "serviceRoleArn": "arn:aws:iam::aws_account_id:role/ecsCodeDeployRole",
  "ecsServices": [
      {
          "serviceName": "service-webapp-bluegreen",
          "clusterName": "wts-cluster"
      }
  ]
}
```
    

  Then create the CodeDeploy deployment group.

  ```
  aws deploy create-deployment-group \
     --cli-input-json file://wts-deployment-group.json \
     --region ca-central-1
  ```

## Step 6: Create and monitor an CodeDeploy deployment

Use the following steps to create and upload an application specification file (AppSpec file) and an CodeDeploy deployment.

NOTE: These steps are what's used into our Gitlab CI, please refer to [scripts/deploy.sh](../scripts/deploy.sh)

### To create and monitor an CodeDeploy deployment

1. Create and upload an AppSpec file using the following steps.

      a. Create a file named appspec.yaml with the contents of the CodeDeploy deployment group. This example uses the resources that you created earlier.

      ```
    version: 0.0
    Resources:
      - TargetService:
          Type: AWS::ECS::Service
          Properties:
            TaskDefinition: "arn:aws:ecs:region:aws_account_id:task-definition/wts-task-def:7"
            LoadBalancerInfo:
              ContainerName: "web"
              ContainerPort: 80
            PlatformVersion: "LATEST"
      ```
      b. Use the s3 mb command to create an Amazon S3 bucket for the AppSpec file.
      ```
      aws s3 mb s3://wts-bluegreen-bucket
      ```  

      c. Use the s3 cp command to upload the AppSpec file to the Amazon S3 bucket.
      ```
      aws s3 cp ./appspec.yaml s3://wts-bluegreen-bucket/appspec.yaml
      ```

2. Create the CodeDeploy deployment using the following steps.

      a. Create a file named create-deployment.json with the contents of the CodeDeploy deployment. This example uses the resources that you created earlier.


      ```
      {
          "applicationName": "wts-bluegreen-app",
          "deploymentGroupName": "wts-bluegreen-dg",
          "revision": {
              "revisionType": "S3",
              "s3Location": {
                  "bucket": "wts-bluegreen-bucket",
                  "key": "appspec.yaml",
                  "bundleType": "YAML"
              }
          }
      }
      ```

      b. Use the create-deployment command to create the deployment.

      ```
      aws deploy create-deployment \
        --cli-input-json file://create-deployment.json \
        --region ca-central-1
      ```
      The output includes the deployment ID, with the following format:

      ```
      {
          "deploymentId": "d-RPCR1U3TW"
      }
      ```

      c. Use the get-deployment-target command to get the details of the deployment, specifying the deploymentId from the previous output.

      ```
      aws deploy get-deployment-target \
        --deployment-id "d-IMJU3A8TW" \
        --target-id wts-cluster:service-webapp-bluegreen \
        --region ca-central-1
      ```

