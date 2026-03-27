// ══════════════════════════════════════════════════
//  ISI BURGER — Jenkinsfile simplifié
// ══════════════════════════════════════════════════

pipeline {

    agent any

    environment {
        APP_NAME   = 'isi-burger'
        BRANCH     = 'Cheikh-Tidiane-Ba'
        REPO_URL   = 'https://github.com/cheikhtidiane03/Ba_Cheikh_Tidiane_Burger'
    }

    triggers {
        githubPush()
    }

    stages {

        stage('📥 1. Pull du code') {
            steps {
                echo '📥 Récupération du code depuis GitHub...'
                git branch: "${BRANCH}",
                    url: "${REPO_URL}"
                echo "✅ Code récupéré !"
            }
        }

        stage('📦 2. Installation dépendances PHP') {
            steps {
                sh '''
                docker run --rm \
                -v $PWD:/app \
                -w /app \
                composer install --no-dev --prefer-dist --no-interaction
                '''
            }
        }

        stage('🎨 3. Build assets JS/CSS') {
            steps {
                echo '🎨 Build Tailwind + Vite...'
                sh 'npm ci'
                sh 'npm run build'
                echo '✅ Assets compilés'
            }
        }

        stage('🐳 4. Build image Docker') {
            steps {
                echo '🐳 Construction image Docker...'
                sh 'docker build -t isi-burger:latest .'
                echo '✅ Image Docker créée'
            }
        }

    }

    post {
        success {
            echo '✅ Pipeline ISI BURGER terminé avec succès !'
        }
        failure {
            echo '❌ Pipeline échoué — vérifier les logs'
        }
    }
}