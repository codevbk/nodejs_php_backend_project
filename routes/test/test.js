const express = require('express');
const router = express.Router();
const util = require('util');
const exec = util.promisify(require('child_process').exec);
/* */

router.get('/', async (req, res) => {
    const fileName = "routes/test/test.php";
    /* */
    try {
        const requestData = JSON.stringify(req.query).replace(/"/g, '\\"');
        console.log("requestData: ", requestData);
        const { stdout, stderr } = await exec(`php ${fileName} "${requestData}"`);
        /* */
        console.log(`Output: ${stdout}`);
        const jsonData = JSON.parse(stdout);
        /* */
        res.json(jsonData);
    } catch (error) {
        console.error(`error: ${error.message}`);
        res.status(500).json({ error: 'Internal Server Error' });
    }
});

module.exports = router;