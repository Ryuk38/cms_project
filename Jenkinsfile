pipeline {
    agent any

    stages {
        stage('Clone CMS Repo') {
            steps {
                git 'https://github.com/Ryuk38/cms_project.git'
            }
        }

        stage('Build Docker Images') {
            steps {
                sh 'docker-compose build'
            }
        }

        stage('Run CMS in Containers') {
            steps {
                sh 'docker-compose up -d'
            }
        }

        stage('Check Running Containers') {
            steps {
                sh 'docker ps'
            }
        }
    }
}
