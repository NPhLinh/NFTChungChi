<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChiTietCapQuyenController;
use App\Http\Controllers\ChiTietDonHangController;
use App\Http\Controllers\ChucNangController;
use App\Http\Controllers\ChucVuController;
use App\Http\Controllers\ChungChiController;
use App\Http\Controllers\DonHangController;
use App\Http\Controllers\GiaoDichController;
use App\Http\Controllers\HocVienController;
use App\Http\Controllers\LichSuGiaoDichController;
use App\Http\Controllers\NftGuiDenController;
use App\Http\Controllers\NhanThongTinLienHeController;
use App\Http\Controllers\ThongBaoController;
use App\Http\Controllers\ThongBaoNguoiNhanController;
use App\Http\Controllers\ThongKeController;
use App\Http\Controllers\ThongTinUploadController;
use App\Http\Controllers\ToChucCapChungChiController;
use App\Http\Controllers\UpFileImageController;
use App\Http\Controllers\ViNftController;
use App\Http\Controllers\YeuCauCapController;
use Illuminate\Support\Facades\Route;


Route::get('/xem-giao-dich', [GiaoDichController::class, 'index']);


Route::get('/admin/data', [AdminController::class,'getData']);
Route::post('/admin/dang-ky',[AdminController::class,'dangKy']);
Route::post('/admin/dang-nhap', [AdminController::class,'dangNhap']);
Route::post('/admin/kiem-tra-chia-khoa', [AdminController::class,'kiemTraChiaKhoa']);
Route::get('/admin/dang-xuat', [AdminController::class,'dangXuat']);
Route::get('/admin/dang-xuat-all', [AdminController::class,'dangXuatAll']);
Route::get('/admin/profile', [AdminController::class,'Profile']);
Route::post('/admin/update-profile', [AdminController::class,'updateProfile']);
Route::post('/admin/update-mat-khau', [AdminController::class,'updateMatKhau']);
Route::post('/admin/quen-mat-khau', [AdminController::class, 'actionQuenmatKhau']);
Route::post('/admin/lay-lai-mat-khau/{hash_reset}', [AdminController::class, 'actionLayLaiMatKhau']);
Route::get('/admin/chuc-nang/data', [ChucNangController::class, 'getDataChucNang']);
Route::get('/admin/chuc-vu/data', [ChucVuController::class, 'getDataChucVu']);
Route::post('/admin/chuc-vu/create', [ChucVuController::class, 'createDataChucVu']);
Route::delete('/admin/chuc-vu/delete/{id}', [ChucVuController::class, 'deleteChucVu']);
Route::post('/admin/chuc-vu/update', [ChucVuController::class, 'UpateChucVu']);
Route::get('/admin/chuc-nang-theo-chuc-vu/{id}', [ChiTietCapQuyenController::class, 'loadchiTietChucNang']);
Route::post('/admin/chi-tiet-cap-quyen/create', [ChiTietCapQuyenController::class, 'store']);
Route::post('/admin/chi-tiet-cap-quyen/delete', [ChiTietCapQuyenController::class, 'destroy']);

Route::post('/admin/chuc-vu-nhan-vien/update', [AdminController::class,'updateChucVuNhanVien']);


Route::post('/admin/doi-trang-thai', [AdminController::class,'doiTrangThai']);
Route::post('/admin/hoc-vien/doi-trang-thai', [HocVienController::class,'doiTrangThaiHocVien']);
Route::post('/admin/to-chuc/doi-trang-thai', [ToChucCapChungChiController::class,'doiTrangThai']);

Route::get('/admin/cap-nft/data', [ChungChiController::class,'getDataCapNft']);
Route::post('/admin/cap-nft/create', [ChungChiController::class,'createCapNft']);

Route::post('/admin/tai-khoan-nhan-vien/tim-kiem', [AdminController::class, 'getTKTimKiem']);
Route::post('/admin/tai-khoan-nguoi-dung/tim-kiem', [HocVienController::class, 'getTKTimKiem']);
Route::post('/admin/tai-khoan-to-chuc/tim-kiem', [ToChucCapChungChiController::class, 'getTKTimKiem']);





Route::post('/hoc-vien/dang-ky', [HocVienController::class,'dangKy']);
Route::post('/hoc-vien/dang-nhap', [HocVienController::class,'dangNhap']);
Route::get('/hoc-vien/data', [HocVienController::class,'getData']);
Route::post('/hoc-vien/kiem-tra-chia-khoa', [HocVienController::class,'kiemTraChiaKhoa']);
Route::get('/hoc-vien/dang-xuat', [HocVienController::class,'dangXuat']);
Route::get('/hoc-vien/dang-xuat-all', [HocVienController::class,'dangXuatAll']);
Route::get('/hoc-vien/profile', [HocVienController::class,'Profile']);
Route::post('/hoc-vien/update-profile', [HocVienController::class,'updateProfile']);
Route::post('/hoc-vien/chon-avt', [HocVienController::class,'chonAvt']);
Route::post('/hoc-vien/update-mat-khau', [HocVienController::class,'updateMatKhau']);
Route::post('/hoc-vien/quen-mat-khau', [HocVienController::class, 'actionQuenmatKhau']);
Route::post('/hoc-vien/lay-lai-mat-khau/{hash_reset}', [HocVienController::class, 'actionLayLaiMatKhau']);

Route::post('/hoc-vien/dia-chi-vi/update', [HocVienController::class, 'capNhatDiaChiVi']);
Route::get('/hoc-vien/dia-chi-vi', [HocVienController::class, 'getDataDiaChiVi']);


Route::post('/to-chuc/dang-ky', [ToChucCapChungChiController::class,'dangKy']);
Route::post('/to-chuc/dang-nhap', [ToChucCapChungChiController::class,'dangNhap']);
Route::get('/to-chuc/data', [ToChucCapChungChiController::class,'getData']);
Route::post('/to-chuc/kiem-tra-chia-khoa', [ToChucCapChungChiController::class,'kiemTraChiaKhoa']);
Route::get('/to-chuc/dang-xuat-all', [ToChucCapChungChiController::class,'dangXuatAll']);
Route::get('/to-chuc/dang-xuat', [ToChucCapChungChiController::class,'dangXuat']);
Route::get('/to-chuc/profile', [ToChucCapChungChiController::class,'Profile']);
Route::post('/to-chuc/update-profile', [ToChucCapChungChiController::class,'updateProfile']);
Route::post('/to-chuc/chon-avt', [ToChucCapChungChiController::class,'chonAvt']);
Route::post('/to-chuc/update-mat-khau', [ToChucCapChungChiController::class,'updateMatKhau']);
Route::post('/to-chuc/quen-mat-khau', [ToChucCapChungChiController::class, 'actionQuenmatKhau']);
Route::post('/to-chuc/lay-lai-mat-khau/{hash_reset}', [ToChucCapChungChiController::class, 'actionLayLaiMatKhau']);



Route::get('list-ten/to-chuc/data', [ToChucCapChungChiController::class,'getDataTen']);





Route::post('/admin/gui-thong-bao', [ThongBaoController::class,'guiThongBao']);
Route::get('/admin/data-thong-bao', [ThongBaoController::class,'getData']);



Route::get('/xem-thong-bao', [ThongBaoNguoiNhanController::class,'xemThongBao']);
Route::get('/xem-chi-tiet-thong-bao/{id}', [ThongBaoNguoiNhanController::class,'xemChiTietThongBao']);
Route::post('/xoa-thong-bao', [ThongBaoNguoiNhanController::class,'xoaThongBao']);



Route::post('/hoc-vien/thanh-toan', [DonHangController::class,'actionThanhToan']);//bước 2

Route::get('/hoc-vien/lich-su-giao-dich', [DonHangController::class,'getHocVienLichSuGiaoDich']);


Route::post('/them-vao-thanh-toan', [ChiTietDonHangController::class,'themVaoThanhToan']); //bước1
Route::get('/hoc-vien/can-thanh-toan', [ChiTietDonHangController::class,'getData']);
Route::post('/hoc-vien/xoa-don-chi-tiet', [ChiTietDonHangController::class,'xoaDonChiTiet']);


Route::get('/admin/quan-ly-chung-chi/data', [ChungChiController::class,'getDataADChungChi']);
Route::get('/hoc-vien/chung-chi-chua-cap', [ChungChiController::class,'getDataHv']);
Route::get('/hoc-vien/chung-chi-nft', [ChungChiController::class,'getDataNft']);
Route::get('/to-chuc/chung-chi-nft', [ChungChiController::class,'getDataTc']);
Route::post('to-chuc/change-vo-hieu-hoa', [ChungChiController::class,'changeVoHieuHoa']);// là nft

Route::post('/to-chuc/tao-chung-chi', [ChungChiController::class,'taoChungChi']);//chưa có token


Route::post('/hoc-vien/yeu-cau-cap', [YeuCauCapController::class, 'guiYeuCauCap']);


Route::get('/to-chuc/truy-xuat-getdata/{id}', [YeuCauCapController::class, 'getDataTruyXuat']);


Route::get('/get-yeu-cau-cap-data', [YeuCauCapController::class, 'getData']);


Route::post('/upload-folder', [UpFileImageController::class, 'uploadFolder']);




Route::post('/import-excel', [ThongTinUploadController::class, 'import']);
Route::get('/get-data', [ThongTinUploadController::class, 'getData']);


Route::get('/hoc-vien/vi-nft/get-data', [ViNftController::class, 'getDataViHV']);


Route::get('/admin/thong-ke-doanh-thu/data', [ThongKeController::class, 'getThongKeDoanhThu']);
Route::get('/admin/thong-ke-nft-da-cap/data', [ThongKeController::class, 'getThongKeNFTDaCap']);


Route::post('/hoc-vien/gui-nft', [NftGuiDenController::class, 'guiNFT']);
Route::get('/hoc-vien/nhan-nft', [NftGuiDenController::class, 'nhanNFT']);
Route::get('/hoc-vien/nhan-nft/chi-tiet/{id}', [NftGuiDenController::class,'xemChiTietNFTGuiDen']);
Route::post('/xoa-NFT-gui-den', [NftGuiDenController::class,'xoaNFTGuiDen']);


Route::get('/hoc-vien/nhan-nft/chi-tiet/{id}', [NftGuiDenController::class,'xemChiTietNFTGuiDen']);



Route::get('/admin/lich-su-giao-dich-nhan', [LichSuGiaoDichController::class,'lsGiaoDichNhan']);
Route::get('/admin/lich-su-giao-dich-gui', [LichSuGiaoDichController::class,'lsGiaoDichGui']);


Route::post('/admin/nhan-thong-tin-lien-he/tao', [NhanThongTinLienHeController::class,'tao']);
Route::get('/admin/nhan-thong-tin-lien-he/xem', [NhanThongTinLienHeController::class,'xem']);



//php artisan storage:link
//composer require shuchkin/simplexlsx
//php artisan queue:work đếm thời gian giao dịch

