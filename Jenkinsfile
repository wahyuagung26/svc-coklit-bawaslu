pipeline {
    agent any
    environment {
        SSH_USERNAME = '15919-2460'
        SSH_HOST     = 'gate.jagoan.cloud'
        SSH_PORT     = 'p3022'
        SSH_PROJECT_DIRECTORY     = 'public_html/public_html/portofolio/bawaslu-api'
    }
     stages {
        stage('Deploy Development') {
                when { 
                    branch 'development';
                }
                steps {
                    sshagent(['ssh-app-bawaslu']) {
                         echo 'deploy to development'
                    }
               }
          }
          stage('Deploy Production') {
                when { 
                    branch 'main';
                }
                steps {
                    sshagent(['ssh-app-bawaslu']) {
                         sh '''
                              ssh -o StrictHostKeyChecking=no -p ${SSH_PORT} -l ${SSH_USERNAME} ${SSH_HOST} << ENDSSH
                              cd ${SSH_PROJECT_DIRECTORY}
                              git fetch
                              git checkout main
                              git pull
                         '''
                    }
               }
          }
          stage('Send Notif') {
                steps {
                    echo 'send notif to discord'
               }
          }
     }
 }
 