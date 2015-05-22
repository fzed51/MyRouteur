Write-Host $("ssh.exe -t git@github.com")
ssh.exe -t git@github.com

if (-not $?){
    Write-Host $("ssh-add.exe " + $(resolve-path "~/.ssh/github_rsa"))
	ssh-add.exe $(resolve-path "~/.ssh/github_rsa")
}

.\Clear-Project.ps1

git pull