#Requirements
1. Stable internet connection.
2. PHP Sqlite/pdo extension installed/ enabled.
3. PHP curl extension installed/ enabled.
4. Node installed.
5. Composer installed.

#Procuedure
1. Run composer install in the project root.
2. Navigate to front-end folder and run yarn install (Alternatively use npm).
3. Start front end app by running yarn start in the front-end folder (Alternatively use npm).
4. Start server by running  php -S localhost:8000 in the root folder (App has been configured to listen on this port).
5. Open localhost:3000 on your broswer (Default front end port) or any other port link that may be assigned to you.
6. Once open click get started.
7. Database setup happens in the background. Once complete click on view employees.
8. You will be redirected to a page containing the employee list.
9. Click on view details to display modal with employee details.