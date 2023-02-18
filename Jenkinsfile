pipeline {
     agent any
     stages {
          stage('Deploy Production') {
                steps {
                    sshagent(['ssh-app-bawaslu']) {
                         // some block
                         sh '''
                              ssh -o StrictHostKeyChecking=no -p 65002 -l u909598054 185.187.241.21 << ENDSSH
                              cd public_html/public_html/portofolio/bawaslu-api
                              wait
                              git fetch
                              wait
                              git checkout main
                              wait
                              git pull
                              wait 
                              composer install
                         '''
                    }
               }
          }
     }
 }
 