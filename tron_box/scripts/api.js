//// filepath: m:\BlockChain\TronBox\scripts\api.js
require('dotenv').config();
const express = require('express');
const bodyParser = require('body-parser');
const TronWeb = require('tronweb');

const app = express();
app.use(bodyParser.json());

const privateKey = process.env.PRIVATE_KEY_SHASTA;
const contractAddress = process.env.TRON_CONTRACT_ADDRESS;

if (!privateKey || !contractAddress) {
    console.error('Missing PRIVATE_KEY_SHASTA or TRON_CONTRACT_ADDRESS in .env');
    process.exit(1);
}

const fullNode = new TronWeb.providers.HttpProvider('https://api.shasta.trongrid.io');
const solidityNode = new TronWeb.providers.HttpProvider('https://api.shasta.trongrid.io');
const eventServer = 'https://api.shasta.trongrid.io';
const tronWeb = new TronWeb(fullNode, solidityNode, eventServer, privateKey);

app.post('/api/mint-nft', async (req, res) => {
    try {
        const { recipient, tokenURI } = req.body;
        const tokenId = Date.now();

        if (!recipient || !tokenURI) {
            return res.status(400).json({ success: false, error: 'Recipient and tokenURI are required.' });
        }

        const contract = await tronWeb.contract().at(contractAddress);

        const tx = await contract.mintNFT(recipient, tokenId, tokenURI).send({
            feeLimit: 100_000_000,
            callValue: 0,
        });

        console.log('Mint NFT transaction txID:', tx);

        res.json({ success: true, transactionHash: tx, tokenId: tokenId });
    } catch (error) {
        console.error('Mint NFT error:', error);
        res.status(400).json({ success: false, error: error.message });
    }
});

app.get('/api/transaction/:txHash/:tokenId', async (req, res) => {
    try {
        const { txHash, tokenId } = req.params;

        if (!txHash || txHash.length !== 64) {
            return res.status(400).json({ success: false, error: 'Invalid transaction hash.' });
        }

        if (!tokenId || isNaN(tokenId)) {
            return res.status(400).json({ success: false, error: 'Invalid tokenId.' });
        }

        const txInfo = await tronWeb.trx.getTransaction(txHash);
        const txDetail = await tronWeb.trx.getTransactionInfo(txHash);

        if (!txInfo) {
            return res.status(404).json({ success: false, error: 'Transaction not found.' });
        }

        let tokenUri = null;

        if (txDetail.receipt && txDetail.receipt.result === 'SUCCESS') {
            const contract = await tronWeb.contract().at(contractAddress);
            tokenUri = await contract.tokenURI(tokenId).call();
        }

        res.json({
            success: true,
            transaction: {
                hash: txHash,
                confirmed: txDetail.receipt && txDetail.receipt.result === 'SUCCESS',
                blockNumber: txInfo.blockNumber,
                from: tronWeb.address.fromHex(txInfo.raw_data.contract[0].parameter.value.owner_address),
                to: tronWeb.address.fromHex(txInfo.raw_data.contract[0].parameter.value.contract_address),
                contractRet: txDetail.receipt ? txDetail.receipt.result : null,
                tokenURI: tokenUri || null
            }
        });
    } catch (error) {
        console.error('Get transaction error:', error);
        res.status(400).json({ success: false, error: error.message });
    }
});


const port = process.env.HOST_PORT || 3000;
app.listen(port, () => {
    console.log(`API server is running on port ${port}`);
});
