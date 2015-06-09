# -----------------------------------------------------
#   Clear-Project
#   =============
# @autor fzed51
# @copyright @2015
# -----------------------------------------------------

# suppression de tous les dossier de backup de notepad++
ls -Filter nppBackup -Recurse -Directory | Remove-Item -Recurse -Force
php ./tabToSpace.phps
./Test-Php.ps1