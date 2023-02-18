steps {
    sshagent(credentials: ['ssh-app-bawaslu']) {
      sh '''
          [ -d ~/.ssh ] || mkdir ~/.ssh && chmod 0700 ~/.ssh
          ssh-keyscan -t rsa,dsa -p 65002 185.187.241.21 >> ~/.ssh/known_hosts
          ssh u909598054@185.187.241.21 ...
      '''
    }
}