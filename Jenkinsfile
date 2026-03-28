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
                echo "✅ Code récupéré — Commit : ${env.GIT_COMMIT?.take(8)}"
            }
        }

        stage('📦 2. Installation dépendances') {
            parallel {
                stage('Composer') {
                    steps {
                        sh 'composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader'
                    }
                }
                stage('npm + build') {
                    steps {
                        sh 'npm ci'
                        sh 'npm run build'
                    }
                }
            }
        }

        stage('🐳 3. Build image Docker') {
            steps {
                sh '''
                    echo "🐳 Construction de l'image Docker..."
                    docker build --no-cache -t isi-burger:latest .
                    echo "✅ Image créée"
                    docker images isi-burger
                '''
            }
        }

        stage('🚀 4. Déploiement') {
            steps {
                sh '''
                    echo "🚀 Déploiement..."
                    docker compose down --remove-orphans 2>/dev/null || true
                    docker compose up -d
                    echo "⏳ Attente démarrage (15s)..."
                    sleep 15
                    docker compose ps
                '''
            }
        }

        stage('🧹 5. Nettoyage') {
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
            sh 'docker compose logs --tail=50 2>/dev/null || true'
            echo '❌ Pipeline échoué — voir les logs ci-dessus'
        }
        always {
            echo "Pipeline terminé — Build #${BUILD_NUMBER}"
        }
    }
}