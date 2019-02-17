#API

Add a Store Branch: <br>
Method POST <br>
url: 'http://your_domain/api/addStoreBranch' <br>

|Name|Description|Type|Type|
|---|---|---|---|
|id|identity|numeric|required|
| name | name | string | option |
| parent | parent id | numeric | option |
<br>

Update a Store Branch <br>
Method PUT <br>
url: 'http://your_domain/api/addStoreBranch' <br>

|Name|Description|Type|Type|
|---|---|---|---|
| id | identity |numeric| required |
| name | name | string | required |
<br>

Move A Store Branch <br>
Method PUT <br>
url: 'http://your_domain/api/moveStoreBranch' <br>

|Name|Description|Type|Para|
|---|---|---|---|
| id | identity |numeric| required |
| Parent | parent id | numeric | required |
<br>

view A Single Store Branch<br>
Method GET<br>
url: 'http://your_domain/api/viewStoreBranch'<br>

|Name|Description|Type|Para|
|---|---|---|---|
| id | identity |numeric| required |
<br>

view A Store Branch with its all children<br>
Method GET<br>
url: 'http://your_domain/api/viewGroupStoreBranch'<br>

|Name|Description|Type|Para|
|---|---|---|---|
| id | identity |numeric| required |
<br>

view A Store Branch with its all children<br>
Method GET<br>
url: 'http://your_domain/api/viewAllStoreBranch'<br>
No Parameters Needed
<br>
<br>
delete A Store Branch with its all children<br>
Method DELETE<br>
url: 'http://your_domain/api/deleteStoreBranch'<br>

|Name|Description|Type|Para|
|---|---|---|---|
| id | identity |numeric| required |
<br>

#seed and migrate code example
```
//migrate database
php artisan migrate 
//run command to initial a tree in database
php artisan db:seed --class=StoreBranchSeeder 

```

#import file description<br>
Models/StoreBranch.php //StoreBranch model <br>
routes/api.php //api route <br>
controller/api/* //for all achievements <br>
Requests/StoreBranchRequest.php  //validate request parameters <br>

#api package 
dingo
