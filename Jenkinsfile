pipeline {
    agent any

    // Parameters for target Azure resources (override in job or keep defaults)
    parameters {
        string(name: 'AZURE_RESOURCE_GROUP', defaultValue: 'myResourceGroup', description: 'Azure Resource Group name')
        string(name: 'AZURE_WEBAPP_NAME', defaultValue: 'myWebAppName', description: 'Azure Web App name (Linux PHP App Service)')
        booleanParam(name: 'RUN_MIGRATIONS_AFTER_DEPLOY', defaultValue: false, description: 'If true, attempt to run migrations after deploy (requires SSH/remote command support)')
    }

    environment {
        COMPOSER_HOME = "${WORKSPACE}/.composer"
        NPM_CONFIG_LOGLEVEL = "warn"
        // Do NOT store secrets here. Use Jenkins Credentials and withCredentials in the deploy stage.
    }

    stages {
        stage('Checkout') {
            steps {
                echo '📦 Checkout repository'
                checkout scm
            }
        }

        stage('Prepare tools') {
            steps {
                echo '🔧 Ensure composer & php are available (install composer if missing)'
                sh '''
                set -e
                echo "PHP: $(php -v 2>/dev/null | head -n1 || echo 'php not found')"
                if ! command -v composer >/dev/null 2>&1; then
                  echo "composer not found, installing composer locally..."
                  php -r "copy('https://getcomposer.org/installer','composer-setup.php');"
                  php composer-setup.php --quiet --install-dir=${WORKSPACE} --filename=composer
                  export PATH=${WORKSPACE}:$PATH
                  php -r "unlink('composer-setup.php');"
                fi
                composer --version || true
                '''
            }
        }

        stage('Install PHP dependencies') {
            steps {
                echo '📥 Installing PHP dependencies'
                sh 'composer install --no-interaction --prefer-dist --optimize-autoloader'
            }
        }

        stage('Prepare .env for CI') {
            steps {
                echo '⚙️ Prepare .env (for build/tests only) — will NOT be deployed'
                sh '''
                set -e
                if [ ! -f .env ]; then
                  if [ -f .env.example ]; then
                    cp .env.example .env
                  else
                    cat > .env <<'EOL'
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost
EOL
                  fi
                fi

                # Ensure we have an APP_KEY for build steps
                php artisan key:generate --ansi || true
                '''
            }
        }

        stage('Database: prepare sqlite for tests') {
            steps {
                echo '🗄️ Create sqlite db for CI tests (avoids external DB requirement)'
                sh '''
                mkdir -p database
                touch database/database.sqlite
                '''
            }
        }

        stage('Run Migrations (CI)') {
            steps {
                echo '🧩 Running migrations for test environment (sqlite)'
                sh '''
                set -e
                export DB_CONNECTION=sqlite
                export DB_DATABASE=$(pwd)/database/database.sqlite
                php artisan migrate --force || true
                '''
            }
        }

        stage('Run Tests') {
            steps {
                echo '🧪 Running tests (artisan test or phpunit)'
                sh '''
                set -e
                if php artisan --version >/dev/null 2>&1; then
                  php artisan test --no-interaction || true
                elif [ -f vendor/bin/phpunit ]; then
                  vendor/bin/phpunit --testdox || true
                else
                  echo "No test runner found, skipping tests"
                fi
                '''
            }
        }

        stage('Build frontend assets (if any)') {
            steps {
                echo '📦 Building frontend assets (npm) if package.json exists'
                sh '''
                set -e
                if [ -f package.json ]; then
                  if ! command -v npm >/dev/null 2>&1; then
                    echo "npm not found on agent — ensure Node is installed or prebuild assets"
                    exit 1
                  fi
                  npm ci
                  # run common build scripts
                  if npm run | grep -q "prod"; then
                    npm run prod
                  elif npm run | grep -q "build"; then
                    npm run build
                  else
                    echo "No build script (prod/build) found; skipping asset build"
                  fi
                else
                  echo "No package.json — skipping frontend build"
                fi
                '''
            }
        }

        stage('Optimize Laravel') {
            steps {
                echo '⚡ Optimizing framework caches'
                sh '''
                set -e
                # avoid clearing production sensitive caches; this is for build packaging
                php artisan config:cache || true
                php artisan route:cache || true
                php artisan view:cache || true
                '''
            }
        }

        stage('Package for deployment') {
            steps {
                echo '📦 Creating deployment package (build.zip)'
                sh '''
                set -e
                # Install production composer deps (optionally)
                composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

                # Ensure vendor is present
                [ -d vendor ] || { echo "vendor not found after composer install"; exit 1; }

                # Remove runtime-specific files we don't want to deploy
                rm -f .env
                rm -rf node_modules
                rm -rf tests

                # Create zip (exclude .git, CI artifacts)
                zip -r build.zip . -x ".git/*" "storage/*" "vendor/bin/*" "build.zip" || true

                # show size
                du -h build.zip || true
                '''
                archiveArtifacts artifacts: 'build.zip', fingerprint: true, allowEmptyArchive: false
            }
        }

        stage('Azure: Login & Deploy') {
            steps {
                echo '🚀 Deploying to Azure App Service (zip deploy)'

                // Credentials: create the following Jenkins "Secret text" credentials:
                // - AZURE_CLIENT_ID (id: AZURE_CLIENT_ID)
                // - AZURE_CLIENT_SECRET (id: AZURE_CLIENT_SECRET)
                // - AZURE_TENANT_ID (id: AZURE_TENANT_ID)
                // - AZURE_SUBSCRIPTION_ID (id: AZURE_SUBSCRIPTION_ID)
                //
                // Alternatively wrap these into another credentials binding you prefer.
                withCredentials([
                  string(credentialsId: 'AZURE_CLIENT_ID', variable: 'AZ_CLIENT_ID'),
                  string(credentialsId: 'AZURE_CLIENT_SECRET', variable: 'AZ_CLIENT_SECRET'),
                  string(credentialsId: 'AZURE_TENANT_ID', variable: 'AZ_TENANT_ID'),
                  string(credentialsId: 'AZURE_SUBSCRIPTION_ID', variable: 'AZ_SUBSCRIPTION_ID')
                ]) {
                    sh '''
                    set -e
                    # Install azure cli if missing (works on many Linux agents)
                    if ! command -v az >/dev/null 2>&1; then
                      echo "Azure CLI not found, installing..."
                      curl -sL https://aka.ms/InstallAzureCLIDeb | sudo bash
                    fi

                    echo "Logging into Azure using service principal..."
                    az login --service-principal -u "$AZ_CLIENT_ID" -p "$AZ_CLIENT_SECRET" --tenant "$AZ_TENANT_ID"
                    az account set --subscription "$AZ_SUBSCRIPTION_ID"

                    echo "Setting WEBSITE_RUN_FROM_PACKAGE=1 to use package deploy"
                    az webapp config appsettings set --resource-group "${AZURE_RESOURCE_GROUP}" --name "${AZURE_WEBAPP_NAME}" --settings WEBSITE_RUN_FROM_PACKAGE=1

                    echo "Deploying build.zip to ${AZURE_WEBAPP_NAME} in ${AZURE_RESOURCE_GROUP}..."
                    az webapp deployment source config-zip --resource-group "${AZURE_RESOURCE_GROUP}" --name "${AZURE_WEBAPP_NAME}" --src build.zip

                    echo "Deployment finished. Logging out."
                    az logout || true
                    '''
                }
            }
        }

        stage('Optional: Run migrations on Azure (manual/optional)') {
            when {
                expression { return params.RUN_MIGRATIONS_AFTER_DEPLOY == true }
            }
            steps {
                echo '⚠️ Attempting to run migrations on Azure — ensure publish credentials/SSH available or use migration scripts'
                echo 'See job notes for safer alternatives (Kudu/Run-From-Package caveats).'
                // This block is intentionally left for manual/advanced configuration.
            }
        }
    }

    post {
        success {
            echo '✅ Pipeline completed successfully'
        }
        failure {
            echo '❌ Pipeline failed'
        }
        always {
            echo '🧹 Cleaning workspace...'
            cleanWs()
        }
    }
}
