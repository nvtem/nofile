const util = require('util')
const exec = require('child_process').exec
const promExec = util.promisify(exec)

function sleep(ms) {
    return new Promise(resolve => { setTimeout(() => { resolve() }, ms) })
}

async function getContainerIDByName(name) {
    while (true) {
        const { stdout } = await promExec(`docker ps -aqf "name=${name}"`)
        if (stdout)
            return stdout.trim()
        else
            await sleep(3000)
    }
}

(async () => {
    console.log('Please wait...')

    exec('docker-compose up --build')

    const apachephpContainerID = await getContainerIDByName('nofile-apache-php')
    const mysqlContainerID = await getContainerIDByName('nofile-mysql')

    while (true) {
        const { stdout } = await promExec(`docker exec ${mysqlContainerID} sh -c "netstat -tlpn | grep :::3306 | cat"`)

        if (stdout)
            break
        else
            await sleep(3000)
    }

    await promExec(`docker exec ${apachephpContainerID} composer i`)
    await promExec(`docker exec ${apachephpContainerID} php yii migrate --interactive=0`)

    console.log('Done!')
})()