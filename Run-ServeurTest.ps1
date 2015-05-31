Param(
    [switch]$test
)

$webRoot = ([string]$MyInvocation.MyCommand.Path).Replace("\"+[string]$MyInvocation.MyCommand.Name, '')

if(-not $test){
    $webRoot = Join-Path -Path $webRoot -ChildPath '.'
    &"C:\Program Files (x86)\Google\Chrome\Application\chrome.exe" "127.0.0.2:8765/index.php"
    php -S 127.0.0.2:8765 -t "$webRoot" "serveur-routeur.php"
} else {
	&"C:\Program Files (x86)\Google\Chrome\Application\chrome.exe" "127.0.0.2:8765/test.php"    
	php -S 127.0.0.2:8765 -t "$webRoot"
}    