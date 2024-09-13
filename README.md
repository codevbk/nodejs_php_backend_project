# Backend Project Sample with Node.js and PHP

## Installation

```npm install```

## Running

### Development

```npm run start:development```

### Production

```npm run start:production```

## Documentation 

## GET /test 
This method and endpoint are used to retrieve existing data entries.

#### Postman
You can use Postman to send a GET request to:

Fetch all data: `http://localhost:3000/test`

```bash
http://localhost:3000/test
```

Fetch specific data by ID (replace [dataID] with the actual data ID): `http://localhost:3000/test/test_id=[dataID]`

```bash
http://localhost:3000/test/test_id=[dataID]
```

Fetch specific data by Name (replace [dataName] with the actual data Name): `http://localhost:3000/test/test_name=[dataName]`

```bash
http://localhost:3000/test/test_name=[dataName]
```

Fetch specific data by ID and Name (replace [dataID] and [dataName] with the actual data ID): `http://localhost:3000/test/test_id=[dataID]&test_name=[dataName]`

```bash
http://localhost:3000/test/test_id=[dataID]&test_name=[dataName]
```