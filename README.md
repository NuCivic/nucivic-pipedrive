# nucivic-pipedrive
Pipedrive export for NuCivic

Requires composer. 

1. Run `composer install` in repository root after cloning locally. 
2. Create an app/config/config.yml file to store your pipedrive API key.
3. Create the empty folders `export` and `export/files` in your repository root.
4. Run `app/console export` to export all data. Add specific tables as arguments, IE, `app/console export users deals`. 

### Availlable tables for export

* deals
* dealParticipants
* persons
* dealProducts
* products
* organizations
* activities
* files
* notes
* users

Exporting files will download all files to `export/files` as well as create a csv of file objects.
