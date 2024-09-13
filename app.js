const express = require('express');
const bodyParser = require('body-parser');
const util = require('util');
const exec = util.promisify(require('child_process').exec);
const dotenv = require('dotenv');
dotenv.config();
const app = express();
//const port = 3000;

const status = process.env.STATUS;

switch (status) {
    case 'DEVELOPMENT':
        const developmentKeys = {};
        for (const key in process.env) {
            if (key.endsWith('_DEV')) {
                developmentKeys[key] = process.env[key];
            }
        }
        
        break;
    case 'PRODUCTION':
        const productionKeys = {};
        for (const key in process.env) {
            if (key.endsWith('_PROD')) {
                productionKeys[key] = process.env[key];
            }
        }
        
        break;
    default:
        /* */
}

const port = 3000;

app.disable('x-powered-by');

app.use(bodyParser.json());

const apiPrefix = 'api';
const apiBasename = 'v1';
/* */
const allowedMethods = ["POST", "GET", "PUT", "DELETE"];
const allowedPaths = ["test"];
/* */
let checkRequirements = false;
let globalPath = "";

app.use((req, res, next) => {
    let checkMethod = false;
    let checkPath = false;
    /* */
    const requestMethod = req.method;
    
    const requestMethodIndex = allowedMethods.indexOf(requestMethod);
    if (requestMethodIndex > -1) {
        checkMethod = true;
    }
    const requestPaths = req.path;
    const pathParts = requestPaths.split('/').filter(part => part !== '').filter(part => part !== apiBasename);
    /* */
    const requestPathIndex = allowedPaths.indexOf(pathParts[0]);
    const requestPath = pathParts[0];
    globalPath = requestPath;
    
    if (requestPathIndex > -1) {
        checkPath = true;
        /* */
    }
    
    const requestHeaders = req.headers;
    
    const requestQueries = req.query;
    
    for (const key in requestQueries) {
        if (requestQueries.hasOwnProperty(key)) {
            const value = requestQueries[key];
            /* */
        }
    }
    res.setHeader('X-Content-Type-Options', 'nosniff');
    res.setHeader('Access-Control-Allow-Origin', '*');
    if(checkMethod == true && checkPath == true){
       /* */
        next();
    }else{
        res.status(400).send('Invalid Requirements');
    }
    /* */
});

const testRouter = require('./routes/test/test');
app.use('/test', testRouter);

/* */


app.listen(port, () => {
    console.log(`Server is running on port ${port}`);
});