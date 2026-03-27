// ══════════════════════════════════════════════════
//  ISI BURGER — Jenkinsfile
//  Pipeline déclaratif pour Jenkins local
// ══════════════════════════════════════════════════

pipeline {

    agent any

    // ── Variables d'environnement ──────────────────
    environment {
        APP_NAME        = 'isi-burger'
        BRANCH_NAME_APP = 'nom_prenom_burger'
        DOCKER_IMAGE    = "isi-burger:${BUILD_NUMBER}"
        DOCKER_LATEST   = 'isi-burger:latest'
        COMPOSE_FILE    = 'docker-compose.yml'
    }

    // ── Options du pipeline ────────────────────────
    options {
        buildDiscarder(logRotator(numToKeepStr: '10'))
        timeout(time: 30, unit: 'MINUTES')
        disableConcurrentBuilds()
        timestamps()
    }

    // ── Déclencheurs ──────────────────────────────
    triggers {
        // Webhook GitHub (configurer dans GitHub → Settings → Webhooks)
        githubPush()
    }

    stages {

        // ════════════════════════════════════════
        // ÉTAPE 1 : Pull du code depuis GitHub
        // ════════════════════════════════════════
        stage('📥 Pull du code') {
            steps {
                echo '📥 Récupération du code depuis GitHub...'
                checkout([
                    $class: 'GitSCM',
                    branches: [[name: "*/${BRANCH_NAME_APP}"]],
                    userRemoteConfigs: [[
                        url: 'https://github.com/TON_USERNAME/isi-burger.git',
                        credentialsId: 'github-credentials'
                    ]]
                ])
                echo "✅ Code récupéré — Commit : ${env.GIT_COMMIT}"
            }
        }

        // ════════════════════════════════════════
        // ÉTAPE 2 : Installation des dépendances
        // ════════════════════════════════════════
        stage('📦 Installation des dépendances') {
            parallel {

                stage('PHP — Composer') {
                    steps {
                        echo '📦 Installation Composer...'
                        sh '''
                            composer install \
                                --no-dev \
                                --prefer-dist \
                                --no-interaction \
                                --no-progress \
                                --optimize-autoloader
                        '''
                        echo '✅ Dépendances PHP installées'
                    }
                }

                stage('JS — npm') {
                    steps {
                        echo '🎨 Installation npm...'
                        sh 'npm ci --prefer-offline'
                        sh 'npm run build'
                        echo '✅ Assets JS/CSS compilés'
                    }
                }
            }
        }

        // ════════════════════════════════════════
        // ÉTAPE 3 : Configuration
        // ════════════════════════════════════════
        stage('⚙️ Configuration') {
            steps {
                echo '⚙️ Configuration de l\'environnement...'
                sh '''
                    if [ ! -f .env ]; then
                        cp .env.example .env
                        php artisan key:generate
                        echo "✅ Clé générée"
                    else
                        echo "ℹ️  .env déjà présent"
                    fi

                    php artisan config:clear
                    php artisan route:clear
                    php artisan view:clear
                '''
            }
        }

        // ════════════════════════════════════════
        // ÉTAPE 4 : Build de l'image Docker
        // ════════════════════════════════════════
        stage('🐳 Build image Docker') {
            steps {
                echo "🐳 Construction de l'image Docker ${DOCKER_IMAGE}..."
                sh """
                    docker build \
                        --tag ${DOCKER_IMAGE} \
                        --tag ${DOCKER_LATEST} \
                        --build-arg APP_ENV=production \
                        --file Dockerfile \
                        .
                """
                echo "✅ Image ${DOCKER_IMAGE} créée"
            }
        }

        // ════════════════════════════════════════
        // ÉTAPE 5 : Déploiement
        // ════════════════════════════════════════
        stage('🚀 Déploiement') {
            steps {
                echo '🚀 Déploiement avec Docker Compose...'
                sh '''
                    # Arrêter les containers existants
                    docker compose down --remove-orphans 2>/dev/null || true

                    # Démarrer avec la nouvelle image
                    docker compose up -d --force-recreate

                    # Attendre que l'app soit prête
                    sleep 10

                    # Vérifier que l'app répond
                    curl -f http://localhost:8000/up || exit 1
                '''
                echo '✅ Application déployée sur http://localhost:8000'
            }
        }

    }

    // ── Post-actions ──────────────────────────────
    post {
        success {
            echo """
            ╔══════════════════════════════╗
            ║  ✅ PIPELINE RÉUSSI          ║
            ║  ISI BURGER déployé !        ║
            ║  Build : #${BUILD_NUMBER}    ║
            ╚══════════════════════════════╝
            """
        }

        failure {
            echo """
            ╔══════════════════════════════╗
            ║  ❌ PIPELINE ÉCHOUÉ          ║
            ║  Build : #${BUILD_NUMBER}    ║
            ╚══════════════════════════════╝
            """
        }

        always {
            // Nettoyer les images Docker inutilisées
            sh 'docker image prune -f 2>/dev/null || true'
        }
    }
}