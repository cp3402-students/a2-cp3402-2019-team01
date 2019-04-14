#!/usr/bin/env bash

set -euo pipefail

deploy_to_env () {
    local env=$1
    echo Deploying to ${env}
    ssh -i ./prvt_key.pem -t ec2-user@13.239.37.152 "rm -rf /ecs/cp3402-$env-storage/wp-content/plugins"
    ssh -i ./prvt_key.pem -t ec2-user@13.239.37.152 "rm -rf /ecs/cp3402-$env-storage/wp-content/theme/custom_theme"
    scp -r -i ./prvt_key.pem ./wp-content/plugins/. ec2-user@13.239.37.152:/ecs/cp3402-${env}-storage/wp-content/plugins/
    scp -r -i ./prvt_key.pem ./wp-content/themes/custom_theme/. ec2-user@13.239.37.152:/ecs/cp3402-${env}-storage/wp-content/themes/custom_theme/
}

eval $@
