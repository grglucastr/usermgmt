# User Management System


This is a challenge project applied by [InterNations](https://www.internations.org) as requirement for the [Backend Engineer](https://www.internations.org/career/vacancy/personio-99085?source=stackoverflow) role.

## Content
*  [Docs and Diagrams](#docs-and-diagrams)
  	 * [UML Diagram](#uml-diagram)
  	 * [Entity Relation Diagram](#er-diagram)
*  [Install Project](#install-project)
*  [Database](#db)
	 * [Configuration](#db-config)
	 * [Create Database](#db-create)
	 * [Create Tables](#db-create-tables)
*  [Running Application](#run-app)
*  [RESTFul API](#restful-api)


## <a name="docs-and-diagrams"></a>Docs and Diagrams

This is a project where the user assumes an admin role and manage other users. The users can be part of groups. The admin can manage groups by creating groups and add users to it. 

On this project, a group can have many users and a user can be part of many groups.

For more details, check the following diagrams.

### <a name="uml-diagram"></a>UML Diagram

![](https://i.ibb.co/8Mq6kXN/Class-Diagram.png)

### <a name="er-diagram"></a>ER Diagram
![](https://i.ibb.co/RHYFXyv/the-er.png)


## <a name="install-project"></a>Install Project
After download this project, you must have installed on your machine the following tools in order to get this project running:
* Git
* Composer
* MySQL
 
Git will clone this whole repository into your machine. To do it so, run the following command on your terminal:

```
git clone https://github.com/grglucastr/usermgmt.git
```

Go inside the folder ``` usermgmt ``` and run:
```
composer install
```
This will download all the necessary libraries and also provide some bundles.
Up next, we will be doing some database configurations.

## <a name="db"></a>Database
In this section, we will set the database, create the schema and create the tables. To automate this process, this project counts with the help of the Doctrine ORM.

### <a name="db-config"></a>Configuration
Update the ```.env``` file with your database configuration. Find the following line and update it with your database information:

```
DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name
```
 In the URI above, for the database name ```db_name``` you can pick whatever fits better for you.
 
 ### <a name="db-create"></a>Create Database
 
 In order to create the database, run the following command on your terminal:
 ```
./bin/console doctrine:database:create
```
This will create the schema where the tables will be lay on it.

### <a name="db-create-tables"></a>Create Tables

To create all the tables on the database, run the following command on your terminal:
 ```
./bin/console doctrine:migrations:migrate
```
This will run all the migrations and install all the tables on your database.

## <a name="run-app"></a>Running Application

After all the configuration, now lets run the app. In your terminal type the following command:
 ```
./bin/console server:run
```
and then access the URL through your browser to see the forms and pages.

## <a name="restful-api"></a>RESTFul API

The User Management System provides some endpoints to be consumed by others clients such as mobile app. All of the paths provide content in JSON. 

The table below describes the routes available. You can also list them by typing in your terminal:

 ```
./bin/console debug:router
```

| Method | Path | Description | Input Example |
| ------ | ------ | ------ | ------ |
| GET | /api/groups |List all groups from database | - |
| GET | /api/groups/{groupId} | Get a specific group by its ID | - |
| POST | /api/groups | Adds a new group to database | {"group_name": "Cooking"} |
| DELETE | /api/groups/{groupId} |Deletes a group from database if it doesn't contains any user.| - |
| GET | /api/groups/{groupId}/users  |Lists all the users from a specific group with its ID passed by URL parameter.| - |
| GET | /api/users | List all users from database | - | 
| POST | /api/users | Adds a new user to database. The username attribute is unique. If the same username is added more than once, than an error object is returned. | {"username": "grglucastr"} |
| GET | /api/users/{userIdOrUsername} | Get a specific user by passing its ID or its username to the URL parameter.  i.g.: ```/api/users/1``` or ```/api/users/grglucastr```|-|
| DELETE | /api/users/{userIdOrUsername}  | Delete a user from database by passing its ID or username | - |
| GET | /api/users/{userIdOrUsername}/groups/ | List all the groups that the user is attached to it | - |
| POST | /api/users/{userIdOrUsername}/groups/{groupId} | Adds a user to a specific group
| DELETE | /api/users/{userIdOrUsername}/groups/{groupId} | Removes a user from a specific group | - |

