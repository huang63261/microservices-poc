# navigate to the apps directory
Set-Location -Path "./apps"

# loop through each directory in the apps directory
Get-ChildItem -Directory | ForEach-Object {
    Write-Host "Configuring $($_.Name)"
    Set-Location $_.FullName

    # installation steps
    composer install
    Copy-Item .env.example .env
    php artisan key:generate

    # navigate back to the apps directory
    Set-Location ..
}

Write-Host "All services configured."
