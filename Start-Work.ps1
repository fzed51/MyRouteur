Write-Host $("ssh.exe -t git@github.com")
ssh.exe -T git@github.com

if ($LASTEXITCODE -ne 1){
    Write-Host $("ssh-add.exe " + $(resolve-path "~/.ssh/github_rsa"))
	ssh-add.exe $(resolve-path "~/.ssh/github_rsa")
}
$composer = "composer"
if (@(Get-Command composer -ErrorAction SilentlyContinue).count -eq 0){
	$composer = "./composer"
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
}
.\Clear-Project.ps1

git pull
&$composer selfupdate
&$composer update