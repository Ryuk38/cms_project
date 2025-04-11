pipeline {
    agent any
    stages {
        stage("Checkout Code") {
            steps {
                git branch: 'main', url: 'https://github.com/Ryuk38/cms_project.git'
        }
        stage("Build and Deploy PHP") {
            steps {
                sh 'docker-compose down || true'
                sh 'docker-compose up -d --build'
            }
        }
    }
    post {
        always {
            sh 'docker-compose logs'
        }
    }
}