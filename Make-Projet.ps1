# +--------------------------------------------------------------------------+
# | Make-Projet                                                              |
# +--------------------------------------------------------------------------+
# | @2015                                                                    |
# | dev to master                                                            |
# | Nétoie le projet pour faire une une version de production                |
# +--------------------------------------------------------------------------+

param(
	[switch]$major,
	[switch]$minor,
	[switch]$release,
	[switch]$alpha,
	[switch]$beta,
	[switch]$whatif,
	[switch]$test,
)

$liste = @(
	".gitignore",
	".\LICENSE",
	".\README.md",
	".\composer.bat",
	".\Make-Projet.ps1",
	".\VERSION",
	".\composer\composer.phar",
	".\core\routeur\Route.php",
	".\core\routeur\Routeur.php",
	".\core\routeur\Route.php",
	".\core\routeur\Route.php"
)

$num_version = Get-Content ./VERSION | Out-String | ConvertFrom-Json

$prefix = $False

if($release){
	$num_version[2]++
}
if($minor){
	$num_version[2]=0
	$num_version[1]++
}
if($major){
	$num_version[2]=0
	$num_version[1]=0
	$num_version[0]++
}
$num_version | ConvertTo-Json | Set-Content VERSION
$version = [string]$num_version[0] + "." + [string]$num_version[1]
if($num_version[2] -gt 0){
	$version += "." + [string]$num_version[2]
}
if($pre){
	if(!$prefix){
		$version += "-"
		$prefix = $True
	}
	$version += "pre"
}
if($alpha){
	if(!$prefix){
		$version += "-"
		$prefix = $True
	}
	$version += "alpha"
} else {
	if($beta){
		if(!$prefix){
			$version += "-"
			$prefix = $True
		}
		$version += "beta"
	}
}

Write-Host "Creation de la version " -no
Write-Host $version -f Yellow

$composerjson = @"
{
    "name": "fzed51/routeur",
	"type": "composent-fzed51-core",
    "description": "Routeur PHP simple. Il est autonome un maximum pour eviter les dépendances.",
    "license": "MIT",
    "authors": [
        {
            "name": "fabien.sanchez",
            "email": "fzed51@users.noreply.github.com"
        }
    ],
    "autoload": {
        "psr-4": {
            "Core\\": "core/"
        }
    },
    "require": {
        "php": ">=5.6.0"
    }
}
"@

ls -r -file | ? {
	$liste -notcontains $($_.FullName | Resolve-Path -Relative)
} | Remove-Item -force

ls -r -directory | ? {
	@(ls -r -file).count -eq 0
} | ForEach-Object -Begin {
	Write-Host "Le(s) fichier(s) suivant est(sont) supprime(s) :"
}  -Process {
	Write-Host $_.FullName -f Cyan
	Remove-Item $_.FullName -force
}

if ((!$whatif) -or $test){
	$composerjson | Set-Content ./composer.json
	composer update
	git add -A
	git commit -m ":package: make for v$version"
} else {
	Write-Host "`$composerjson | Set-Content ./composer.json"
	Write-Host "composer update"
	"git add -A"
	"git commit -m `":package: make for v$version`""
}
	
#>