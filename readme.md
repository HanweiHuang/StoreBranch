#API

Add a Store Branch:
Method POST
url: 'http://your_domain/api/addStoreBranch'

|Name|Description|Type|Type|
|---|---|---|---|
|id|identity|numeric|required|
| name | name | string | option |
| parent | parent id | numeric | option |


Update a Store Branch
Method PUT
url: 'http://your_domain/api/addStoreBranch'

|Name|Description|Type|Type|
|---|---|---|---|
| id | identity |numeric| required |
| name | name | string | required |


Move A Store Branch
Method PUT
url: 'http://your_domain/api/moveStoreBranch'

|Name|Description|Type|Para|
|---|---|---|---|
| id | identity |numeric| required |
| Parent | parent id | numeric | required |


view A Single Store Branch
Method GET
url: 'http://your_domain/api/viewStoreBranch'

|Name|Description|Type|Para|
|---|---|---|---|
| id | identity |numeric| required |


view A Store Branch with its all children
Method GET
url: 'http://your_domain/api/viewGroupStoreBranch'

|Name|Description|Type|Para|
|---|---|---|---|
| id | identity |numeric| required |


view A Store Branch with its all children
Method GET
url: 'http://your_domain/api/viewAllStoreBranch'
No Parameters Needed


delete A Store Branch with its all children
Method DELETE
url: 'http://your_domain/api/deleteStoreBranch'

|Name|Description|Type|Para|
|---|---|---|---|
| id | identity |numeric| required |

#Doc code
```
//migrate database
php artisan migrate 
//run command to initial a tree in database
php artisan db:seed --class=StoreBranchSeeder 

```
Models/StoreBranch.php //StoreBranch model <br>
routes/api.php //api route <br>
controller/api/* //for all achievements <br>
Requests/StoreBranchRequest.php  //validate request parameters <br>
