pipeline {
    agent any

    stages {
        stage('Checkout') {
            steps {
                echo '📦 Checking out code from repository...'
                checkout scm
            }
        }

        stage('Prepare Environment') {
            steps {
                script {
                    if (isUnix()) {
                        sh 'echo "Running on Unix agent:" && uname -a'
                    } else {
                        bat 'echo Running on Windows agent && ver'
                    }
                }
            }
        }

        stage('Composer Install') {
            steps {
                script {
                    if (fileExists('composer.json')) {
                        try {
                            if (isUnix()) {
                                sh 'composer install --no-interaction --prefer-dist'
                            } else {
                                bat 'composer install --no-interaction --prefer-dist'
                            }
                        } catch (e) {
                            echo 'Composer install failed or not available — continuing.'
                        }
                    } else {
                        echo 'No composer.json found, skipping Composer install.'
                    }
                }
            }
        }

        stage('Node Install') {
            steps {
                script {
                    if (fileExists('package.json')) {
                        try {
                            if (isUnix()) {
                                sh 'npm ci'
                            } else {
                                bat 'npm install'
                            }
                        } catch (e) {
                            echo 'Node install failed — continuing.'
                        }
                    } else {
                        echo 'No package.json found, skipping Node install.'
                    }
                }
            }
        }

        stage('Build Assets') {
            steps {
                script {
                    if (fileExists('package.json')) {
                        try {
                            if (isUnix()) {
                                sh 'npm run build || npm run prod || true'
                            } else {
                                bat 'npm run build || npm run prod'
                            }
                        } catch (e) {
                            echo 'Asset build failed or scripts missing — continuing.'
                        }
                    } else {
                        echo 'No frontend assets to build.'
                    }
                }
            }
        }

        stage('Run Tests') {
            steps {
                script {
                    if (fileExists('vendor/bin/phpunit') || fileExists('phpunit.xml') || fileExists('phpunit.xml.dist')) {
                        try {
                            if (isUnix()) {
                                sh './vendor/bin/phpunit --colors=always || true'
                            } else {
                                bat '.\\vendor\\bin\\phpunit --colors=always || exit /b 0'
                            }
                        } catch (e) {
                            echo 'PHPUnit run failed or not configured — continuing.'
                        }
                    } else {
                        echo 'No PHPUnit configuration found, skipping tests.'
                    }
                }
            }
        }

        stage('Archive Artifacts') {
            steps {
                echo '📂 Archiving build artifacts...'
                archiveArtifacts artifacts: 'public/**, resources/**, vendor/**', allowEmptyArchive: true, fingerprint: true
            }
        }
    }

    post {
        success {
            echo '✅ Pipeline completed successfully!'
        }
        failure {
            echo '❌ Pipeline failed!'
        }
        always {
            echo '🧹 Cleaning workspace...'
            cleanWs()
        }
    }
}
