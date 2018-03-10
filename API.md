# API Documentation

**need authentication**: put JWT token in header (Authorization).

## User

### register

**request:**

- url: `/register.php`
- method: `POST`
- need authentication: `false`

```
{
  username: "sampleUser",
  password: "samplePass",
  email: "mail@example.com"
}
```

**response:**

```
{
  status: 1 (success) / 0 (fail),
  msg: "Success / Error: xxx"
}
```

### login

**request:**

- url: `/login.php`
- method: `POST`
- need authentication: `false`

```
{
  username: "sampleUser",
  password: "samplePass",
}
```

**response:**

```
{
  status: 1 (success) / 0 (fail),
  msg: "Success / Error: xxx",
  token: "jwt token"
}
```

### logout

- url: `/logout.php`
- method: `POST`
- need authentication: `false`

**request:**

```
{}
```

**response:**

```
{
  status: 1 (success) / 0 (fail),
  msg: "Success / Error: xxx"
}
```

## Lists

### all lists

- url: `/list/all.php`
- method: `GET`
- need authentication: `false`

**request:**

```
{}
```

**response:**

```
{
  status: 1 (success) / 0 (fail),
  msg: "Success / Error: xxx",
  lists: [
    {
      id: 1,
      name: "sample list",
      archived: 0
    }
  ]
}
```

### single list

- url: `/list/single.php?id={list_id}`
- method: `GET`
- need authentication: `false`

**request:**

```
{}
```

**response:**

```
{
  status: 1 (success) / 0 (fail),
  msg: "Success / Error: xxx",
  list: {
    id: "list id",
    name: "sample list",
    archived: 0
  } 
}
```

### search lists

- url: `/list/search.php?s={keyword}`
- method: `GET`
- need authentication: `false`

**request:**

```
{}
```

**response:**

```
{
  status: 1 (success) / 0 (fail),
  msg: "Success / Error: xxx",
  lists: [
    {
      id: 1,
      name: "sample list",
      archived: 0
    }
  ]
}
```
### add a list

- url: `/list/add.php`
- method: `POST`
- need authentication: `true`

**request:**

```
{
  name: "list name"
}
```

**response:**

```
{
  status: 1 (success) / 0 (fail),
  msg: "Success / Error: xxx"
}
```

### edit a list

- url: `/list/edit.php`
- method: `POST`
- need authentication: `true`

**request:**

```
{
  id: "list id",
  name: "list name"
}
```

**response:**

```
{
  status: 1 (success) / 0 (fail),
  msg: "Success / Error: xxx"
}
```

### archive / restore a list

- url: `/list/archive.php`
- method: `POST`
- need authentication: `true`

**request:**

```
{
  id: "list id",
  archived: 0/1
}
```

**response:**

```
{
  status: 1 (success) / 0 (fail),
  msg: "Success / Error: xxx"
}
```

### delete a list

- url: `/list/delete.php`
- method: `POST`
- need authentication: `true`

**request:**

```
{
  id: "list id"
}
```

**response:**

```
{
  status: 1 (success) / 0 (fail),
  msg: "Success / Error: xxx"
}
```

### uncheck all item in a list

- url: `/list/reuse.php`
- method: `POST`
- need authentication: `true`

**request:**

```
{
  id: "list id"
}
```

**response:**

```
{
  status: 1 (success) / 0 (fail),
  msg: "Success / Error: xxx"
}
```

## Items

### all items in a list

- url: `/item/all.php?list_id={list id}`
- method: `GET`
- need authentication: `false`

**request:**

```
{}
```

**response:**

```
{
  status: 1 (success) / 0 (fail),
  msg: "Success / Error: xxx",
  items: [
    {
      id: 1,
      name: "sample list",
      checked: 0
    }
  ]
}
```

### search items

- url: `/item/search.php?s={keyword}`
- method: `GET`
- need authentication: `false`

**request:**

```
{}
```

**response:**

```
{
  status: 1 (success) / 0 (fail),
  msg: "Success / Error: xxx",
  items: [
    {
      id: 1,
      name: "sample list",
      checked: 0
    }
  ]
}
```
### add a item

- url: `/item/add.php`
- method: `POST`
- need authentication: `true`

**request:**

```
{
  list_id: "list id",
  name: "item name"
}
```

**response:**

```
{
  status: 1 (success) / 0 (fail),
  msg: "Success / Error: xxx"
}
```

### edit a item

- url: `/item/edit.php`
- method: `POST`
- need authentication: `true`

**request:**

```
{
  id: "item id",
  name: "item name"
}
```

**response:**

```
{
  status: 1 (success) / 0 (fail),
  msg: "Success / Error: xxx"
}
```

### check / uncheck a item

- url: `/item/check.php`
- method: `POST`
- need authentication: `true`

**request:**

```
{
  id: "list id",
  checked: 0/1,
}
```

**response:**

```
{
  status: 1 (success) / 0 (fail),
  msg: "Success / Error: xxx"
}
```

### delete a item

- url: `/item/delete.php`
- method: `POST`
- need authentication: `true`

**request:**

```
{
  id: "item id"
}
```

**response:**

```
{
  status: 1 (success) / 0 (fail),
  msg: "Success / Error: xxx"
}
```





