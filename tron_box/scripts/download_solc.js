const fs = require('fs');
const path = require('path');
const https = require('https');
const os = require('os');

// Version cần download
const version = 'v0.5.10+commit.5a6ea5b1';
const solcFileName = `soljson_v0.5.10.js`;
const solcUrl = `https://binaries.soliditylang.org/bin/soljson-${version}.js`;

// Thư mục .tronbox/solc/ trong User Home
const tronboxSolcFolder = path.join(os.homedir(), '.tronbox', 'solc');
const savePath = path.join(tronboxSolcFolder, solcFileName);

// Hàm tải file
function download(url, dest) {
    return new Promise((resolve, reject) => {
        const file = fs.createWriteStream(dest);
        https.get(url, (response) => {
            if (response.statusCode !== 200) {
                return reject(`Request Failed. Status Code: ${response.statusCode}`);
            }
            response.pipe(file);
            file.on('finish', () => file.close(resolve));
        }).on('error', (err) => {
            fs.unlink(dest, () => reject(err.message));
        });
    });
}

async function main() {
    try {
        if (!fs.existsSync(tronboxSolcFolder)) {
            fs.mkdirSync(tronboxSolcFolder, { recursive: true });
            console.log('✅ Created folder:', tronboxSolcFolder);
        }

        if (fs.existsSync(savePath)) {
            console.log(`✅ Already downloaded: ${savePath}`);
            return;
        }

        console.log(`⏳ Downloading Solidity Compiler ${version}...`);
        await download(solcUrl, savePath);
        console.log(`🎯 Downloaded to ${savePath}`);
    } catch (error) {
        console.error('❌ Error downloading solc:', error);
    }
}

main();
