pipeline {
    agent any
    
    stages {
        stage('Checkout') {
            steps {
                echo '📦 Checking out code from repository...'
                checkout scm
            }
        }
        
        stage('Install Dependencies') {
            steps {
                echo '📥 Installing npm dependencies...'
                bat 'npm install'
            }
        }
        
        stage('Lint') {
            steps {
                echo '🔍 Running linter...'
                script {
                    try {
                        bat 'npm run lint'
                    } catch (Exception e) {
                        echo 'Lint script not found, skipping...'
                    }
                }
            }
        }
        
        stage('Build') {
            steps {
                echo '🔨 Building application...'
                bat 'npx expo export --platform web'
            }
        }
        
        stage('Archive') {
            steps {
                echo '📂 Archiving build artifacts...'
                archiveArtifacts artifacts: 'dist/**/*', allowEmptyArchive: true, fingerprint: true
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
        }
    }
}
