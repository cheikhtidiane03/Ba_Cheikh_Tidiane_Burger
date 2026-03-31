// ══════════════════════════════════════════════════
//  ISI BURGER — Jenkinsfile
//  Branch : Cheikh-Tidiane-Ba
// ══════════════════════════════════════════════════

pipeline {

    agent any

    environment {
        REPO_URL = 'https://github.com/cheikhtidiane03/Ba_Cheikh_Tidiane_Burger'
        BRANCH   = 'Cheikh-Tidiane-Ba'
    }

    triggers {
        // Déclenché automatiquement par le webhook GitHub
        githubPush()
    }

    options {
        buildDiscarder(logRotator(numToKeepStr: '5'))
        timeout(time: 20, unit: 'MINUTES')
        disableConcurrentBuilds()
    }

    stages {

        // ── Étape 1 : Pull du code ─────────────────
        stage('📥 Pull du code') {
            steps {
                echo '📥 Récupération du code depuis GitHub...'
                git branch: "${BRANCH}", url: "${REPO_URL}"
                echo "✅ Code récupéré !"
            }
        }

        stage('📦 Préparation') {
            steps {
                echo '📦 Préparation via Docker (Composer + npm déjà gérés dans Dockerfile)'
            }
        }

        // ── Étape 3 : Création image Docker ─────────
        stage('🐳 Création image Docker') {
            steps {
                echo "🐳 Construction de l'image Docker..."
                sh 'docker build --no-cache -t isi-burger:latest .'
                echo '✅ Image Docker créée'
                sh 'docker images isi-burger'
            }
        }

        // ── Étape 4 : Déploiement ───────────────────
        stage('🚀 Déploiement') {
            steps {
                echo '🚀 Déploiement...'
                sh 'docker compose down --remove-orphans 2>/dev/null || true'
                sh 'docker compose up -d'
                sh 'sleep 10'
                sh 'docker compose ps'
                echo '✅ Application déployée sur http://localhost:8080'
            }
        }

    }

    post {
        success {
            echo '✅ Pipeline ISI BURGER — SUCCESS'
            echo '🌐 Application : http://localhost:8080'
        }
        failure {
            sh 'docker compose logs --tail=30 2>/dev/null || true'
            echo '❌ Pipeline échoué — voir les logs'
        }
        always {
            echo "Build #${BUILD_NUMBER} terminé"
        }
    }
}