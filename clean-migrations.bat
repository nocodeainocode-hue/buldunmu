@echo off
setlocal enabledelayedexpansion

set DIR=D:\firmarehberiprojesi\firmarehberi\database\migrations

REM Delete old migration stubs
del /q "%DIR%\*_create_categories_table.php"
del /q "%DIR%\*_create_cities_table.php"
del /q "%DIR%\*_create_districts_table.php"
del /q "%DIR%\*_create_companies_table.php"
del /q "%DIR%\*_create_company_images_table.php"
del /q "%DIR%\*_create_listing_requests_table.php"
del /q "%DIR%\*_create_contact_messages_table.php"
del /q "%DIR%\*_create_site_settings_table.php"

echo Done deleting old migrations
