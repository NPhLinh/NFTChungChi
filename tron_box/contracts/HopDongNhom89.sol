// SPDX-License-Identifier: MIT
pragma solidity ^0.5.10;

import "openzeppelin-solidity/contracts/token/ERC721/ERC721Full.sol";

contract HopDongNhom89 is ERC721Full {
    constructor() ERC721Full("HopDongNhom89", "Nhom89") public {}

    function mintNFT(address recipient, uint256 tokenId, string memory tokenURI) public {
        _mint(recipient, tokenId);      // cấp quyền sở hữu token
        _setTokenURI(tokenId, tokenURI); // gán metadata cho token
    }
}
