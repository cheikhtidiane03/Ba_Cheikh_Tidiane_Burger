pipeline {

    agent any

    environment {
        APP_NAME = 'isi-burger'
        BRANCH   = 'Cheikh-Tidiane-Ba'
        REPO_URL = 'https://github.com/cheikhtidiane03/Ba_Cheikh_Tidiane_Burger'
    }

    triggers {
        githubPush()
    }

    stages {

        stage('📥 Clone') {
            steps {
                echo '📥 Clonage du projet...'
                git branch: "${BRANCH}", url: "${REPO_URL}"
            }
        }

        stage('🐳 Build Docker') {
            steps {
                sh '''
                echo "🐳 Build des containers..."
                docker compose down || true
                docker compose build --no-cache
                '''
            }
        }

        stage('🚀 Deploy') {
            steps {
                sh '''
                echo "🚀 Lancement des containers..."
                docker compose up -d
                '''
            }
        }

        stage('🧹 Clean') {
            steps {
                sh 'docker system prune -f || true'
            }
        }
    }

    post {
        success {
            echo '✅ Application déployée sur http://localhost:8000'
        }
        failure {
            echo '❌ Pipeline échoué'
        }
    }
}