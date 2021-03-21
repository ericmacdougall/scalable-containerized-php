#!/usr/bin/env bash

set -eu
echo "Home=$HOME"

__dir="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
__file="${__dir}/$(basename "${BASH_SOURCE[0]}")"
__base="$(basename ${__file} .sh)"

generate_revision_number() {
  echo $((1 + RANDOM % 100000))
}

build_id="${CI_PIPELINE_IID:-}"
if [[ -z "${build_id}" ]]; then
  build_id=$(generate_revision_number)
fi

codedeploy_application="${AWS_CODEDEPLOY_APPLICATION:-}"

deployment_environment="${CI_COMMIT_REF_NAME}"
if [[ $deployment_environment == "dev" ]]; then
  codedeploy_deployment_group="${AWS_CODEDEPLOY_DEPLOYMENT_GROUP_DEV:-}"
elif [[ $deployment_environment == "staging" ]]; then
  codedeploy_deployment_group="${AWS_CODEDEPLOY_DEPLOYMENT_GROUP_STAG:-}"
elif [[ $deployment_environment == v*.*.* ]]; then
  codedeploy_deployment_group="${AWS_CODEDEPLOY_DEPLOYMENT_GROUP_PROD:-}"
else
  codedeploy_deployment_group="${AWS_CODEDEPLOY_DEPLOYMENT_GROUP_DEV:-}"
fi

revision_bundle_type="yaml"
revision_s3_bucket_name="${AWS_CODEDEPLOY_S3_BUCKET:-}"
revision_s3_key_prefix="${codedeploy_application}/${codedeploy_deployment_group}"
revision_s3_key="${revision_s3_key_prefix}/backend-code-apis-${build_id}.${revision_bundle_type}"
revision_s3_location="s3://${revision_s3_bucket_name}/${revision_s3_key}"
deployment_s3_location=""
project_dir="$(readlink -f ${__dir}/../../)"

generate_revision_description() {
  echo "This is a revision for the web application"
}

generate_deployment_description() {
  echo "This is a revision for the web application"
}

create_codedeploy_revision() {
  echo "Uploading revision to S3 ..."
  aws s3 cp ./appspec.yml ${revision_s3_location}
  deployment_s3_location="bucket=${AWS_CODEDEPLOY_S3_BUCKET},key=${revision_s3_key},bundleType=${revision_bundle_type}"

  echo "Codedeploy revision for S3 location ${revision_s3_location} is created"
}

create_codedeploy_deployment() {
  if [[ "${deployment_s3_location}" == "" ]]; then
    echo "Please call 'create_codedeploy_revision' function first"
    return 1
  fi
  deployment_id=$(aws deploy create-deployment \
    --application-name "${codedeploy_application}" \
    --deployment-group-name "${codedeploy_deployment_group}" \
    --description "$(generate_deployment_description)" \
    --s3-location "${deployment_s3_location}" |
    grep -Po '"deploymentId": \K[^.]+"')
  echo "Codedeploy deployment ${deployment_id} is created"
}

main() {
  echo "
Starting the deployment to codedeploy
-----------------------------------------------------------------
Codedeploy Application: ${codedeploy_application}
Codedeploy Deployment Group: ${codedeploy_deployment_group}
Revision S3 Location: ${revision_s3_location}
Source Location: ${project_dir}
------------------------------------------------------------------
  "
  create_codedeploy_revision
  create_codedeploy_deployment
}

main