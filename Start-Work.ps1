Write-Host $("ssh.exe -t git@github.com")
ssh.exe -T git@github.com

if (-not $?){
    Write-Host $("ssh-add.exe " + $(resolve-path "~/.ssh/github_rsa"))
	ssh-add.exe $(resolve-path "~/.ssh/github_rsa")
}

if (-not $(test-path ./composer.bat)){
"@echo Off
php ./composer/composer.phar %*
" | set-content ./composer.bat
}
if (-not $(test-path ./composer/composer.phar)){
	md composer
	cd composer
	php -r "readfile('https://getcomposer.org/installer');" | php
	cd ..
}

.\Clear-Project.ps1

git pull