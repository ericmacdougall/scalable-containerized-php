# Create a target tracking scaling policy (AWS CLI)

You can create scaling policies that tell Application Auto Scaling what to do when the load on your application changes.

You can create a scaling policy by using the AWS CLI for the following configuration tasks.

### Tasks

* Register a scalable target
* Create a target tracking scaling policy
* Create an alarm that triggers the scaling policy

## Register a scalable target

If you haven't already done so, register the scalable target. Use the register-scalable-target command to register a specific resource in the target service as a scalable target. The following example registers an Amazon ECS service with Application Auto Scaling. Application Auto Scaling can scale the number of tasks at a minimum of 2 tasks and a maximum of 10.

```
aws application-autoscaling register-scalable-target --service-namespace ecs \
  --scalable-dimension ecs:service:DesiredCount \
  --resource-id service/default/sample-app-service \
  --min-capacity 2 --max-capacity 20
```

## Create a target tracking scaling policy

Save this configuration in a file named `config.json`
```
{
  "TargetValue": 45.0,
  "PredefinedMetricSpecification": 
  {
    "PredefinedMetricType": "ECSServiceAverageCPUUtilization"
  },
  "ScaleOutCooldown": 60,
  "ScaleInCooldown": 300
}
```
Use the following put-scaling-policy command, along with the config.json file that you created, to create a scaling policy named cpu45-target-tracking-scaling-policy.

```
aws application-autoscaling put-scaling-policy --service-namespace ecs \
  --scalable-dimension ecs:service:DesiredCount \
  --resource-id service/default/sample-app-service \
  --policy-name cpu45-target-tracking-scaling-policy --policy-type TargetTrackingScaling \
  --target-tracking-configuration file://config.json
```

## Describe target tracking scaling policies

You can describe all scaling policies for the specified service namespace using the following describe-scaling-policies command.

```
aws application-autoscaling describe-scaling-policies --service-namespace ecs
```
