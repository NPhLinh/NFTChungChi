require('dotenv').config();
const port = process.env.HOST_PORT || 9090

module.exports = {
    networks: {
        shasta: {
            privateKey: process.env.PRIVATE_KEY_SHASTA,
            userFeePercentage: 50,
            feeLimit: 1000 * 1e6,
            fullHost: 'https://api.shasta.trongrid.io',
            network_id: '2'
        },
        development: {
            // For tronbox/tre docker image
            privateKey: '0000000000000000000000000000000000000000000000000000000000000001',
            userFeePercentage: 0,
            feeLimit: 1000 * 1e6,
            fullHost: 'http://127.0.0.1:' + port,
            network_id: '9'
        }
    },
    compilers: {
        solc: {
            version: '0.5.10'
        }
    }
}
