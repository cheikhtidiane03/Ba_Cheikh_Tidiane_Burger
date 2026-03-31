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

    options {
        buildDiscarder(logRotator(numToKeepStr: '5'))
        timeout(time: 30, unit: 'MINUTES')
        disableConcurrentBuilds()
    }

    stages {

        stage('📥 1. Pull du code') {
            steps {
                echo '📥 Récupération du code...'
                git branch: "${BRANCH}", url: "${REPO_URL}"
                echo "✅ Code récupéré !"
            }
        }

        stage('📦 2. Composer install') {
            steps {
                echo '📦 Installation des dépendances PHP...'
                sh 'docker run --rm -v $(pwd):/app -w /app composer:2.7 install --no-dev --prefer-dist --no-interaction --optimize-autoloader'
                echo '✅ Composer OK'
            }
        }

        stage('🎨 3. npm install + build') {
            steps {
                echo '🎨 Build des assets...'
                sh 'npm ci'
                sh 'npm run build'
                echo '✅ Assets compilés'
            }
        }

        stage('🐳 4. Build image Docker') {
            steps {
                echo '🐳 Construction de l\'image Docker...'
                sh 'docker build --no-cache -t isi-burger:latest .'
                echo '✅ Image Docker créée'
                sh 'docker images isi-burger'
            }
        }

        stage('🚀 5. Déploiement') {
            steps {
                echo '🚀 Déploiement avec Docker Compose...'
                sh 'docker compose down --remove-orphans 2>/dev/null || true'
                sh 'docker compose up -d'
                sh 'sleep 10'
                sh 'docker compose ps'
                echo '✅ Déployé !'
            }
        }

        stage('🧹 6. Nettoyage') {
            steps {
                sh 'docker image prune -f 2>/dev/null || true'
                echo '✅ Nettoyage terminé'
            }
        }
    }

    post {
        success {
            echo '✅ ISI BURGER déployé sur http://localhost:8080'
        }
        failure {
            sh 'docker compose logs --tail=30 2>/dev/null || true'
            echo '❌ Pipeline échoué'
        }
        always {
            echo "Build #${BUILD_NUMBER} terminé"
        }
    }
}