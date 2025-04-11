pipeline {
    agent any
    stages {
        stage("Checkout Code") {
            steps {
                git branch: 'main', url: 'https://github.com/Ryuk38/cms_project.git'
            }
        }
        stage("Build and Deploy with Docker Compose") {
            steps {
                sh 'docker-compose -f docker-compose.yml down || true'
                sh 'docker-compose -f docker-compose.yml up -d --build'
            }
        }
    }
    post {
        always {
            sh 'docker-compose -f docker-compose.yml logs'
        }
    }
}