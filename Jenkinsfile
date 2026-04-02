// ══════════════════════════════════════════════════
//  ISI BURGER — Jenkinsfile FINAL CORRIGÉ
//  Branch : Cheikh-Tidiane-Ba
// ══════════════════════════════════════════════════

pipeline {

    agent any

    environment {
        REPO_URL   = 'https://github.com/cheikhtidiane03/Ba_Cheikh_Tidiane_Burger'
        BRANCH     = 'Cheikh-Tidiane-Ba'
        IMAGE_NAME = 'isi-burger'
    }

    triggers {
        githubPush()
    }

    options {
        buildDiscarder(logRotator(numToKeepStr: '5'))
        timeout(time: 20, unit: 'MINUTES')
        disableConcurrentBuilds()
    }

    stages {

        stage('📥 Pull du code') {
            steps {
                echo '📥 Récupération du code depuis GitHub...'
                git branch: "${BRANCH}", url: "${REPO_URL}"
                echo "✅ Code récupéré — Commit : ${env.GIT_COMMIT?.take(8)}"
            }
        }

        stage('🐳 Build image Docker') {
            steps {
                echo "🐳 Construction de l'image Docker..."
                // SANS --no-cache pour utiliser le cache et aller vite
                sh "docker build -t ${IMAGE_NAME}:latest -t ${IMAGE_NAME}:${BUILD_NUMBER} ."
                echo "✅ Image ${IMAGE_NAME}:latest créée"
                sh "docker images ${IMAGE_NAME}"
            }
        }

        stage('🚀 Déploiement') {
            steps {
                echo '🚀 Déploiement...'
                // Utiliser docker-compose (avec tiret) au lieu de docker compose
                sh 'docker-compose down --remove-orphans 2>/dev/null || true'
                sh 'docker-compose up -d'
                sh 'sleep 10'
                sh 'docker-compose ps'
                echo '✅ Application déployée sur http://localhost:8082'
            }
        }

    }

    post {
        success {
            echo '✅ Pipeline ISI BURGER — SUCCESS !'
            echo '🌐 http://localhost:8080'
        }
        failure {
            sh 'docker-compose logs --tail=20 2>/dev/null || true'
            echo '❌ Pipeline échoué'
        }
        always {
            // Nettoyer les anciennes images pour libérer l'espace
            sh 'docker image prune -f 2>/dev/null || true'
            echo "Build #${BUILD_NUMBER} terminé"
        }
    }
}