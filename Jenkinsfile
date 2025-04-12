pipeline {
    agent any
    stages {
        stage('Clone CMS Repo') {
            steps {
                retry(3) {
                    git url: 'https://github.com/Ryuk38/cms_project.git', branch: 'main' 
                }
            }
        }
        stage('Build Docker Images') {
            steps {
                sh 'docker-compose build'
            }
        }
        stage('Run CMS in Containers') {
    steps {
        sh '''
            docker-compose down --volumes --remove-orphans
            docker-compose up -d
        '''
    }
}

        stage('Check Running Containers') {
            steps {
                sh 'docker ps'
            }
        }
    }
}
